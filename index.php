<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'message' => 'Backend API is running',
    'endpoints' => [
        '/api/health.php',
        '/api/login.php',
        '/api/register.php',
        '/api/profile.php',
        '/api/referrals.php',
        '/test_db.php',
        '/debug.php',
        '/api/debug.php'
    ]
], JSON_PRETTY_PRINT);
?>
