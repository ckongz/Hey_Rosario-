<?php
$page_title = 'Community Updates - Hey Rosario!';
require_once 'includes/db-connection.php';
require_once 'includes/session-check.php';
include 'includes/header.php';

$announcements = fetchAll($pdo, "SELECT a.*, ad.first_name, ad.last_name FROM announcements a LEFT JOIN admins ad ON a.author_id = ad.admin_id WHERE is_archived = 0 ORDER BY created_at DESC LIMIT 20");
?>

<div class="page-content">
    <!-- Hero Section - Match your CSS structure -->
    <section class="hero page-hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Community Updates</h1>
                <p class="tagline">Stay informed with real-time announcements from Barangay Rosario</p>
            </div>
        </div>
    </section>

    <!-- Updates Section -->
    <section class="section">
        <div class="container">
            <?php if (empty($announcements)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No announcements available at this time.
                </div>
            <?php else: ?>
                <div class="updates-grid">
                    <?php foreach ($announcements as $announcement): ?>
                        <div class="update-card <?php echo strtolower($announcement['category']) === 'emergency' ? 'emergency-card' : ''; ?>">
                            <?php if (strtolower($announcement['category']) === 'emergency'): ?>
                                <div class="emergency-badge">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    EMERGENCY ALERT
                                </div>
                            <?php endif; ?>
                            
                            <div class="update-header">
                                <h2 class="update-title"><?php echo htmlspecialchars($announcement['title']); ?></h2>
                                <div class="update-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?php echo date('F d, Y', strtotime($announcement['created_at'])); ?>
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-tag"></i>
                                        <?php echo htmlspecialchars($announcement['category']); ?>
                                    </span>
                                    <?php if (!empty($announcement['first_name'])): ?>
                                        <span class="meta-item">
                                            <i class="fas fa-user"></i>
                                            <?php echo htmlspecialchars($announcement['first_name'] . ' ' . $announcement['last_name']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="update-content">
                                <?php echo nl2br(htmlspecialchars($announcement['content'])); ?>
                            </div>
                            
                            <?php if (!empty($announcement['attachment'])): ?>
                                <div class="update-attachment">
                                    <i class="fas fa-paperclip"></i>
                                    <a href="uploads/<?php echo htmlspecialchars($announcement['attachment']); ?>" target="_blank">
                                        View Attachment
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>