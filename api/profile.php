<?php
require_once 'db.php';

// Get token from header
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';

if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
    sendResponse(['error' => 'No token provided'], 401);
}

$token = $matches[1];
$userId = verifyToken($token);

if (!$userId) {
    sendResponse(['error' => 'Invalid token'], 401);
}

// Get user profile
$stmt = $pdo->prepare("
    SELECT id, name, phone, email, referral_code, balance, total_earned, is_active, role, created_at 
    FROM users 
    WHERE id = ?
");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    sendResponse(['error' => 'User not found'], 404);
}

// Get referral count
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM referrals WHERE referrer_id = ? AND status = 'paid'");
$stmt->execute([$userId]);
$referralCount = $stmt->fetch();

$user['referral_count'] = $referralCount['count'];

sendResponse($user);
?>
