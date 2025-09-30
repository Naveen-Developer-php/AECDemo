<?php
session_start();
header('Content-Type: application/json');

// Import DB connection
include('db.php');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validate input
if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required']);
    exit;
}

try {
    // Fetch user by email
    $stmt = $dbh->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        exit;
    }

    // Store user session (exclude password)
    $_SESSION['user'] = [
        'fullname' => $user['fullname'],
        'email' => $user['email'],
        'age' => $user['age'],
        'dob' => $user['dob'],
        'contact' => $user['contact']
    ];

    // Return JSON response
    echo json_encode(['success' => true, 'user' => $_SESSION['user']]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: '.$e->getMessage()]);
}
exit;
