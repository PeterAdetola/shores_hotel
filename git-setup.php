<?php
// git-setup.php - Run this ONCE to initialize Git
$secret = 'base64:hfoE/W5SyxyRIw/GLnlv0QTn/M2HlsQ31ehGFaqBaXM='; // Change this!

if (!isset($_GET['key']) || $_GET['key'] !== $secret) {
    die('Unauthorized');
}

echo "<h2>Git Setup</h2>";

// Your project path
$projectPath = '/home/jupiterc/domains/https://shoreshotelng.com/public_html';
chdir($projectPath);

echo "<h3>1. Checking current directory:</h3>";
echo "<pre>" . getcwd() . "</pre>";

echo "<h3>2. Initializing Git (if not already):</h3>";
echo "<pre>" . shell_exec('git init 2>&1') . "</pre>";

echo "<h3>3. Adding GitHub remote:</h3>";
$repoUrl = 'https://github.com/PeterAdetola/shores_hotel.git';
echo "<pre>" . shell_exec("git remote add origin $repoUrl 2>&1") . "</pre>";

echo "<h3>4. Fetching from GitHub:</h3>";
echo "<pre>" . shell_exec('git fetch origin 2>&1') . "</pre>";

echo "<h3>5. Checking out main branch:</h3>";
echo "<pre>" . shell_exec('git checkout main 2>&1') . "</pre>";

echo "<h3>6. First pull:</h3>";
echo "<pre>" . shell_exec('git pull origin main 2>&1') . "</pre>";

echo "<h3>Setup Complete!</h3>";
echo "<p>Delete this file now for security!</p>";
?>
