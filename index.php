<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'message' => 'AffiliatePro Backend API',
    'version' => '1.0.0',
    'endpoints' => [
        'GET /' => 'This message',
        'GET /test.php' => 'PHP info',
        'GET /debug.php' => 'Debug information',
        'GET /api/health.php' => 'Health check',
        'POST /api/login.php' => 'User login',
        'POST /api/register.php' => 'User registration',
        'GET /api/profile.php' => 'User profile (auth required)',
        'GET /api/referrals.php' => 'User referrals (auth required)'
    ],
    'database' => 'TiDB (MySQL compatible)',
    'status' => 'operational'
], JSON_PRETTY_PRINT);
?>
