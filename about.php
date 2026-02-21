<?php
$page_title = 'About Us - Hey Rosario!';
include 'includes/header.php';
?>

<div class="page-content">
    <!-- Hero Section - Matching your CSS structure -->
    <section class="hero page-hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>About Hey Rosario!</h1>
                <p class="tagline">Your trusted digital platform for Barangay Sto. Rosario</p>
            </div>
        </div>
    </section>

    <!-- About Content Section -->
    <section class="section">
        <div class="container">
            <!-- Main About Card -->
            <div class="about-card">
                <div class="about-header">
                    <div class="section-label">GET TO KNOW US</div>
                    <h2 class="about-title">Who We Are</h2>
                </div>
                
                <div class="about-content">
                    <p class="about-text">
                        Hey Rosario! is the official digital platform of Barangay Sto. Rosario, Angeles City, 
                        designed to bridge the gap between local government and the people we serve.
                    </p>
                </div>
            </div>

            <!-- Mission Card -->
            <div class="about-card mission-card">
                <div class="about-header">
                    <div class="section-label">OUR PURPOSE</div>
                    <h2 class="about-title">Our Mission</h2>
                </div>
                
                <div class="about-content">
                    <p class="about-text">
                        To empower every resident of Barangay Sto. Rosario through accessible, 
                        transparent, and responsive digital governance.
                    </p>
                </div>
            </div>

            <!-- Team Card -->
            <div class="about-card team-card">
                <div class="about-header">
                    <div class="section-label">THE PEOPLE BEHIND IT</div>
                    <h2 class="about-title">Development Team</h2>
                </div>
                
                <div class="team-grid">
                    <!-- Casey -->
                    <div class="team-member">
                        <div class="member-avatar">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3 class="member-name">Casey</h3>
                        <p class="member-role">Project Lead & Frontend Developer</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>

                    <!-- Abygale -->
                    <div class="team-member">
                        <div class="member-avatar">
                            <i class="fas fa-paint-brush"></i>
                        </div>
                        <h3 class="member-name">Abygale</h3>
                        <p class="member-role">UI/UX Designer & Design Lead</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-behance"></i></a>
                            <a href="#"><i class="fab fa-dribbble"></i></a>
                        </div>
                    </div>

                    <!-- Ayenne -->
                    <div class="team-member">
                        <div class="member-avatar">
                            <i class="fas fa-database"></i>
                        </div>
                        <h3 class="member-name">Ayenne</h3>
                        <p class="member-role">Database Administrator & Documentation Lead</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fas fa-file-alt"></i></a>
                        </div>
                    </div>

                    <!-- Prince -->
                    <div class="team-member">
                        <div class="member-avatar">
                            <i class="fab fa-php"></i>
                        </div>
                        <h3 class="member-name">Prince</h3>
                        <p class="member-role">PHP Backend Developer</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fas fa-code"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Values Section -->
            <div class="values-section">
                <h3 class="values-title">Our Core Values</h3>
                <div class="values-grid">
                    <div class="value-item">
                        <i class="fas fa-hand-holding-heart"></i>
                        <h4>Transparency</h4>
                        <p>Open and honest communication with our community</p>
                    </div>
                    <div class="value-item">
                        <i class="fas fa-bolt"></i>
                        <h4>Responsiveness</h4>
                        <p>Quick and efficient service to all residents</p>
                    </div>
                    <div class="value-item">
                        <i class="fas fa-users"></i>
                        <h4>Inclusivity</h4>
                        <p>Serving every resident of Barangay Sto. Rosario</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>