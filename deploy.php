<?php
/**
 * GitHub Webhook Deployment Script
 * Automatically deploys when you push to GitHub
 */

// Configuration
$SECRET = 'base64:hfoE/W5SyxyRIw/GLnlv0QTn/M2HlsQ31ehGFaqBaXM='; // Will set this in GitHub
$PROJECT_PATH = '/home/jupiterc/domains/https://shoreshotelng.com/public_html';
$BRANCH = 'main';
$LOG_FILE = 'deployment.log';

// Function to log messages
function logMessage($message) {
    global $LOG_FILE;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($LOG_FILE, "[$timestamp] $message\n", FILE_APPEND);
}

// Verify GitHub signature
function verifySignature($payload, $signature) {
    global $SECRET;
    $expected = 'sha256=' . hash_hmac('sha256', $payload, $SECRET);
    return hash_equals($expected, $signature);
}

// Main deployment logic
try {
    // Get payload
    $payload = file_get_contents('php://input');
    $signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

    // Verify request is from GitHub
    if (empty($signature) || !verifySignature($payload, $signature)) {
        http_response_code(403);
        logMessage('ERROR: Invalid signature - Unauthorized request');
        die('Unauthorized');
    }

    logMessage('====== Deployment Started ======');

    // Change to project directory
    chdir($PROJECT_PATH);
    logMessage('Changed to directory: ' . getcwd());

    // Pull latest changes
    logMessage('Pulling from GitHub...');
    exec("git pull origin $BRANCH 2>&1", $output, $return);
    logMessage('Git pull output: ' . implode("\n", $output));

    if ($return !== 0) {
        throw new Exception('Git pull failed');
    }

    // Composer install (if composer.json changed)
    logMessage('Running composer install...');
    exec('composer install --no-dev --optimize-autoloader 2>&1', $composerOutput);
    logMessage('Composer output: ' . implode("\n", $composerOutput));

    // Laravel optimization commands
    $commands = [
        'php artisan config:cache',
        'php artisan route:cache',
        'php artisan view:cache',
        'php artisan migrate --force'
    ];

    foreach ($commands as $cmd) {
        logMessage("Running: $cmd");
        exec("$cmd 2>&1", $cmdOutput);
        logMessage('Output: ' . implode("\n", $cmdOutput));
    }

    // Fix permissions
    exec('chmod -R 775 storage bootstrap/cache 2>&1');
    logMessage('Permissions updated');

    logMessage('====== Deployment Completed Successfully ======');

    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Deployment completed']);

} catch (Exception $e) {
    logMessage('ERROR: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
