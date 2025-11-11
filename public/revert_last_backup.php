<?php
// revert_last_backup.php — Web-safe rollback script with secret key

// ===========================
// CONFIGURATION
// ===========================
$SECRET_KEY = 'shoreshotel_secret_2025'; // Change this!
$BACKUP_BASE = '/home/jupiterc/backups';
$TARGET = '/home/jupiterc/domains/shoreshotelng.com/shores_website';
$LOG_FILE = '/home/jupiterc/domains/shoreshotelng.com/shores_website/storage/logs/rollback.log';

// ===========================
// HELPER FUNCTIONS
// ===========================
function logMessage($msg, $file) {
    file_put_contents($file, "[" . date('Y-m-d H:i:s') . "] $msg\n", FILE_APPEND);
}

// ===========================
// SECURITY CHECK
// ===========================
$key = $_GET['key'] ?? '';
if ($key !== $SECRET_KEY) {
    http_response_code(403);
    die('❌ Unauthorized');
}

logMessage("=== 🔄 Rollback Triggered via Web ===", $LOG_FILE);

// ===========================
// FIND LATEST BACKUP
// ===========================
$backups = glob("$BACKUP_BASE/shores_*", GLOB_ONLYDIR);
rsort($backups);

if (empty($backups)) {
    logMessage("❌ No backups found.", $LOG_FILE);
    die('❌ No backups available.');
}

$latest = $backups[0];
logMessage("Restoring from: $latest", $LOG_FILE);

// ===========================
// PERFORM RESTORE
// ===========================
shell_exec("rm -rf $TARGET");
shell_exec("cp -r $latest/shores_website $TARGET");
logMessage("✅ Restore complete", $LOG_FILE);

echo "✅ Restore complete from backup: " . basename($latest);
?>
