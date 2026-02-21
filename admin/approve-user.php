<?php
session_start();
require_once '../includes/db-connection.php';
require_once '../includes/session-check.php';
requireAdmin();
$id = (int)($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';
if (!$id || !in_array($action,['approve','reject'])) { header("Location: ../admin-dashboard.php?error=Invalid+request"); exit(); }
$new_status = ($action==='approve') ? 'verified' : 'rejected';
dbExec("UPDATE users SET verification_status=? WHERE user_id=?",[$new_status,$id]);
logActivity($pdo,'User '.ucfirst($action),'User #'.$id);
$msg = ($action==='approve') ? 'User+approved+successfully' : 'User+rejected';
header("Location: ../admin-dashboard.php?section=users&success=$msg");
exit();
