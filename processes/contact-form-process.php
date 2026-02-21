<?php
require_once '../includes/db-connection.php';
require_once '../includes/session-check.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: ../contact.php"); exit(); }
$name = sanitizeInput($_POST['name'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$subject = sanitizeInput($_POST['subject'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');
try {
    $sql = "INSERT INTO contact_messages (name, email, subject, message, ip_address) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $subject, $message, $_SERVER['REMOTE_ADDR']]);
    header("Location: ../contact.php?success=message_sent");
    exit();
} catch (PDOException $e) {
    error_log("Contact form error: " . $e->getMessage());
    header("Location: ../contact.php?error=system_error");
    exit();
}
?>