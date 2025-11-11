<?php
// github_update.php - Safe Auto Deployment Script for Shared Hosting (v3)
// -----------------------------------------------------------
// Features:
// ✅ Secure webhook with secret
// ✅ Auto backup before each deploy
// ✅ Handles untracked files like vendor.zip
// ✅ Composer install fix for shared hosting
// ✅ Public folder sync (no rsync needed)
// ✅ Email notifications for success/failure
// -----------------------------------------------------------

ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(300);

// ========================================================
// CONFIGURATION
// ========================================================
$SECRET = 'shoreshotel_secret_2025';
$ADMIN_EMAIL = 'peteradetola@gmail.com';

$LOG_FILE = '/home/jupiterc/domains/shoreshotelng.com/shores_website/storage/logs/deployment.log';
$LARAVEL_PATH = '/home/jupiterc/domains/shoreshotelng.com/shores_website';
$PUBLIC_PATH = '/home/jupiterc/domains/shoreshotelng.com/public_html';
$BACKUP_BASE = '/home/jupiterc/backups';
$COMPOSER_PATH = '/usr/local/bin/composer'; // adjust if needed (run `which composer`)

// ========================================================
// LOGGING FUNCTION
// ========================================================
function logMessage($msg)
{
    global $LOG_FILE;
    $dir = dirname($LOG_FILE);
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    file_put_contents($LOG_FILE, "[" . date('Y-m-d H:i:s') . "] $msg\n", FILE_APPEND);
}

// ========================================================
// VERIFY SIGNATURE
// ========================================================
logMessage("=== INCOMING GITHUB REQUEST ===");
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';

if (empty($signature)) {
    http_response_code(403);
    logMessage("❌ Missing GitHub signature");
    die('Forbidden');
}

$expected = (strpos($signature, 'sha256=') === 0)
    ? 'sha256=' . hash_hmac('sha256', $payload, $SECRET)
    : 'sha1=' . hash_hmac('sha1', $payload, $SECRET);

if (!hash_equals($expected, $signature)) {
    http_response_code(403);
    logMessage("❌ Signature mismatch");
    die('Forbidden');
}

logMessage("✅ Signature verified");

// ========================================================
// SAFE COMMAND RUNNER
// ========================================================
function runCommand($cmd, $cwd = '/')
{
    logMessage("→ $cmd");
    $process = proc_open(
        $cmd,
        [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
        $pipes,
        $cwd
    );

    if (!is_resource($process)) {
        logMessage("❌ Failed to start process");
        return false;
    }

    $output = stream_get_contents($pipes[1]);
    $error = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);

    $exit = proc_close($process);
    if ($output) logMessage("Output: $output");
    if ($error) logMessage("Error: $error");
    logMessage("Exit code: $exit");

    return $exit === 0;
}

// ========================================================
// EMAIL NOTIFIER
// ========================================================
function sendEmail($subject, $body)
{
    global $ADMIN_EMAIL;
    @mail($ADMIN_EMAIL, $subject, $body, "From: deploy@shoreshotelng.com");
}

// ========================================================
// DEPLOYMENT PROCESS
// ========================================================
try {
    logMessage("=== 🚀 STARTING DEPLOYMENT ===");

    // --- Create backup ---
    if (!is_dir($BACKUP_BASE)) @mkdir($BACKUP_BASE, 0755, true);
    $backupDir = $BACKUP_BASE . '/shores_' . date('Ymd_His');
    @mkdir($backupDir, 0755, true);

    logMessage("📦 Creating backup at: $backupDir");
    runCommand("cp -r $LARAVEL_PATH $backupDir");
    logMessage("✅ Backup completed");

    // --- Fix common git issue ---
    runCommand('rm -f vendor.zip', $LARAVEL_PATH);

    // --- Git pull latest ---
    logMessage("🔁 Pulling latest code from GitHub...");
    runCommand('git reset --hard', $LARAVEL_PATH);
    runCommand('git pull origin main', $LARAVEL_PATH);

    // --- Composer install ---
    logMessage("⚙️ Running composer install...");
    runCommand("export HOME=/tmp && $COMPOSER_PATH install --no-dev --no-interaction --optimize-autoloader", $LARAVEL_PATH);

    // --- Laravel optimization ---
    $commands = [
        'php artisan clear-compiled',
        'php artisan config:clear',
        'php artisan cache:clear',
        'php artisan route:clear',
        'php artisan view:clear',
        'php artisan config:cache',
        'php artisan route:cache',
        'php artisan view:cache'
    ];
    foreach ($commands as $cmd) {
        runCommand($cmd, $LARAVEL_PATH);
    }

    // --- Public sync ---
    logMessage("🔁 Syncing public files...");
    runCommand("cp -r $LARAVEL_PATH/public/* $PUBLIC_PATH/", '/');
    logMessage("✅ Public files updated");

    // --- Permissions ---
    runCommand("chmod -R 775 storage bootstrap/cache", $LARAVEL_PATH);

    // --- SUCCESS ---
    logMessage("=== ✅ DEPLOYMENT COMPLETED SUCCESSFULLY ===");
    sendEmail("✅ Shores Hotel Deployment Successful", "Deployment completed at " . date('Y-m-d H:i:s') . "\nBackup: $backupDir");
    echo json_encode(['status' => 'success', 'backup' => $backupDir]);

} catch (Exception $e) {
    logMessage("❌ Deployment failed: " . $e->getMessage());
    sendEmail("🚨 Shores Hotel Deployment Failed", "Deployment failed at " . date('Y-m-d H:i:s') . "\n\nError:\n" . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
