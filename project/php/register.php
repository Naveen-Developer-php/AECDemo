<?php
session_start();
header('Content-Type: application/json'); // important for AJAX JSON response

// Import DB connection
include('db.php');

// Get and sanitize POST data
$fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$age      = !empty($_POST['age']) ? intval($_POST['age']) : null;
$dob      = !empty($_POST['dob']) ? $_POST['dob'] : null;
$contact  = isset($_POST['contact']) ? trim($_POST['contact']) : '';

// Basic validation
if (!$fullname || !$email || !$password || !$contact) {
    echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
    exit;
}

try {
    // Hash password
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Insert user
    $stmt = $dbh->prepare("INSERT INTO users (fullname,email,password,age,dob,contact) 
                           VALUES (:fullname,:email,:password,:age,:dob,:contact)");

    $stmt->execute([
        ':fullname' => $fullname,
        ':email'    => $email,
        ':password' => $passwordHash,
        ':age'      => $age,
        ':dob'      => $dob,
        ':contact'  => $contact
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) { // duplicate email
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Server Error: '.$e->getMessage()]);
    }
}
exit;
