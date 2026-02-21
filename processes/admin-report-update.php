<?php
session_start();
require_once '../includes/db-connection.php';
require_once '../includes/session-check.php';

requireAdmin();

$report_id = (int)($_POST['report_id'] ?? 0);
$status = $_POST['status'] ?? '';

if ($report_id <= 0 || !in_array($status, ['Pending', 'Reviewing', 'In Progress', 'Resolved', 'Closed', 'Requires Clarification'])) {
    header('Location: ../admin-dashboard.php?error=Invalid data');
    exit();
}

$stmt = $pdo->prepare("UPDATE reports SET status = ? WHERE report_id = ?");
$stmt->execute([$status, $report_id]);

header('Location: ../admin-dashboard.php?success=Report updated successfully');
exit();
?>
