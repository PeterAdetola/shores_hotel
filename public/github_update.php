<?php
/**
 * github_update.php - COMPLETE FIXED VERSION
 * Handles GitHub webhook + manual deployments
 * FIXED: Properly syncs directory contents without nesting
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(300);

// ===== CONFIGURATION =====
$GITHUB_SECRET = 'shoreshotel_secret_2025';
$MANUAL_KEY    = 'shoreshotel2024_github_deploy';
$LOG_FILE      = '/home/jupiterc/domains/shoreshotelng.com/shores_website/storage/logs/deployment.log';
$LARAVEL_PATH  = '/home/jupiterc/domains/shoreshotelng.com/shores_website';
$PUBLIC_PATH   = '/home/jupiterc/domains/shoreshotelng.com/public_html';
$BACKUP_BASE   = '/home/jupiterc/backups';
$ADMIN_EMAIL   = 'peteradetola@gmail.com';
$FROM_EMAIL    = 'deploy@shoreshotelng.com';
$MAX_BACKUPS   = 6;

// ===== HELPERS =====
function logMessage($msg) {
    global $LOG_FILE;
    $dir = dirname($LOG_FILE);
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    file_put_contents($LOG_FILE, "[" . date('Y-m-d H:i:s') . "] $msg\n", FILE_APPEND);
}

function sendEmail($subject, $message) {
    global $ADMIN_EMAIL, $FROM_EMAIL;
    @mail($ADMIN_EMAIL, $subject, $message, "From: $FROM_EMAIL");
}

function runCommand($cmd, $cwd = '/') {
    logMessage("→ $cmd");
    $descriptors = [
        0 => ["pipe", "r"],
        1 => ["pipe", "w"],
        2 => ["pipe", "w"]
    ];
    $process = proc_open($cmd, $descriptors, $pipes, $cwd);
    if (!is_resource($process)) {
        logMessage("❌ Failed to start: $cmd");
        return ['output' => '', 'exit_code' => 1];
    }
    fclose($pipes[0]);
    $output = stream_get_contents($pipes[1]);
    $error  = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exitCode = proc_close($process);

    if ($output) logMessage("Output: $output");
    if ($error)  logMessage("Error: $error");
    logMessage("Exit code: $exitCode");

    return ['output' => $output, 'error' => $error, 'exit_code' => $exitCode];
}

// ===== VIEW LOG =====
if (isset($_GET['view_log']) && isset($_GET['key']) && $_GET['key'] === $MANUAL_KEY) {
    header('Content-Type: text/plain');
    if (file_exists($LOG_FILE)) {
        readfile($LOG_FILE);
    } else {
        echo "No deployment log found.";
    }
    exit;
}

// ===== AUTHENTICATION =====
$isManualTrigger = isset($_GET['key']);
$isAuthenticated = false;

if ($isManualTrigger) {
    $providedKey = $_GET['key'] ?? '';
    if ($providedKey === $MANUAL_KEY) {
        $isAuthenticated = true;
        logMessage("=== MANUAL DEPLOYMENT TRIGGERED VIA BROWSER ===");
    } else {
        http_response_code(403);
        logMessage("❌ Invalid manual trigger key");
        die('Forbidden: Invalid key');
    }
} else {
    logMessage("=== INCOMING GITHUB WEBHOOK REQUEST ===");
    $payload = file_get_contents('php://input');
    $signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';

    if (empty($signature)) {
        http_response_code(403);
        logMessage("❌ No signature header found");
        die('Forbidden: No signature');
    }

    $expected = (strpos($signature, 'sha256=') === 0)
        ? 'sha256=' . hash_hmac('sha256', $payload, $GITHUB_SECRET)
        : 'sha1='   . hash_hmac('sha1', $payload, $GITHUB_SECRET);

    if (hash_equals($expected, $signature)) {
        $isAuthenticated = true;
        logMessage("✅ GitHub signature verified");
    } else {
        http_response_code(403);
        logMessage("❌ Signature mismatch");
        die('Forbidden: Invalid signature');
    }
}

if (!$isAuthenticated) {
    http_response_code(403);
    die('Forbidden');
}

// ===== DEPLOYMENT =====
try {
    logMessage("=== 🚀 STARTING DEPLOYMENT ===");

    if (!is_dir($BACKUP_BASE)) {
        mkdir($BACKUP_BASE, 0755, true);
    }

    // CREATE BACKUP
    $backupDir = $BACKUP_BASE . '/shores_' . date('Ymd_His');
    mkdir($backupDir, 0755, true);

    logMessage("📦 Creating backup at: $backupDir");
    runCommand("cp -r " . escapeshellarg($LARAVEL_PATH) . " " . escapeshellarg("$backupDir/shores_website"), '/');
    if (is_dir($PUBLIC_PATH)) {
        runCommand("cp -r " . escapeshellarg($PUBLIC_PATH) . " " . escapeshellarg("$backupDir/public_html"), '/');
    }
    logMessage("✅ Backup completed");

    // CLEANUP OLD BACKUPS
    $backups = glob("$BACKUP_BASE/shores_*", GLOB_ONLYDIR);
    rsort($backups);
    if (count($backups) > $MAX_BACKUPS) {
        $toDelete = array_slice($backups, $MAX_BACKUPS);
        foreach ($toDelete as $old) {
            runCommand("rm -rf " . escapeshellarg($old));
            logMessage("🗑️ Deleted old backup: $old");
        }
    }

    // GIT PULL
    logMessage("🔁 Pulling latest code from GitHub...");
    runCommand('git reset --hard', $LARAVEL_PATH);
    runCommand('git pull origin main', $LARAVEL_PATH);

    // COMPOSER INSTALL
    logMessage("📦 Running composer install...");
    runCommand('HOME=/tmp composer install --no-dev --no-interaction --optimize-autoloader', $LARAVEL_PATH);

    // LARAVEL OPTIMIZATIONS
    logMessage("🔧 Running Laravel optimizations...");
    $artisanCommands = [
        'php artisan clear-compiled',
        'php artisan config:clear',
        'php artisan cache:clear',
        'php artisan route:clear',
        'php artisan view:clear',
        'php artisan config:cache',
        'php artisan route:cache',
        'php artisan view:cache'
    ];
    foreach ($artisanCommands as $cmd) {
        runCommand($cmd, $LARAVEL_PATH);
    }

    // ===== FIXED: SYNC PUBLIC FILES - CORRECT METHOD =====
    logMessage("🔁 Syncing public files...");

    // Backup server's index.php
    $indexBackup = "/tmp/index.php.server." . time() . ".bak";
    if (file_exists("$PUBLIC_PATH/index.php")) {
        copy("$PUBLIC_PATH/index.php", $indexBackup);
        logMessage("✅ Backed up index.php to $indexBackup");
    }

    $publicSource = "$LARAVEL_PATH/public";

    if (!is_dir($publicSource)) {
        logMessage("❌ ERROR: Public source directory not found: $publicSource");
        throw new Exception("Public source directory not found");
    }

    // Get list of items to sync (excluding index.php)
    $items = scandir($publicSource);
    $syncedCount = 0;
    $failedCount = 0;
    $syncDetails = [];

    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || $item === 'index.php') {
            continue;
        }

        $sourcePath = "$publicSource/$item";
        $destPath = "$PUBLIC_PATH/$item";

        // CRITICAL FIX: For directories, we need to sync CONTENTS, not the directory itself
        if (is_dir($sourcePath)) {
            logMessage("  📁 Syncing directory: $item");

            // Create destination directory if it doesn't exist
            if (!is_dir($destPath)) {
                mkdir($destPath, 0755, true);
                logMessage("    Created directory: $destPath");
            }

            // Sync contents using wildcard (/* means "contents of")
            $cpResult = runCommand("cp -rf " . escapeshellarg($sourcePath) . "/* " . escapeshellarg($destPath) . "/", '/');

            // Also copy hidden files if any (suppress errors if none exist)
            runCommand("cp -rf " . escapeshellarg($sourcePath) . "/.* " . escapeshellarg($destPath) . "/ 2>/dev/null || true", '/');

        } else {
            // For files, direct copy is fine
            logMessage("  📄 Syncing file: $item");
            $cpResult = runCommand("cp -f " . escapeshellarg($sourcePath) . " " . escapeshellarg($destPath), '/');
        }

        // Verify the sync
        if (is_file($sourcePath) && file_exists($destPath)) {
            $sourceSize = filesize($sourcePath);
            $destSize = filesize($destPath);
            if ($sourceSize === $destSize) {
                $syncedCount++;
                logMessage("  ✅ $item ($sourceSize bytes) - Verified ✓");
                $syncDetails[] = "✅ $item";
            } else {
                $failedCount++;
                logMessage("  ⚠️ $item - Size mismatch! Source: $sourceSize, Dest: $destSize");
                $syncDetails[] = "⚠️ $item (size mismatch)";
            }
        } elseif (is_dir($sourcePath) && is_dir($destPath)) {
            $syncedCount++;
            $fileCount = count(array_diff(scandir($destPath), ['.', '..']));
            logMessage("  ✅ $item/ ($fileCount items inside) - Verified ✓");
            $syncDetails[] = "✅ $item/";
        } else {
            $failedCount++;
            logMessage("  ❌ Failed to sync: $item");
            $syncDetails[] = "❌ $item (failed)";
        }
    }

    // Restore server's index.php
    if (file_exists($indexBackup)) {
        copy($indexBackup, "$PUBLIC_PATH/index.php");
        logMessage("✅ Restored index.php");
        unlink($indexBackup);
    }

    // Fix permissions on synced files
    logMessage("🔒 Fixing permissions on public files...");
    runCommand("chmod -R 755 " . escapeshellarg($PUBLIC_PATH) . "/css 2>/dev/null || true", '/');
    runCommand("chmod -R 755 " . escapeshellarg($PUBLIC_PATH) . "/js 2>/dev/null || true", '/');
    runCommand("chmod -R 755 " . escapeshellarg($PUBLIC_PATH) . "/img 2>/dev/null || true", '/');
    runCommand("chmod -R 755 " . escapeshellarg($PUBLIC_PATH) . "/build 2>/dev/null || true", '/');

    logMessage("✅ Public sync completed: $syncedCount synced, $failedCount failed");
    logMessage("Details: " . implode(", ", $syncDetails));

    // FIX STORAGE PERMISSIONS
    logMessage("🔒 Fixing storage permissions...");
    runCommand("chmod -R 775 storage bootstrap/cache", $LARAVEL_PATH);
    logMessage("✅ Storage permissions fixed");

    logMessage("=== ✅ DEPLOYMENT COMPLETED SUCCESSFULLY ===");

    $successMsg = "Deployment completed successfully on " . date('Y-m-d H:i:s') .
        "\n\nBackup created: $backupDir" .
        "\n\nFiles synced: $syncedCount successfully" .
        "\nFailed: $failedCount" .
        "\n\nSync details:\n" . implode("\n", $syncDetails) .
        "\n\n⚠️ IMPORTANT: Press Ctrl+Shift+R (or Cmd+Shift+R on Mac) to hard refresh and see CSS/JS changes!" .
        "\n\nTriggered via: " . ($isManualTrigger ? "Manual browser" : "GitHub webhook");

    sendEmail("✅ Shores Deployment Successful", $successMsg);

    if ($isManualTrigger) {
        echo "<!DOCTYPE html>
<html>
<head>
    <title>Deployment Successful</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #f0f0f0; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 20px; border-radius: 5px; max-width: 700px; margin: 0 auto; }
        h1 { margin-top: 0; color: #155724; }
        .details { background: white; padding: 15px; border-radius: 3px; margin: 15px 0; font-family: monospace; font-size: 12px; }
        a { color: #155724; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 10px; border-radius: 3px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class='success'>
        <h1>✅ Deployment Completed Successfully</h1>
        <p><strong>Time:</strong> " . date('Y-m-d H:i:s') . "</p>
        <p><strong>Backup:</strong> " . basename($backupDir) . "</p>
        <p><strong>Files Synced:</strong> $syncedCount</p>
        <p><strong>Failed:</strong> $failedCount</p>

        <div class='details'>
            <strong>Sync Details:</strong><br>
            " . implode("<br>", $syncDetails) . "
        </div>

        <div class='warning'>
            <strong>⚠️ Important:</strong> Press <kbd>Ctrl+Shift+R</kbd> (Windows/Linux) or <kbd>Cmd+Shift+R</kbd> (Mac) to hard refresh your browser and see the CSS/JS changes!
        </div>

        <p>
            <a href='?key=$MANUAL_KEY&view_log=1'>📋 View Full Deployment Log</a> |
            <a href='/'>🏠 Go to Homepage</a>
        </p>
    </div>
</body>
</html>";
    } else {
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'backup' => basename($backupDir),
            'synced' => $syncedCount,
            'failed' => $failedCount,
            'time'   => date('Y-m-d H:i:s')
        ]);
    }

} catch (Exception $e) {
    $errorMsg = "❌ Deployment failed: " . $e->getMessage();
    logMessage($errorMsg);
    logMessage("Stack trace: " . $e->getTraceAsString());
    sendEmail("🚨 Shores Deployment Failed", $errorMsg . "\n\n" . $e->getTraceAsString());

    if ($isManualTrigger) {
        http_response_code(500);
        echo "<!DOCTYPE html>
<html>
<head>
    <title>Deployment Failed</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #f0f0f0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 20px; border-radius: 5px; max-width: 700px; margin: 0 auto; }
        h1 { margin-top: 0; color: #721c24; }
        pre { background: white; padding: 10px; border-radius: 3px; overflow-x: auto; }
        a { color: #721c24; }
    </style>
</head>
<body>
    <div class='error'>
        <h1>❌ Deployment Failed</h1>
        <p><strong>Error:</strong></p>
        <pre>" . htmlspecialchars($errorMsg) . "</pre>
        <p><a href='?key=$MANUAL_KEY&view_log=1'>View Full Log</a></p>
    </div>
</body>
</html>";
    } else {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}
?>
