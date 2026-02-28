<?php
// Redirect to API info or show API documentation
header('Content-Type: application/json');

// Check if API files exist
$api_exists = file_exists(__DIR__ . '/api/health.php');

echo json_encode([
    'status' => 'online',
    'message' => 'AffiliatePro API Backend',
    'api_location' => '/api/',
    'api_files_exist' => $api_exists,
    'endpoints' => [
        'GET /api/health.php' => 'Health check',
        'POST /api/login.php' => 'User login',
        'POST /api/register.php' => 'User registration',
        'GET /api/profile.php' => 'User profile (auth required)',
        'GET /api/referrals.php' => 'User referrals (auth required)',
        'GET /api/db.php' => 'Database configuration (debug)',
        'GET /test-db-simple.php' => 'Database test'
    ],
    'server_time' => date('Y-m-d H:i:s'),
    'php_version' => phpversion(),
    'note' => 'Your API files are in the /api/ directory'
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
