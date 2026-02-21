<?php
require_once '../includes/db-connection.php';
require_once '../includes/session-check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../register.php");
    exit();
}

if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    header("Location: ../register.php?error=invalid_token");
    exit();
}

$first_name = sanitizeInput($_POST['first_name'] ?? '');
$last_name = sanitizeInput($_POST['last_name'] ?? '');
$middle_name = sanitizeInput($_POST['middle_name'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$contact_number = sanitizeInput($_POST['contact_number'] ?? '');
$house_number = sanitizeInput($_POST['house_number'] ?? '');
$street = sanitizeInput($_POST['street'] ?? '');
$purok = sanitizeInput($_POST['purok'] ?? '');

// Validation
if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
    header("Location: ../register.php?error=empty_fields");
    exit();
}

if ($password !== $confirm_password) {
    header("Location: ../register.php?error=password_mismatch");
    exit();
}

if (strlen($password) < 8) {
    header("Location: ../register.php?error=password_too_short");
    exit();
}

try {
    // Check if email already exists
    $check_sql = "SELECT user_id FROM users WHERE email = ?";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$email]);
    
    if ($check_stmt->fetch()) {
        header("Location: ../register.php?error=email_exists");
        exit();
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $sql = "INSERT INTO users (email, password, first_name, last_name, middle_name, contact_number, house_number, street, purok, role, verification_status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'citizen', 'pending')";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $hashed_password, $first_name, $last_name, $middle_name, $contact_number, $house_number, $street, $purok]);
    
    header("Location: ../login.php?success=registered");
    exit();
    
} catch (PDOException $e) {
    error_log("Registration error: " . $e->getMessage());
    header("Location: ../register.php?error=system_error");
    exit();
}
?>
