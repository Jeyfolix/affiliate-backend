<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'online',
    'message' => 'AffiliatePro API is running',
    'endpoints' => [
        'GET /' => 'This message',
        'GET /api/health.php' => 'Health check',
        'POST /api/login.php' => 'User login',
        'POST /api/register.php' => 'User registration',
        'GET /api/profile.php' => 'User profile (requires token)',
        'GET /api/referrals.php' => 'User referrals (requires token)',
        'GET /test.php' => 'PHP info',
        'GET /test-db-simple.php' => 'Test database connection'
    ],
    'timestamp' => date('Y-m-d H:i:s')
], JSON_PRETTY_PRINT);
?>
