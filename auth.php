<?php
// auth.php
require_once 'config.php';

function registerUser($firstName, $lastName, $email, $password, $accountType, $organization = null) {
    global $pdo;
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        return ['success' => false, 'message' => 'Email already exists'];
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, account_type, organization) VALUES (?, ?, ?, ?, ?, ?)");
    
    try {
        $stmt->execute([$firstName, $lastName, $email, $hashedPassword, $accountType, $organization]);
        return ['success' => true, 'message' => 'Registration successful'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
    }
}

function loginUser($email, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['account_type'] = $user['account_type'];
        
        return ['success' => true, 'message' => 'Login successful'];
    } else {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit;
}

function getUserBatches($userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM batches WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createBatch($userId, $name, $type, $description) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO batches (user_id, name, type, description) VALUES (?, ?, ?, ?)");
    
    try {
        $stmt->execute([$userId, $name, $type, $description]);
        return ['success' => true, 'message' => 'Batch created successfully', 'batch_id' => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to create batch: ' . $e->getMessage()];
    }
}
?>