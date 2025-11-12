<?php
// github_update.php — GitHub Webhook Deployment Handler with Auto Backup, Retention, and Email Alerts
// Enhanced with manual trigger option

ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(300);

// ============================================
// CONFIGURATION
// ============================================
$GITHUB_SECRET = 'shoreshotel_secret_2025';      // For webhook verification
$MANUAL_KEY    = 'shoreshotel2024_github_deploy'; // For manual browser trigger
$LOG_FILE      = '/home/jupiterc/domains/shoreshotelng.com/shores_website/storage/logs/deployment.log';
$LARAVEL_PATH  = '/home/jupiterc/domains/shoreshotelng.com/shores_website';
$PUBLIC_PATH   = '/home/jupiterc/domains/shoreshotelng.com/public_html';
$BACKUP_BASE   = '/home/jupiterc/backups';
$ADMIN_EMAIL   = 'peteradetola@gmail.com';
$FROM_EMAIL    = 'deploy@shoreshotelng.com';
$MAX_BACKUPS   = 6; // keep only 6 most recent backups

// ============================================
// HELPERS
// ============================================
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

function runCommand($cmd, $cwd = '/', $timeout = 60) {
    logMessage("→ $cmd");
    $descriptors = [
        0 => ["pipe", "r"],
        1 => ["pipe", "w"],
        2 => ["pipe", "w"]
    ];
    $process = proc_open($cmd, $descriptors, $pipes, $cwd);
    if (!is_resource($process)) {
        logMessage("❌ Failed to start process: $cmd");
        return false;
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
    return $exitCode === 0;
}

// ============================================
// VIEW LOG OPTION
// ============================================
if (isset($_GET['view_log']) && isset($_GET['key']) && $_GET['key'] === $MANUAL_KEY) {
    header('Content-Type: text/plain');
    if (file_exists($LOG_FILE)) {
        readfile($LOG_FILE);
    } else {
        echo "No deployment log found.";
    }
    exit;
}

// ============================================
// AUTHENTICATION: GitHub Webhook OR Manual Trigger
// ============================================
$isManualTrigger = isset($_GET['key']);
$isAuthenticated = false;

if ($isManualTrigger) {
    // Manual trigger via browser with ?key=
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
    // GitHub webhook - verify signature
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

// ============================================
// DEPLOYMENT WITH BACKUP & RETENTION
// ============================================
try {
    logMessage("=== 🚀 STARTING DEPLOYMENT ===");

    // Ensure backup directory exists
    if (!is_dir($BACKUP_BASE)) {
        mkdir($BACKUP_BASE, 0755, true);
    }

    // Create backup folder
    $backupDir = $BACKUP_BASE . '/shores_' . date('Ymd_His');
    mkdir($backupDir, 0755, true);

    // ============================================
    // BACKUP: Laravel app + public_html
    // ============================================
    logMessage("📦 Creating backup at: $backupDir");

    // Backup Laravel project
    runCommand("cp -r " . escapeshellarg($LARAVEL_PATH) . " " . escapeshellarg("$backupDir/shores_website"), '/');

    // Backup public_html
    if (is_dir($PUBLIC_PATH)) {
        runCommand("cp -r " . escapeshellarg($PUBLIC_PATH) . " " . escapeshellarg("$backupDir/public_html"), '/');
    }

    logMessage("✅ Backup completed");

    // ====== Keep only last N backups ======
    $backups = glob("$BACKUP_BASE/shores_*", GLOB_ONLYDIR);
    rsort($backups); // newest first
    if (count($backups) > $MAX_BACKUPS) {
        $toDelete = array_slice($backups, $MAX_BACKUPS);
        foreach ($toDelete as $old) {
            runCommand("rm -rf " . escapeshellarg($old));
            logMessage("🗑️ Deleted old backup: $old");
        }
    }

    // ============================================
    // STEP 1: GIT RESET + PULL
    // ============================================
    logMessage("🔁 Pulling latest code from GitHub...");

    // Remove blocking files first
    if (file_exists("$LARAVEL_PATH/vendor.zip")) {
        runCommand("rm -f " . escapeshellarg("$LARAVEL_PATH/vendor.zip"));
        logMessage("Removed vendor.zip");
    }

    runCommand('git reset --hard', $LARAVEL_PATH);
    runCommand('git pull origin main', $LARAVEL_PATH);

    // ============================================
    // STEP 2: COMPOSER INSTALL
    // ============================================
    logMessage("📦 Running composer install...");
    // Use HOME=/tmp workaround for shared hosting
    runCommand('HOME=/tmp composer install --no-dev --no-interaction --optimize-autoloader', $LARAVEL_PATH);

    // ============================================
    // STEP 3: CLEAR + CACHE LARAVEL
    // ============================================
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

    // ============================================
    // STEP 4: SYNC PUBLIC FILES (preserve index.php)
    // ============================================
    logMessage("🔁 Syncing public files...");

    // Backup server's index.php
    runCommand("cp " . escapeshellarg("$PUBLIC_PATH/index.php") . " /tmp/index.php.server.bak", '/');

    // Sync Laravel's public folder to public_html
    runCommand("rsync -av --exclude='index.php' $LARAVEL_PATH/public/ $PUBLIC_PATH/", '/');

    // Restore server's index.php
    runCommand("cp /tmp/index.php.server.bak " . escapeshellarg("$PUBLIC_PATH/index.php"), '/');

    logMessage("✅ Public sync completed");

    // ============================================
    // STEP 5: FIX PERMISSIONS
    // ============================================
    logMessage("🔒 Fixing permissions...");
    runCommand("chmod -R 775 storage bootstrap/cache", $LARAVEL_PATH);
    logMessage("✅ Permissions fixed");

    logMessage("=== ✅ DEPLOYMENT COMPLETED SUCCESSFULLY ===");

    $successMsg = "Deployment completed successfully on " . date('Y-m-d H:i:s') .
        "\n\nBackup created: $backupDir" .
        "\n\nTriggered via: " . ($isManualTrigger ? "Manual browser" : "GitHub webhook");

    sendEmail("✅ Shores Deployment Successful", $successMsg);

    if ($isManualTrigger) {
        // HTML response for browser
        echo "<!DOCTYPE html>
<html>
<head>
    <title>Deployment Successful</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #f0f0f0; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 20px; border-radius: 5px; max-width: 600px; margin: 0 auto; }
        h1 { margin-top: 0; }
        a { color: #155724; }
    </style>
</head>
<body>
    <div class='success'>
        <h1>✅ Deployment Completed Successfully</h1>
        <p><strong>Time:</strong> " . date('Y-m-d H:i:s') . "</p>
        <p><strong>Backup:</strong> " . basename($backupDir) . "</p>
        <p><a href='?key=$MANUAL_KEY&view_log=1'>View Deployment Log</a></p>
    </div>
</body>
</html>";
    } else {
        // JSON response for webhook
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'backup' => basename($backupDir),
            'time'   => date('Y-m-d H:i:s')
        ]);
    }

} catch (Exception $e) {
    $errorMsg = "❌ Deployment failed: " . $e->getMessage();
    logMessage($errorMsg);
    sendEmail("🚨 Shores Deployment Failed", $errorMsg);

    if ($isManualTrigger) {
        http_response_code(500);
        echo "<!DOCTYPE html>
<html>
<head>
    <title>Deployment Failed</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #f0f0f0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 20px; border-radius: 5px; max-width: 600px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class='error'>
        <h1>❌ Deployment Failed</h1>
        <p>" . htmlspecialchars($errorMsg) . "</p>
    </div>
</body>
</html>";
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
