<?php
echo "<h1>Database Connection Test</h1>";

// Try to include db.php
echo "<h2>1. Including db.php...</h2>";
try {
    include_once 'api/db.php';
    echo "✅ db.php included successfully<br>";
    echo "✅ PDO object exists: " . (isset($pdo) ? 'Yes' : 'No') . "<br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Show PHP info
echo "<h2>2. PHP Configuration:</h2>";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? '✅ Loaded' : '❌ Not loaded') . "<br>";
echo "OpenSSL: " . (extension_loaded('openssl') ? '✅ Loaded' : '❌ Not loaded') . "<br>";

// Check SSL certificates
echo "<h2>3. SSL Certificate paths:</h2>";
$paths = [
    '/etc/ssl/certs/ca-certificates.crt',
    '/etc/pki/tls/certs/ca-bundle.crt',
    '/etc/ssl/cert.pem'
];

foreach ($paths as $path) {
    echo "$path: " . (file_exists($path) ? '✅ Exists' : '❌ Not found') . "<br>";
}
?>
