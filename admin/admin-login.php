<?php
$page_title = 'Admin Login - Hey Rosario!';
require_once '../includes/db-connection.php';
require_once '../includes/session-check.php';

if (isAdminLoggedIn()) {
    header("Location: ../admin-dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container" style="max-width: 500px; margin: 5rem auto;">
        <div class="card">
            <div class="text-center mb-3">
                <i class="fas fa-shield-alt" style="font-size: 4rem; color: var(--primary-red);"></i>
                <h1 style="color: var(--primary-red); margin-top: 1rem;">Admin Portal</h1>
                <p>Secure access for authorized personnel only</p>
            </div>
            
            <form action="../processes/login-process.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label class="form-label">Admin Email</label>
                    <input type="email" name="email" class="form-input" required autofocus>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-lock"></i> Secure Login
                </button>
            </form>
            
            <div class="mt-2 text-center">
                <p><a href="../login.php" style="color: var(--accent-red);">← Back to Citizen Login</a></p>
            </div>
        </div>
        
        <div class="mt-2 text-center" style="color: #666; font-size: 0.9rem;">
            <p><i class="fas fa-info-circle"></i> This portal is for administrators, captains, and staff members only.</p>
        </div>
    </div>
</body>
</html>
