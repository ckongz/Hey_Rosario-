<?php
$page_title = 'Contact Us - Hey Rosario!';
include 'includes/header.php';

// Check for session messages
$success_message = isset($_SESSION['contact_success']) ? $_SESSION['contact_success'] : '';
$error_message = isset($_SESSION['contact_error']) ? $_SESSION['contact_error'] : '';

// Clear session messages
unset($_SESSION['contact_success']);
unset($_SESSION['contact_error']);
?>

<div class="page-content">
    <!-- Hero Section -->
    <section class="hero page-hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Contact Us</h1>
                <p class="tagline">We're here to listen and assist you</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header">
                <span class="section-label">GET IN TOUCH</span>
                <h2 class="section-title">How Can We Help You?</h2>
                <p class="section-subtitle">
                    Have questions, concerns, or feedback? Reach out to us and we'll respond as soon as possible.
                </p>
            </div>

            <!-- Display Messages -->
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <!-- Contact Grid -->
            <div class="contact-grid">
                <!-- Contact Information -->
                <div class="contact-info-grid">
                    <!-- Address Card -->
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Visit Us</h3>
                        <p>
                            Barangay Sto. Rosario Hall<br>
                            Angeles City, Pampanga 2009
                        </p>
                    </div>

                    <!-- Phone Card -->
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h3>Call Us</h3>
                        <p>
                            <strong>Landline:</strong> (045) 123-4567<br>
                            <strong>Mobile:</strong> 0917 123 4567
                        </p>
                    </div>

                    <!-- Email Card -->
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email Us</h3>
                        <p>
                            info@heyrosario.gov.ph<br>
                            support@heyrosario.gov.ph
                        </p>
                    </div>

                    <!-- Hours Card -->
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Office Hours</h3>
                        <p>
                            <strong>Mon - Fri:</strong> 8:00 AM - 5:00 PM<br>
                            <strong>Saturday:</strong> 8:00 AM - 12:00 PM<br>
                            <strong>Sunday:</strong> Closed
                        </p>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="contact-form-card">
                    <h3>Send Us a Message</h3>
                    <p>Fill out the form below and we'll get back to you within 24-48 hours.</p>

                    <form action="processes/contact-form-process.php" method="POST" id="contactForm">
                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name">Full Name <span class="required">*</span></label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   required 
                                   placeholder="Enter your full name">
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   required 
                                   placeholder="your.email@example.com">
                        </div>

                        <!-- Subject Field -->
                        <div class="form-group">
                            <label for="subject">Subject <span class="required">*</span></label>
                            <input type="text" 
                                   id="subject" 
                                   name="subject" 
                                   required 
                                   placeholder="What is this regarding?">
                        </div>

                        <!-- Message Field -->
                        <div class="form-group">
                            <label for="message">Message <span class="required">*</span></label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="5" 
                                      required 
                                      placeholder="Type your message here..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Map Section -->
            <div class="map-section">
                <h3>Find Us Here</h3>
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3850.331215215468!2d120.589123314841!3d15.145739889464!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396f28f0b0b0b0b%3A0x123456789abcdef!2sAngeles%2C%20Pampanga!5e0!3m2!1sen!2sph!4v1234567890123!5m2!1sen!2sph"
                        width="100%" 
                        height="400" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>

            <!-- Emergency Contacts -->
            <div class="emergency-contacts">
                <h3>Emergency Contacts</h3>
                <div class="emergency-grid">
                    <div><strong>Barangay Tanod:</strong> 0917 123 4567</div>
                    <div><strong>Police:</strong> 117</div>
                    <div><strong>Fire:</strong> 160</div>
                    <div><strong>Ambulance:</strong> 911</div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Simple Form Validation -->
<script>
document.getElementById('contactForm')?.addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const subject = document.getElementById('subject').value.trim();
    const message = document.getElementById('message').value.trim();
    
    if (!name || !email || !subject || !message) {
        e.preventDefault();
        alert('Please fill in all required fields.');
        return;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        return;
    }
});
</script>

<?php include 'includes/footer.php'; ?>