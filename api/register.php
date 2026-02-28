<?php
require_once 'db.php';

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    sendResponse(['error' => 'Invalid input'], 400);
}

// Validate required fields
$required = ['name', 'phone', 'password', 'mpesa_code'];
foreach ($required as $field) {
    if (empty($input[$field])) {
        sendResponse(['error' => "$field is required"], 400);
    }
}

// Validate phone number (Kenyan format)
if (!preg_match('/^[0-9]{10,12}$/', $input['phone'])) {
    sendResponse(['error' => 'Invalid phone number'], 400);
}

// Check if user exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE phone = ?");
$stmt->execute([$input['phone']]);
if ($stmt->fetch()) {
    sendResponse(['error' => 'Phone number already registered'], 400);
}

// Check M-PESA code (you can add validation here)
$stmt = $pdo->prepare("SELECT id FROM users WHERE mpesa_code = ?");
$stmt->execute([$input['mpesa_code']]);
if ($stmt->fetch()) {
    sendResponse(['error' => 'M-PESA code already used'], 400);
}

// Find referrer
$referredBy = null;
if (!empty($input['referral_code'])) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE referral_code = ?");
    $stmt->execute([$input['referral_code']]);
    $referrer = $stmt->fetch();
    if ($referrer) {
        $referredBy = $referrer['id'];
    }
}

// Generate unique referral code
$referralCode = 'AF' . strtoupper(substr(md5(uniqid()), 0, 6));

// Hash password
$hashedPassword = password_hash($input['password'], PASSWORD_DEFAULT);

// Start transaction
$pdo->beginTransaction();

try {
    // Insert user
    $stmt = $pdo->prepare("
        INSERT INTO users (name, phone, password, referral_code, referred_by, mpesa_code) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $input['name'],
        $input['phone'],
        $hashedPassword,
        $referralCode,
        $referredBy,
        $input['mpesa_code']
    ]);
    
    $userId = $pdo->lastInsertId();
    
    // If referred, create referral record and pay commission
    if ($referredBy) {
        // Create referral record
        $stmt = $pdo->prepare("
            INSERT INTO referrals (referrer_id, referred_id, commission, status, paid_at) 
            VALUES (?, ?, 150, 'paid', NOW())
        ");
        $stmt->execute([$referredBy, $userId]);
        
        // Update referrer's balance
        $stmt = $pdo->prepare("
            UPDATE users 
            SET balance = balance + 150, total_earned = total_earned + 150 
            WHERE id = ?
        ");
        $stmt->execute([$referredBy]);
    }
    
    $pdo->commit();
    
    // Generate token
    $token = generateToken($userId);
    
    sendResponse([
        'success' => true,
        'message' => 'Registration successful',
        'token' => $token,
        'user' => [
            'id' => $userId,
            'name' => $input['name'],
            'phone' => $input['phone'],
            'referral_code' => $referralCode,
            'balance' => 0
        ]
    ], 201);
    
} catch (Exception $e) {
    $pdo->rollBack();
    sendResponse(['error' => 'Registration failed: ' . $e->getMessage()], 500);
}
?>
