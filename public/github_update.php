<?php
// github_update.php — Safe GitHub Webhook Deployment Handler with Async Execution + Auto Backup + Email Alerts

ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(300);

// ============================================
// CONFIGURATION
// ============================================
$SECRET = 'shoreshotel_secret_2025';
$ADMIN_EMAIL = 'peteradetola@gmail.com';
$LOG_FILE = '/home/jupiterc/domains/shoreshotelng.com/shores_website/storage/logs/deployment.log';
$LARAVEL_PATH = '/home/jupiterc/domains/shoreshotelng.com/shores_website';
$PUBLIC_PATH = '/home/jupiterc/domains/shoreshotelng.com/public_html';
$BACKUP_BASE = '/home/jupiterc/backups';

// ============================================
// LOGGING FUNCTION
// ============================================
function logMessage($msg) {
    global $LOG_FILE;
    file_put_contents($LOG_FILE, "[" . date('Y-m-d H:i:s') . "] $msg\n", FILE_APPEND);
}

// ============================================
// VERIFY SIGNATURE
// ============================================
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$expected = 'sha256=' . hash_hmac('sha256', $payload, $GLOBALS['SECRET']);

if (!hash_equals($expected, $signature)) {
    http_response_code(403);
    logMessage("❌ Invalid signature — deployment aborted");
    die('Forbidden');
}

logMessage("✅ Signature verified — webhook accepted");

// ============================================
// RESPOND QUICKLY TO GITHUB
// ============================================
ignore_user_abort(true);
ob_start();
header('Content-Type: application/json');
$response = json_encode(['status' => 'accepted', 'message' => 'Deployment started in background']);
echo $response;
header("Connection: close");
header("Content-Length: " . strlen($response));
ob_end_flush();
flush();
if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request(); // Let PHP-FPM end HTTP connection
}

// ============================================
// CONTINUE DEPLOYMENT IN BACKGROUND
// ============================================
function runCommand($cmd, $cwd = '/', $env = []) {
    logMessage("→ $cmd");
    $proc = proc_open($cmd, [
        0 => ["pipe", "r"],
        1 => ["pipe", "w"],
        2 => ["pipe", "w"]
    ], $pipes, $cwd, $env);

    if (!is_resource($proc)) {
        logMessage("❌ Failed to start command: $cmd");
        return false;
    }

    fclose($pipes[0]);
    $output = stream_get_contents($pipes[1]);
    $error  = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exit = proc_close($proc);

    logMessage("Exit code: $exit");
    if ($output) logMessage("Output: $output");
    if ($error)  logMessage("Error: $error");
    return $exit === 0;
}

try {
    logMessage("=== 🚀 STARTING DEPLOYMENT (Background) ===");

    // ============================================
    // STEP 1: CREATE BACKUP
    // ============================================
    if (!is_dir($BACKUP_BASE)) mkdir($BACKUP_BASE, 0755, true);
    $backupDir = "$BACKUP_BASE/shores_" . date('Ymd_His');
    mkdir($backupDir, 0755, true);

    logMessage("📦 Creating backup at: $backupDir");
    runCommand("cp -r $LARAVEL_PATH $backupDir/shores_website");
    logMessage("✅ Backup completed");

    // ============================================
    // STEP 2: GIT UPDATE
    // ============================================
    chdir($LARAVEL_PATH);
    runCommand('git reset --hard', $LARAVEL_PATH);
    runCommand('git pull origin main', $LARAVEL_PATH);

    // ============================================
    // STEP 3: COMPOSER INSTALL (with temp HOME)
    // ============================================
    $env = ['HOME' => '/tmp', 'COMPOSER_HOME' => '/tmp'];
    runCommand('composer install --no-dev --no-interaction --optimize-autoloader', $LARAVEL_PATH, $env);

    // ============================================
    // STEP 4: CLEAR & CACHE LARAVEL
    // ============================================
    $artisan = [
        'php artisan clear-compiled',
        'php artisan config:clear',
        'php artisan cache:clear',
        'php artisan route:clear',
        'php artisan view:clear',
        'php artisan config:cache',
        'php artisan route:cache',
        'php artisan view:cache'
    ];
    foreach ($artisan as $cmd) runCommand($cmd, $LARAVEL_PATH);

    // ============================================
    // STEP 5: SYNC PUBLIC FILES
    // ============================================
    logMessage("🔁 Syncing public files...");
    runCommand("cp -r $LARAVEL_PATH/public/* $PUBLIC_PATH/");
    logMessage("✅ Public files updated");

    // ============================================
    // STEP 6: FIX PERMISSIONS
    // ============================================
    runCommand("chmod -R 775 storage bootstrap/cache", $LARAVEL_PATH);

    // ============================================
    // SUCCESS EMAIL
    // ============================================
    $subject = "✅ Shores Hotel Deployment Successful";
    $message = "Deployment completed successfully at " . date('Y-m-d H:i:s') . "\nBackup: $backupDir\n\nSee logs at $LOG_FILE";
    @mail($ADMIN_EMAIL, $subject, $message, "From: deploy@shoreshotelng.com");

    logMessage("=== ✅ DEPLOYMENT COMPLETED SUCCESSFULLY ===");

} catch (Exception $e) {
    $msg = "❌ Deployment failed: " . $e->getMessage();
    logMessage($msg);
    @mail($ADMIN_EMAIL, "🚨 Shores Hotel Deployment Failed", $msg, "From: deploy@shoreshotelng.com");
}
?>
