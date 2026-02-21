<?php
$page_title = 'Home - Barangay Rosario';
include 'includes/header.php';
?>

<div class="page-content">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Welcome to Barangay Rosario</h1>
                <p class="tagline">"Keng Sto. Rosario, ating mantabe kekayu"</p>
                <p>Your trusted partner in community governance. Access government services, report issues, stay updated with announcements, and connect with your local government—all in one place.</p>
                <div class="hero-buttons">
                    <a href="services.php" class="btn-primary">
                        <i class="fas fa-building"></i> Explore Services
                    </a>
                    <a href="report.php" class="btn-outline">
                        <i class="fas fa-file-alt"></i> Report an Issue
                    </a>
                </div>
            </div>
            <div class="hero-cards">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-num">15,000+</div>
                    <div class="stat-label">Registered Citizens</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-num">2,500+</div>
                    <div class="stat-label">Services Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="stat-num">1,200+</div>
                    <div class="stat-label">Reports Resolved</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-bullhorn"></i></div>
                    <div class="stat-num">500+</div>
                    <div class="stat-label">Community Updates</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Services Section -->
    <section class="section section-alt">
        <div class="section-header">
            <div class="section-label">OUR SERVICES</div>
            <h2 class="section-title">Government Services</h2>
            <p class="section-subtitle">Access essential barangay services online. Fast, efficient, and transparent processing for all residents.</p>
        </div>
        
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-id-card"></i></div>
                <h3 class="service-title">Barangay Clearance</h3>
                <p class="service-desc">Get your barangay clearance for various purposes including employment, business permits, and legal requirements.</p>
                <a href="services.php" class="service-link">Apply Now <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-file-invoice"></i></div>
                <h3 class="service-title">Indigency Certificate</h3>
                <p class="service-desc">Request a certificate of indigency for financial assistance, scholarships, and medical purposes.</p>
                <a href="services.php" class="service-link">Apply Now <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-home"></i></div>
                <h3 class="service-title">Residency Certificate</h3>
                <p class="service-desc">Obtain proof of residency for government transactions, applications, and other official purposes.</p>
                <a href="services.php" class="service-link">Apply Now <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-store"></i></div>
                <h3 class="service-title">Business Permit</h3>
                <p class="service-desc">Apply for barangay business permits and clearances required for starting or renewing your business.</p>
                <a href="services.php" class="service-link">Apply Now <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <h3 class="service-title">Report an Issue</h3>
                <p class="service-desc">Report community issues, concerns, or complaints directly to the barangay office for immediate action.</p>
                <a href="report.php" class="service-link">Submit Report <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-phone-alt"></i></div>
                <h3 class="service-title">Emergency Hotlines</h3>
                <p class="service-desc">Access important emergency contact numbers for police, fire, medical, and barangay assistance.</p>
                <a href="emergency.php" class="service-link">View Hotlines <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>
    
    <!-- Quick Links Section -->
    <section class="section">
        <div class="section-header">
            <div class="section-label">STAY CONNECTED</div>
            <h2 class="section-title">Quick Access</h2>
            <p class="section-subtitle">Everything you need to stay informed and engaged with your community.</p>
        </div>
        
        <div class="services-grid" style="max-width: 900px;">
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-bullhorn"></i></div>
                <h3 class="service-title">Announcements</h3>
                <p class="service-desc">Stay updated with the latest news, events, and announcements from the barangay.</p>
                <a href="updates.php" class="service-link">View Updates <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-map-marked-alt"></i></div>
                <h3 class="service-title">Tourism Guide</h3>
                <p class="service-desc">Discover local attractions, events, culture, and everything Barangay Rosario has to offer.</p>
                <a href="tourism.php" class="service-link">Explore Tourism <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-chart-line"></i></div>
                <h3 class="service-title">Transparency Portal</h3>
                <p class="service-desc">View budget allocations, projects, ordinances, and executive orders for full transparency.</p>
                <a href="transparency.php" class="service-link">View Reports <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
