<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
define('SESSION_TIMEOUT', 1800);

function isLoggedIn() { return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true; }
function isAdminLoggedIn() { return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true; }
function getUserType() { if (isAdminLoggedIn()) return 'admin'; if (isLoggedIn()) return 'user'; return null; }
function getUserId() { if (isLoggedIn()) return $_SESSION['user_id'] ?? null; if (isAdminLoggedIn()) return $_SESSION['admin_id'] ?? null; return null; }
function getUserRole() { if (isAdminLoggedIn()) return $_SESSION['admin_role'] ?? null; if (isLoggedIn()) return $_SESSION['user_role'] ?? null; return null; }

function requireLogin() {
    if (!isLoggedIn() && !isAdminLoggedIn()) { header("Location: login.php?error=Please+log+in+to+access+this+page"); exit(); }
    checkSessionTimeout();
}
function requireAdmin() {
    if (!isAdminLoggedIn()) { header("Location: login.php?error=Admin+access+required"); exit(); }
    checkSessionTimeout();
}
function requireRole($roles) {
    requireLogin();
    if (!in_array(getUserRole(), (array)$roles)) { header("Location: index.php?error=Access+denied"); exit(); }
}

function checkSessionTimeout() {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        session_unset(); session_destroy();
        header("Location: login.php?error=Session+expired.+Please+log+in+again.");
        exit();
    }
    $_SESSION['last_activity'] = time();
}
function updateLastLogin($pdo, $type, $id) {
    try {
        $sql = $type === 'admin' ? "UPDATE admins SET last_login=NOW() WHERE admin_id=?" : "UPDATE users SET last_login=NOW() WHERE user_id=?";
        $pdo->prepare($sql)->execute([$id]);
    } catch(Exception $e){}
}
function logActivity($pdo, $action, $details='') {
    $user_id  = isLoggedIn() ? getUserId() : null;
    $admin_id = isAdminLoggedIn() ? getUserId() : null;
    try {
        $pdo->prepare("INSERT INTO activity_logs (user_id,admin_id,action,details,ip_address) VALUES (?,?,?,?,?)")
            ->execute([$user_id,$admin_id,$action,$details,$_SERVER['REMOTE_ADDR']??'']);
    } catch(Exception $e){}
}
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verifyCSRFToken($token) { return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token); }
function csrf_input() { return '<input type="hidden" name="csrf_token" value="'.htmlspecialchars(generateCSRFToken()).'">'; }
function sanitizeInput($data) { return htmlspecialchars(trim(stripslashes($data)), ENT_QUOTES, 'UTF-8'); }
function redirectToDashboard() {
    if (isAdminLoggedIn()) { header("Location: admin-dashboard.php"); }
    elseif (isLoggedIn()) { header("Location: " . (getUserRole()==='guest' ? 'dashboard2.php' : 'dashboard.php')); }
    else { header("Location: index.php"); }
    exit();
}
if ((isLoggedIn() || isAdminLoggedIn()) && isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        session_unset(); session_destroy();
        header("Location: login.php?error=Session+expired.+Please+log+in+again.");
        exit();
    }
    $_SESSION['last_activity'] = time();
}
