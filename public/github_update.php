<?php
// github_update.php — GitHub Webhook Deployment Handler with Auto Backup, Retention, and Email Alerts

ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(300);

// ============================================
// CONFIGURATION
// ============================================
$SECRET        = 'shoreshotel_secret_2025';
$LOG_FILE      = '/home/jupiterc/domains/shoreshotelng.com/shores_website/storage/logs/deployment.log';
$LARAVEL_PATH  = '/home/jupiterc/domains/shoreshotelng.com/shores_website';
$PUBLIC_PATH   = '/home/jupiterc/domains/shoreshotelng.com/public_html';
$BACKUP_BASE   = '/home/jupiterc/backups';
$ADMIN_EMAIL   = 'peteradetola@gmail.com';
$FROM_EMAIL    = 'deploy@shoreshotelng.com';
$MAX_BACKUPS   = 3; // keep only 3 most recent backups

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
// VERIFY GITHUB SIGNATURE
// ============================================
logMessage("=== INCOMING GITHUB REQUEST ===");
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';

if (empty($signature)) {
    http_response_code(403);
    logMessage("❌ No signature header found");
    die('Forbidden');
}

$expected = (strpos($signature, 'sha256=') === 0)
    ? 'sha256=' . hash_hmac('sha256', $payload, $SECRET)
    : 'sha1='   . hash_hmac('sha1', $payload, $SECRET);

if (!hash_equals($expected, $signature)) {
    http_response_code(403);
    logMessage("❌ Signature mismatch");
    die('Forbidden');
}

logMessage("✅ Signature verified");

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

    // Backup Laravel project
    logMessage("📦 Creating backup at: $backupDir");
    runCommand("cp -r $LARAVEL_PATH $backupDir", '/');
    logMessage("✅ Backup completed");

    // ====== Keep only last 3 backups ======
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
    runCommand('git reset --hard', $LARAVEL_PATH);
    runCommand('git pull origin main', $LARAVEL_PATH);

    // ============================================
    // STEP 2: COMPOSER INSTALL
    // ============================================
    runCommand('composer install --no-dev --no-interaction --optimize-autoloader', $LARAVEL_PATH);

    // ============================================
    // STEP 3: CLEAR + CACHE LARAVEL
    // ============================================
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
    // STEP 4: SYNC PUBLIC FILES
    // ============================================
    logMessage("🔁 Syncing public files...");
    runCommand("rsync -av --exclude='index.php' $LARAVEL_PATH/public/ $PUBLIC_PATH/", '/');
    logMessage("✅ Public sync completed");

    // ============================================
    // STEP 5: FIX PERMISSIONS
    // ============================================
    runCommand("chmod -R 775 storage bootstrap/cache", $LARAVEL_PATH);

    logMessage("=== ✅ DEPLOYMENT COMPLETED SUCCESSFULLY ===");
    sendEmail(
        "✅ Shores Deployment Successful",
        "Deployment completed successfully on " . date('Y-m-d H:i:s') .
        "\n\nBackup created: $backupDir"
    );

    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'backup' => basename($backupDir),
        'time'   => date('Y-m-d H:i:s')
    ]);

} catch (Exception $e) {
    $errorMsg = "❌ Deployment failed: " . $e->getMessage();
    logMessage($errorMsg);
    sendEmail("🚨 Shores Deployment Failed", $errorMsg);
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
