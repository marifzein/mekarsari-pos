<?php
// Token rahasia agar file tidak bisa diakses sembarangan orang
$secret_token = 'R4h4s14POS2026';

if (!isset($_GET['token']) || $_GET['token'] !== $secret_token) {
    http_response_code(403);
    die('Access Denied');
}

// Eksekusi git pull dari branch dev
$output = shell_exec('git pull origin dev 2>&1');
echo "<pre>$output</pre>";