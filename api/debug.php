<?php
header('Content-Type: application/json');

$response = [
    'status' => 'debug',
    'message' => 'Debug endpoint working',
    'server' => [
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'unknown',
        'script_filename' => $_SERVER['SCRIPT_FILENAME'] ?? 'unknown',
        'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
        'php_version' => phpversion()
    ],
    'files' => []
];

// Try to list files
$root = $_SERVER['DOCUMENT_ROOT'] ?? '/var/www/html';
$directories = [$root, $root . '/api'];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        $response['files'][basename($dir)] = array_values(array_diff($files, ['.', '..']));
    } else {
        $response['files'][basename($dir)] = "Directory not found: $dir";
    }
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
