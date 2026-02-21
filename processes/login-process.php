<?php
session_start();
require_once '../includes/db-connection.php';
require_once '../includes/session-check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: ../login.php"); exit(); }

$email    = strtolower(trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)));
$password = $_POST['password'] ?? '';

if (!$email || !$password) { header("Location: ../login.php?error=Please+enter+email+and+password."); exit(); }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { header("Location: ../login.php?error=Invalid+email+format."); exit(); }

try {
    // 1. Check ADMINS table
    $admin = $pdo ? dbOne("SELECT * FROM admins WHERE email=? AND is_active=1", [$email]) : null;
    if ($admin) {
        // Auto-hash plain text password on first login
        if (strlen($admin['password']) < 50) {
            $hashed = password_hash($admin['password'], PASSWORD_BCRYPT, ['cost'=>12]);
            dbExec("UPDATE admins SET password=? WHERE admin_id=?", [$hashed, $admin['admin_id']]);
            $admin['password'] = $hashed;
        }
        if (password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id']        = $admin['admin_id'];
            $_SESSION['admin_email']     = $admin['email'];
            $_SESSION['admin_name']      = $admin['first_name'].' '.$admin['last_name'];
            $_SESSION['admin_role']      = $admin['role'];
            $_SESSION['last_activity']   = time();
            updateLastLogin($pdo, 'admin', $admin['admin_id']);
            logActivity($pdo, 'Admin Login', 'Admin '.$admin['email'].' logged in');
            header("Location: ../admin-dashboard.php");
            exit();
        }
    }

    // 2. Check USERS table
    $user = $pdo ? dbOne("SELECT * FROM users WHERE email=?", [$email]) : null;
    if ($user) {
        if (strlen($user['password']) < 50) {
            $hashed = password_hash($user['password'], PASSWORD_BCRYPT, ['cost'=>12]);
            dbExec("UPDATE users SET password=? WHERE user_id=?", [$hashed, $user['user_id']]);
            $user['password'] = $hashed;
        }
        if (password_verify($password, $user['password'])) {
            if ($user['verification_status'] === 'rejected') {
                header("Location: ../login.php?error=Your+account+has+been+rejected.+Contact+the+barangay+office.");
                exit();
            }
            session_regenerate_id(true);
            $_SESSION['user_logged_in']       = true;
            $_SESSION['user_id']              = $user['user_id'];
            $_SESSION['user_email']           = $user['email'];
            $_SESSION['user_name']            = $user['first_name'].' '.$user['last_name'];
            $_SESSION['user_role']            = $user['role'];
            $_SESSION['verification_status']  = $user['verification_status'];
            $_SESSION['last_activity']        = time();
            updateLastLogin($pdo, 'user', $user['user_id']);
            logActivity($pdo, 'User Login', 'User '.$user['email'].' logged in');
            header("Location: ../".($user['role']==='guest' ? 'dashboard2.php' : 'dashboard.php'));
            exit();
        }
    }

    // 3. DB not available? Check demo hardcoded accounts
    if (!$pdo) {
        $demo = [
            'admin@heyrosario.com'   => ['type'=>'admin','pass'=>'Admin123!','name'=>'System Admin','role'=>'admin','id'=>1],
            'captain@heyrosario.com' => ['type'=>'admin','pass'=>'Captain123!','name'=>'Maria Santos-Reyes','role'=>'captain','id'=>2],
            'staff1@heyrosario.com'  => ['type'=>'admin','pass'=>'Staff123!','name'=>'Jose dela Cruz','role'=>'staff','id'=>3],
            'citizen1@email.com'     => ['type'=>'user','pass'=>'Citizen123!','name'=>'Anna Rodriguez','role'=>'citizen','id'=>1,'vs'=>'verified'],
            'citizen2@email.com'     => ['type'=>'user','pass'=>'Citizen123!','name'=>'Carlos Martinez','role'=>'citizen','id'=>2,'vs'=>'verified'],
            'pendinguser@email.com'  => ['type'=>'user','pass'=>'Pending123!','name'=>'Sofia Ramos','role'=>'citizen','id'=>3,'vs'=>'pending'],
            'guest1@email.com'       => ['type'=>'user','pass'=>'Guest123!','name'=>'Guest User','role'=>'guest','id'=>4,'vs'=>'verified'],
        ];
        if (isset($demo[$email]) && $password === $demo[$email]['pass']) {
            $d = $demo[$email];
            session_regenerate_id(true);
            if ($d['type']==='admin') {
                $_SESSION['admin_logged_in']=$_SESSION['last_activity']=time();
                $_SESSION['admin_id']=$d['id']; $_SESSION['admin_email']=$email;
                $_SESSION['admin_name']=$d['name']; $_SESSION['admin_role']=$d['role'];
                $_SESSION['last_activity']=time();
                header("Location: ../admin-dashboard.php"); exit();
            } else {
                $_SESSION['user_logged_in']=true; $_SESSION['user_id']=$d['id'];
                $_SESSION['user_email']=$email; $_SESSION['user_name']=$d['name'];
                $_SESSION['user_role']=$d['role']; $_SESSION['verification_status']=$d['vs'];
                $_SESSION['last_activity']=time();
                header("Location: ../".($d['role']==='guest'?'dashboard2.php':'dashboard.php')); exit();
            }
        }
    }

    header("Location: ../login.php?error=Invalid+email+or+password.&email=".urlencode($email));
    exit();
} catch(PDOException $e) {
    error_log("Login error: ".$e->getMessage());
    header("Location: ../login.php?error=System+error.+Please+try+again.");
    exit();
}
