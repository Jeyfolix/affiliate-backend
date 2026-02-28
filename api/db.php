<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ✅ YOUR ACTUAL TiDB CONNECTION DETAILS
$host = 'gateway01.us-west-2.prod.aws.tidbcloud.com';
$port = '4000';
$database = 'affiliatepro';
$username = '3p4nbvFzPNDPn35.root';
$password = 'R9m44lVeBeY5Pcrh'; // REPLACE WITH YOUR PASSWORD

try {
    // For TiDB Serverless, SSL is required
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-certificates.crt', // Path for Kali Linux
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Helper functions
function generateToken($userId) {
    return bin2hex(random_bytes(32)) . '.' . $userId;
}

function verifyToken($token) {
    $parts = explode('.', $token);
    if (count($parts) !== 2) return false;
    return intval($parts[1]);
}

function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}

// Get Authorization header
function getAuthorizationHeader() {
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER['Authorization']);
    } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $headers = trim($_SERVER['HTTP_AUTHORIZATION']);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

// Get bearer token
function getBearerToken() {
    $headers = getAuthorizationHeader();
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}
?>
