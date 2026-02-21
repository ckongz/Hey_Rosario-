<?php
$page_title = 'Government Services - Hey Rosario!';
require_once 'includes/db-connection.php';
require_once 'includes/session-check.php';
include 'includes/header.php';

$services = fetchAll($pdo, "SELECT * FROM services WHERE is_active = 1 ORDER BY service_name");
?>

<div class="page-content">
    <!-- Hero Section - Matching your CSS structure -->
    <section class="hero page-hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Government Services</h1>
                <p class="tagline">Digital access to essential barangay services</p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header">
                <span class="section-label">WHAT WE OFFER</span>
                <h2 class="section-title">Available Services</h2>
                <p class="section-subtitle">
                    Access various barangay services online. Apply for documents, 
                    request assistance, and more from the comfort of your home.
                </p>
            </div>

            <?php if (empty($services)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    No services are currently available. Please check back later.
                </div>
            <?php else: ?>
                <!-- Services Grid - Using your existing services-grid class -->
                <div class="services-grid">
                    <?php foreach ($services as $service): ?>
                        <div class="service-card">
                            <!-- Service Icon (based on service type) -->
                            <div class="service-icon">
                                <?php
                                // Assign icons based on service name or type
                                $icon = 'fa-file-alt'; // default
                                $service_name_lower = strtolower($service['service_name']);
                                
                                if (strpos($service_name_lower, 'clearance') !== false) {
                                    $icon = 'fa-id-card';
                                } elseif (strpos($service_name_lower, 'certificate') !== false) {
                                    $icon = 'fa-certificate';
                                } elseif (strpos($service_name_lower, 'indigency') !== false) {
                                    $icon = 'fa-hand-holding-heart';
                                } elseif (strpos($service_name_lower, 'business') !== false) {
                                    $icon = 'fa-store';
                                } elseif (strpos($service_name_lower, 'residency') !== false) {
                                    $icon = 'fa-home';
                                } elseif (strpos($service_name_lower, 'assistance') !== false) {
                                    $icon = 'fa-hands-helping';
                                }
                                ?>
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>

                            <!-- Service Content -->
                            <h3 class="service-title"><?php echo htmlspecialchars($service['service_name']); ?></h3>
                            
                            <p class="service-desc">
                                <?php echo htmlspecialchars($service['description']); ?>
                            </p>

                            <!-- Service Details -->
                            <div class="service-details">
                                <div class="service-detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span>
                                        <strong>Processing Time:</strong> 
                                        <?php echo htmlspecialchars($service['processing_time']); ?>
                                    </span>
                                </div>
                                
                                <div class="service-detail-item">
                                    <i class="fas fa-tag"></i>
                                    <span>
                                        <strong>Fee:</strong> 
                                        <?php if ($service['is_free']): ?>
                                            <span class="free-badge">Free</span>
                                        <?php else: ?>
                                            <span class="fee-amount">₱<?php echo number_format($service['fee'], 2); ?></span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Requirements (if any) -->
                            <?php if (!empty($service['requirements'])): ?>
                                <div class="service-requirements">
                                    <strong>Requirements:</strong>
                                    <p><?php echo nl2br(htmlspecialchars($service['requirements'])); ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- Action Button -->
                            <div class="service-action">
                                <?php if (isLoggedIn()): ?>
                                    <a href="apply-service.php?id=<?php echo $service['service_id']; ?>" 
                                       class="btn-primary service-apply-btn">
                                        <i class="fas fa-paper-plane"></i>
                                        Apply Online
                                    </a>
                                <?php else: ?>
                                    <a href="login.php?redirect=services" 
                                       class="btn-outline service-login-btn">
                                        <i class="fas fa-sign-in-alt"></i>
                                        Login to Apply
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>