<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Floating Navigation Bar -->
<nav class="navbar">
    <a href="<?php echo BASE_URL; ?>/index.php" class="nav-brand">
        <div class="nav-logo">BR</div>
        <div class="nav-brand-text">
            <h1>Barangay Rosario</h1>
            <p>Angeles City, Pampanga</p>
        </div>
    </a>
    
    <button class="hamburger" id="hamburger" aria-label="Toggle menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <a href="<?php echo BASE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
    <a href="<?php echo BASE_URL; ?>/about.php"><i class="fas fa-info-circle"></i> About Us</a>
    <a href="<?php echo BASE_URL; ?>/services.php"><i class="fas fa-building"></i> Government Services</a>
    <a href="<?php echo BASE_URL; ?>/report.php"><i class="fas fa-file-alt"></i> Citizen Reporting</a>
    <a href="<?php echo BASE_URL; ?>/updates.php"><i class="fas fa-bullhorn"></i> Updates & Announcements</a>
    <a href="<?php echo BASE_URL; ?>/emergency.php"><i class="fas fa-exclamation-triangle"></i> Emergency Hotlines</a>
    <a href="<?php echo BASE_URL; ?>/tourism.php"><i class="fas fa-map-marked-alt"></i> Tourism & Local Guide</a>
    <a href="<?php echo BASE_URL; ?>/transparency.php"><i class="fas fa-chart-line"></i> Transparency Portal</a>
    <a href="<?php echo BASE_URL; ?>/contact.php"><i class="fas fa-envelope"></i> Contact Us</a>
    
    <div class="menu-divider"></div>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="<?php echo BASE_URL; ?>/admin-dashboard.php"><i class="fas fa-cog"></i> Admin Dashboard</a>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>/dashboard.php"><i class="fas fa-user"></i> My Dashboard</a>
        <?php endif; ?>
        <a href="<?php echo BASE_URL; ?>/processes/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    <?php else: ?>
        <a href="<?php echo BASE_URL; ?>/login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
        <a href="<?php echo BASE_URL; ?>/register.php"><i class="fas fa-user-plus"></i> Register</a>
    <?php endif; ?>
</div>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
