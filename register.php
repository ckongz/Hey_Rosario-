<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
$page_title = 'Register - Hey Rosario!';
include 'includes/header.php';

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Redirect if already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
} elseif (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin-dashboard.php');
    exit();
}

// Get any form data from session (for when validation fails)
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];

// Clear session data
unset($_SESSION['form_data']);
unset($_SESSION['errors']);
?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .page-content {
        min-height: calc(100vh - 200px);
    }

    .form-container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .form-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .form-header span {
        font-family: 'DM Mono', monospace;
        font-size: 13px;
        color: #A74238;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .form-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        color: #69000E;
        margin: 10px 0;
    }

    .form-header p {
        color: #A74238;
        line-height: 1.7;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #69000E;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #E4ACAB;
        border-radius: 12px;
        font-family: 'DM Sans', sans-serif;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        border-color: #69000E;
        box-shadow: 0 0 0 3px rgba(105, 0, 14, 0.1);
    }

    .form-group input.error {
        border-color: #ff0000;
    }

    .form-group small {
        color: #A74238;
        font-size: 12px;
        display: block;
        margin-top: 5px;
    }

    .section-title {
        color: #69000E;
        margin: 25px 0 15px;
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        border-bottom: 2px solid #E4ACAB;
        padding-bottom: 10px;
    }

    .terms-box {
        margin: 25px 0;
        padding: 15px;
        background: #E6E6FA;
        border-radius: 12px;
    }

    .terms-box label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        color: #260000;
        font-size: 14px;
    }

    .terms-box input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #69000E;
    }

    .terms-box a {
        color: #69000E;
        font-weight: 600;
        text-decoration: none;
    }

    .terms-box a:hover {
        text-decoration: underline;
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(90deg, #69000E, #A74238);
        color: white;
        padding: 16px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(105, 0, 14, 0.3);
    }

    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .login-link {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 2px solid #E4ACAB;
    }

    .login-link p {
        color: #A74238;
        margin-bottom: 10px;
    }

    .login-link a {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 30px;
        border: 2px solid #69000E;
        border-radius: 50px;
        color: #69000E;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .login-link a:hover {
        background: #69000E;
        color: white;
    }

    .error-message {
        background: linear-gradient(135deg, #FFB6C1, #FFD1D1);
        color: #721c24;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 2px solid #FFB6C1;
    }

    .success-message {
        background: linear-gradient(135deg, #E0F2E0, #C1E1C1);
        color: #155724;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 2px solid #C1E1C1;
    }

    .error-message ul, .success-message ul {
        margin-top: 10px;
        margin-left: 20px;
    }

    .password-strength {
        margin-top: 5px;
        height: 5px;
        background: #eee;
        border-radius: 3px;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        width: 0;
        transition: width 0.3s ease;
    }

    .password-strength-bar.weak {
        background: #ff4444;
        width: 33.33%;
    }

    .password-strength-bar.medium {
        background: #ffbb33;
        width: 66.66%;
    }

    .password-strength-bar.strong {
        background: #00C851;
        width: 100%;
    }

    .hero.page-hero {
        background: linear-gradient(135deg, #69000E 0%, #A74238 100%);
        padding: 60px 0;
        margin-bottom: 40px;
    }

    .hero-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .hero-text h1 {
        font-family: 'Playfair Display', serif;
        font-size: 48px;
        color: white;
        margin-bottom: 10px;
    }

    .hero-text .tagline {
        font-size: 18px;
        color: white;
        opacity: 0.95;
    }

    .section {
        padding: 40px 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
</style>

<div class="page-content">
    <!-- Hero Section -->
    <section class="hero page-hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Join Our Community</h1>
                <p class="tagline">Create your account to access barangay services</p>
            </div>
        </div>
    </section>

    <!-- Registration Section -->
    <section class="section">
        <div class="container">
            <div class="form-container">
                <!-- Header -->
                <div class="form-header">
                    <span>GET STARTED</span>
                    <h2>Create Your Account</h2>
                    <p>Join the Hey Rosario! community and access digital services, track requests, and stay updated with barangay news.</p>
                </div>

                <!-- Error Messages -->
                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Success Message -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars(urldecode($_GET['success'])); ?>
                    </div>
                <?php endif; ?>

                <!-- Registration Form -->
                <form action="processes/register-process.php" method="POST" id="registrationForm">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                    <!-- Personal Information -->
                    <h3 class="section-title">Personal Information</h3>

                    <!-- First Name -->
                    <div class="form-group">
                        <label>First Name *</label>
                        <input type="text" 
                               name="first_name" 
                               id="first_name"
                               required 
                               value="<?php echo isset($form_data['first_name']) ? htmlspecialchars($form_data['first_name']) : ''; ?>"
                               placeholder="Enter your first name">
                    </div>

                    <!-- Last Name -->
                    <div class="form-group">
                        <label>Last Name *</label>
                        <input type="text" 
                               name="last_name" 
                               id="last_name"
                               required 
                               value="<?php echo isset($form_data['last_name']) ? htmlspecialchars($form_data['last_name']) : ''; ?>"
                               placeholder="Enter your last name">
                    </div>

                    <!-- Middle Name -->
                    <div class="form-group">
                        <label>Middle Name</label>
                        <input type="text" 
                               name="middle_name" 
                               id="middle_name"
                               value="<?php echo isset($form_data['middle_name']) ? htmlspecialchars($form_data['middle_name']) : ''; ?>"
                               placeholder="Enter your middle name (optional)">
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               required 
                               value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>"
                               placeholder="Enter your email address">
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" 
                               id="password"
                               name="password" 
                               required 
                               minlength="8"
                               placeholder="Enter your password">
                        <small>Minimum 8 characters</small>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="passwordStrength"></div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label>Confirm Password *</label>
                        <input type="password" 
                               id="confirm_password"
                               name="confirm_password" 
                               required 
                               minlength="8"
                               placeholder="Confirm your password">
                        <small id="passwordMatchMsg"></small>
                    </div>

                    <!-- Contact Number -->
                    <div class="form-group">
                        <label>Contact Number *</label>
                        <input type="tel" 
                               name="contact_number" 
                               id="contact_number"
                               required 
                               pattern="[0-9]{11}"
                               title="Please enter 11 digits"
                               value="<?php echo isset($form_data['contact_number']) ? htmlspecialchars($form_data['contact_number']) : ''; ?>"
                               placeholder="09123456789">
                        <small>11-digit mobile number (e.g., 09123456789)</small>
                    </div>

                    <!-- Address Information -->
                    <h3 class="section-title">Address Information</h3>

                    <!-- House Number -->
                    <div class="form-group">
                        <label>House/Building Number *</label>
                        <input type="text" 
                               name="house_number" 
                               id="house_number"
                               required 
                               value="<?php echo isset($form_data['house_number']) ? htmlspecialchars($form_data['house_number']) : ''; ?>"
                               placeholder="Enter house or building number">
                    </div>

                    <!-- Street -->
                    <div class="form-group">
                        <label>Street *</label>
                        <input type="text" 
                               name="street" 
                               id="street"
                               required 
                               value="<?php echo isset($form_data['street']) ? htmlspecialchars($form_data['street']) : ''; ?>"
                               placeholder="Enter street name">
                    </div>

                    <!-- Purok -->
                    <div class="form-group">
                        <label>Purok *</label>
                        <input type="text" 
                               name="purok" 
                               id="purok"
                               required 
                               value="<?php echo isset($form_data['purok']) ? htmlspecialchars($form_data['purok']) : ''; ?>"
                               placeholder="Enter purok number or name">
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="terms-box">
                        <label>
                            <input type="checkbox" name="terms" id="terms" required>
                            <span>
                                I agree to the <a href="terms.php" target="_blank">Terms and Conditions</a> 
                                and <a href="privacy-policy.php" target="_blank">Privacy Policy</a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="registerBtn" class="btn-submit">
                        <i class="fas fa-user-plus"></i>
                        Create Account
                    </button>
                </form>

                <!-- Login Link -->
                <div class="login-link">
                    <p>Already have an account?</p>
                    <a href="login.php">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In Instead
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordMatchMsg = document.getElementById('passwordMatchMsg');
    const submitBtn = document.getElementById('registerBtn');
    const termsCheckbox = document.getElementById('terms');

    // Password strength checker
    password.addEventListener('input', function() {
        const val = this.value;
        let strength = 0;
        
        if (val.length >= 8) strength++;
        if (val.match(/[a-z]/)) strength++;
        if (val.match(/[A-Z]/)) strength++;
        if (val.match(/[0-9]/)) strength++;
        if (val.match(/[^a-zA-Z0-9]/)) strength++;
        
        passwordStrength.className = 'password-strength-bar';
        
        if (val.length === 0) {
            passwordStrength.style.width = '0';
        } else if (strength <= 2) {
            passwordStrength.classList.add('weak');
        } else if (strength <= 4) {
            passwordStrength.classList.add('medium');
        } else {
            passwordStrength.classList.add('strong');
        }
        
        checkPasswordMatch();
    });

    // Password match checker
    function checkPasswordMatch() {
        if (password.value && confirmPassword.value) {
            if (password.value === confirmPassword.value) {
                confirmPassword.style.borderColor = '#00C851';
                passwordMatchMsg.innerHTML = '✓ Passwords match';
                passwordMatchMsg.style.color = '#00C851';
            } else {
                confirmPassword.style.borderColor = '#ff4444';
                passwordMatchMsg.innerHTML = '✗ Passwords do not match';
                passwordMatchMsg.style.color = '#ff4444';
            }
        } else {
            confirmPassword.style.borderColor = '#E4ACAB';
            passwordMatchMsg.innerHTML = '';
        }
    }

    confirmPassword.addEventListener('input', checkPasswordMatch);

    // Form submission
    form.addEventListener('submit', function(e) {
        // Check if passwords match
        if (password.value !== confirmPassword.value) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }
        
        // Check password length
        if (password.value.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long!');
            return false;
        }
        
        // Check contact number format
        const contactNumber = document.getElementById('contact_number');
        const phoneRegex = /^[0-9]{11}$/;
        if (!phoneRegex.test(contactNumber.value)) {
            e.preventDefault();
            alert('Please enter a valid 11-digit contact number!');
            return false;
        }
        
        // Check terms agreement
        if (!termsCheckbox.checked) {
            e.preventDefault();
            alert('Please agree to the Terms and Conditions');
            return false;
        }
        
        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
        
        return true;
    });

    // Phone number validation (only numbers)
    document.getElementById('contact_number').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>

<?php include 'includes/footer.php'; ?>R