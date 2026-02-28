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

// Get user's referrals
$stmt = $pdo->prepare("
    SELECT 
        r.*,
        u.name as referred_name,
        u.phone as referred_phone
    FROM referrals r
    JOIN users u ON r.referred_id = u.id
    WHERE r.referrer_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$userId]);
$referrals = $stmt->fetchAll();

sendResponse($referrals);
?>
