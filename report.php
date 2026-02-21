<?php
$page_title = 'Citizen Reporting - Hey Rosario!';
require_once 'includes/db-connection.php';
require_once 'includes/session-check.php';
include 'includes/header.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php?redirect=reporting.php');
    exit();
}
?>

<div class="page-content">
    <!-- Hero Section - Matching your CSS structure -->
    <section class="hero page-hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Report an Issue</h1>
                <p class="tagline">Your voice, our action - Help us improve our community</p>
            </div>
        </div>
    </section>

    <!-- Reporting Form Section -->
    <section class="section">
        <div class="container" style="max-width: 800px;">
            <!-- Section Header -->
            <div class="section-header">
                <span class="section-label">CITIZEN FEEDBACK</span>
                <h2 class="section-title">Submit a Report</h2>
                <p class="section-subtitle">
                    Report issues in your barangay. All reports are reviewed by our team 
                    and will be addressed promptly.
                </p>
            </div>

            <!-- Report Form Card - Using your form-container class -->
            <div class="form-container">
                <!-- Display any session messages -->
                <?php if (isset($_SESSION['report_success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php 
                        echo $_SESSION['report_success'];
                        unset($_SESSION['report_success']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['report_error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php 
                        echo $_SESSION['report_error'];
                        unset($_SESSION['report_error']);
                        ?>
                    </div>
                <?php endif; ?>

                <form action="processes/submit-report.php" method="POST" enctype="multipart/form-data" class="report-form">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <!-- Report Type -->
                    <div class="form-group">
                        <label for="report_type" class="form-label">
                            <i class="fas fa-tag"></i>
                            Report Type <span class="required">*</span>
                        </label>
                        <select name="report_type" id="report_type" class="form-select" required>
                            <option value="">Select Type of Report</option>
                            <option value="Infrastructure">🏗️ Infrastructure Issue</option>
                            <option value="Sanitation">🗑️ Sanitation & Environment</option>
                            <option value="Safety">🚨 Safety & Security</option>
                            <option value="Noise">🔊 Noise Complaint</option>
                            <option value="Animal">🐕 Animal Concern</option>
                            <option value="Other">📌 Other Concern</option>
                        </select>
                    </div>

                    <!-- Title -->
                    <div class="form-group">
                        <label for="title" class="form-label">
                            <i class="fas fa-heading"></i>
                            Title <span class="required">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="form-input" 
                               required 
                               placeholder="Brief summary of the issue"
                               maxlength="100">
                        <small class="form-text">Keep it concise and descriptive (max 100 characters)</small>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left"></i>
                            Description <span class="required">*</span>
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  class="form-textarea" 
                                  rows="5" 
                                  required
                                  placeholder="Provide detailed information about the issue..."></textarea>
                        <small class="form-text">Include as much detail as possible to help us address the issue</small>
                    </div>

                    <!-- Location -->
                    <div class="form-group">
                        <label for="location" class="form-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Location <span class="required">*</span>
                        </label>
                        <input type="text" 
                               name="location" 
                               id="location" 
                               class="form-input" 
                               required 
                               placeholder="e.g., Purok 3, near Rosario Elementary School">
                        <small class="form-text">Provide specific location details</small>
                    </div>

                    <!-- Photo Upload -->
                    <div class="form-group">
                        <label for="photo" class="form-label">
                            <i class="fas fa-camera"></i>
                            Upload Photo <span class="optional">(optional)</span>
                        </label>
                        <div class="file-upload-wrapper">
                            <input type="file" 
                                   name="photo" 
                                   id="photo" 
                                   class="form-input" 
                                   accept="image/jpeg, image/png, image/jpg"
                                   onchange="previewImage(this)">
                            <div class="file-upload-info">
                                <i class="fas fa-info-circle"></i>
                                Max file size: 5MB. Accepted formats: JPG, PNG
                            </div>
                        </div>
                        <!-- Image Preview -->
                        <div id="imagePreview" class="image-preview" style="display: none;">
                            <img src="" alt="Preview">
                            <button type="button" class="remove-image" onclick="removeImage()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Privacy Notice -->
                    <div class="privacy-notice">
                        <i class="fas fa-shield-alt"></i>
                        <p>Your report will be handled confidentially. Personal information will only be used for follow-up purposes.</p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        Submit Report
                    </button>

                    <!-- Cancel Link -->
                    <div class="text-center mt-2">
                        <a href="dashboard.php" class="cancel-link">
                            <i class="fas fa-arrow-left"></i>
                            Cancel and return to Dashboard
                        </a>
                    </div>
                </form>
            </div>

            <!-- Quick Tips Card -->
            <div class="tips-card">
                <h3 class="tips-title">
                    <i class="fas fa-lightbulb"></i>
                    Reporting Tips
                </h3>
                <ul class="tips-list">
                    <li><i class="fas fa-check-circle"></i> Be as specific as possible about the location</li>
                    <li><i class="fas fa-check-circle"></i> Include photos for better documentation</li>
                    <li><i class="fas fa-check-circle"></i> Provide accurate contact information for follow-ups</li>
                    <li><i class="fas fa-check-circle"></i> For emergencies, call 911 or barangay hotline immediately</li>
                </ul>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript for Image Preview -->
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = preview.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    const fileInput = document.getElementById('photo');
    const preview = document.getElementById('imagePreview');
    
    fileInput.value = '';
    preview.style.display = 'none';
    preview.querySelector('img').src = '';
}
</script>

<?php include 'includes/footer.php'; ?>