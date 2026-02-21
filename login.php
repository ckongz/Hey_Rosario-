<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin-dashboard.php');
    exit();
} elseif (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}

$page_title = 'Login - Barangay Rosario';
include 'includes/header.php';
?>

<div class="page-content">
    <!-- Hero Section - Matching your design system -->
    <section class="hero page-hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Welcome Back!</h1>
                <p class="tagline">Sign in to access your account</p>
            </div>
        </div>
    </section>

    <!-- Login Section -->
    <section class="section">
        <div class="container">
            <div class="form-container">
                <!-- Header -->
                <div class="section-header" style="margin-bottom: 30px;">
                    <span class="section-label">ACCESS YOUR ACCOUNT</span>
                    <h2 class="form-title">Login to Hey Rosario!</h2>
                    <p class="section-subtitle">
                        Sign in to access barangay services, track requests, and stay updated.
                    </p>
                </div>
                
                <!-- Error Messages -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Success Messages -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars(urldecode($_GET['success'])); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Login Form -->
                <form method="POST" action="processes/login-process.php" class="login-form">
                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email Address <span class="required">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-input" 
                               required 
                               placeholder="your@email.com"
                               value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                    </div>
                    
                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password <span class="required">*</span>
                        </label>
                        <div class="password-field">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-input" 
                                   required 
                                   placeholder="Enter your password">
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Remember Me & Forgot Password -->
                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" value="1">
                            <span>Remember me</span>
                        </label>
                        <a href="forgot-password.php" class="forgot-link">Forgot Password?</a>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit" id="loginBtn">
                        <i class="fas fa-sign-in-alt"></i>
                        Login to Account
                    </button>
                    
                    <!-- Register Link -->
                    <div class="form-footer">
                        <p>Don't have an account?</p>
                        <a href="register.php" class="register-link">
                            <i class="fas fa-user-plus"></i>
                            Create Account
                        </a>
                    </div>
                </form>
                
                <!-- Test Accounts Info -->
                <div class="test-accounts">
                    <div class="test-header" onclick="toggleTestAccounts()">
                        <i class="fas fa-chevron-down" id="testChevron"></i>
                        <span>Test Accounts (Click to expand)</span>
                    </div>
                    <div class="test-content" id="testContent">
                        <div class="test-account">
                            <span class="badge-admin">Admin</span>
                            <code>admin@heyrosario.com</code>
                            <code>Admin123!</code>
                        </div>
                        <div class="test-account">
                            <span class="badge-citizen">Citizen</span>
                            <code>citizen1@email.com</code>
                            <code>Citizen123!</code>
                        </div>
                        <div class="test-account">
                            <span class="badge-staff">Staff</span>
                            <code>staff@heyrosario.com</code>
                            <code>Staff123!</code>
                        </div>
                        <p class="test-note">
                            <i class="fas fa-info-circle"></i>
                            These are for testing only. Use your registered account for actual services.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript for Login Page -->
<script>
// Toggle Password Visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'fas fa-eye';
    }
}

// Toggle Test Accounts
function toggleTestAccounts() {
    const testContent = document.getElementById('testContent');
    const testChevron = document.getElementById('testChevron');
    
    if (testContent.style.display === 'none' || !testContent.style.display) {
        testContent.style.display = 'block';
        testChevron.style.transform = 'rotate(180deg)';
    } else {
        testContent.style.display = 'none';
        testChevron.style.transform = 'rotate(0deg)';
    }
}

// Form Submission Loading State
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.login-form');
    const loginBtn = document.getElementById('loginBtn');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            // Basic validation
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields.');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return;
            }
            
            // Show loading state
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
        });
    }
});
</script>

<!-- Additional CSS for Login Page -->
<style>
/* Password Field */
.password-field {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: var(--medium-red);
    font-size: 18px;
    padding: 5px;
}

.password-toggle:hover {
    color: var(--deepest-red);
}

/* Form Options */
.form-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 20px 0;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 14px;
    color: var(--medium-red);
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--deepest-red);
}

.forgot-link {
    color: var(--deepest-red);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.forgot-link:hover {
    color: var(--medium-red);
    text-decoration: underline;
}

/* Form Footer */
.form-footer {
    text-align: center;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 2px solid var(--cream);
}

.form-footer p {
    color: var(--medium-red);
    font-size: 14px;
    margin-bottom: 10px;
}

.register-link {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--button-gradient);
    color: white;
    padding: 12px 30px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
}

.register-link:hover {
    background: var(--hover-button-gradient);
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(167, 66, 56, 0.3);
}

/* Test Accounts */
.test-accounts {
    margin-top: 30px;
    background: var(--bg-cream);
    border-radius: 15px;
    overflow: hidden;
    border: 2px solid var(--cream);
}

.test-header {
    padding: 15px 20px;
    background: var(--pastel-peach);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--deepest-red);
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
}

.test-header:hover {
    background: var(--cream);
}

.test-header i {
    transition: transform 0.3s ease;
}

.test-content {
    padding: 20px;
    display: none;
}

.test-account {
    background: white;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 10px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    border: 2px solid var(--cream);
}

.test-account:last-child {
    margin-bottom: 0;
}

.badge-admin {
    display: inline-block;
    padding: 4px 12px;
    background: linear-gradient(135deg, #69000E, #A74238);
    color: white;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: fit-content;
}

.badge-citizen {
    display: inline-block;
    padding: 4px 12px;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: fit-content;
}

.badge-staff {
    display: inline-block;
    padding: 4px 12px;
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    color: white;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: fit-content;
}

.test-account code {
    background: var(--bg-cream);
    padding: 8px;
    border-radius: 8px;
    font-size: 13px;
    color: var(--deepest-red);
    font-family: 'DM Mono', monospace;
}

.test-note {
    margin-top: 15px;
    padding: 12px;
    background: var(--pastel-lavender);
    border-radius: 10px;
    font-size: 12px;
    color: var(--medium-red);
    display: flex;
    align-items: center;
    gap: 8px;
}

.test-note i {
    color: var(--deepest-red);
    font-size: 14px;
}

/* Responsive */
@media (max-width: 767px) {
    .form-options {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .test-account code {
        font-size: 12px;
        word-break: break-all;
    }
}

@media (max-width: 480px) {
    .form-container {
        padding: 30px 20px;
    }
    
    .form-title {
        font-size: 28px;
    }
    
    .register-link {
        width: 100%;
        justify-content: center;
    }
}
</style>

<?php include 'includes/footer.php'; ?>