<?php
echo "<h1>Simple Database Test</h1>";

// Turn on error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Testing database connection...</h2>";

$host = 'gateway01.us-west-2.prod.aws.tidbcloud.com';
$port = '4000';
$database = 'affiliatepro';
$username = '3p4nbvFzPNDPn35.root';
$password = 'R9m44lVeBeY5Pcrh';

try {
    echo "Attempting connection to: $host:$port<br>";
    
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected successfully!<br>";
    
    // Test query
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    echo "✅ Query executed: " . print_r($result, true) . "<br>";
    
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "<br>";
    echo "<pre>";
    echo "Error code: " . $e->getCode() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "</pre>";
}

echo "<h2>PHP Extensions:</h2>";
echo "PDO: " . (extension_loaded('pdo') ? '✅' : '❌') . "<br>";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? '✅' : '❌') . "<br>";
echo "MySQLi: " . (extension_loaded('mysqli') ? '✅' : '❌') . "<br>";
echo "OpenSSL: " . (extension_loaded('openssl') ? '✅' : '❌') . "<br>";
?>
