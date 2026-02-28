<?php
require_once 'db.php';

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    sendResponse(['error' => 'Invalid input'], 400);
}

// Validate required fields
if (empty($input['phone']) || empty($input['password'])) {
    sendResponse(['error' => 'Phone and password required'], 400);
}

// Get user
$stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
$stmt->execute([$input['phone']]);
$user = $stmt->fetch();

if (!$user) {
    sendResponse(['error' => 'Invalid credentials'], 401);
}

// Verify password
if (!password_verify($input['password'], $user['password'])) {
    sendResponse(['error' => 'Invalid credentials'], 401);
}

// Check if user is active
if (!$user['is_active']) {
    sendResponse(['error' => 'Account is deactivated'], 403);
}

// Generate token
$token = generateToken($user['id']);

sendResponse([
    'success' => true,
    'message' => 'Login successful',
    'token' => $token,
    'user' => [
        'id' => $user['id'],
        'name' => $user['name'],
        'phone' => $user['phone'],
        'referral_code' => $user['referral_code'],
        'balance' => $user['balance'],
        'total_earned' => $user['total_earned']
    ]
]);
?>
