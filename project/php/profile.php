<?php
session_start();
header('Content-Type: application/json');

// Import DB connection
include('db.php');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$fullname = trim($_POST['fullname'] ?? '');
$email    = trim($_POST['email'] ?? '');
$age      = !empty($_POST['age']) ? intval($_POST['age']) : null;
$dob      = !empty($_POST['dob']) ? $_POST['dob'] : null;
$contact  = trim($_POST['contact'] ?? '');

// Validate
if (!$fullname || !$email) {
    echo json_encode(['success' => false, 'message' => 'Fullname and Email required']);
    exit;
}

try {
    // Update user
    $stmt = $dbh->prepare("UPDATE users SET fullname = :fullname, age = :age, dob = :dob, contact = :contact WHERE email = :email");
    $stmt->execute([
        ':fullname' => $fullname,
        ':age'      => $age,
        ':dob'      => $dob,
        ':contact'  => $contact,
        ':email'    => $email
    ]);

    // Return updated user info
    echo json_encode([
        'success' => true,
        'user' => [
            'fullname' => $fullname,
            'email' => $email,
            'age' => $age,
            'dob' => $dob,
            'contact' => $contact
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: '.$e->getMessage()]);
}
exit;
