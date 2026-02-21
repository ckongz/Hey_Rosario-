<?php
$page_title = 'Community Updates - Hey Rosario!';
require_once 'includes/db-connection.php';
require_once 'includes/session-check.php';
include 'includes/header.php';

$announcements = fetchAll($pdo, "SELECT a.*, ad.first_name, ad.last_name FROM announcements a LEFT JOIN admins ad ON a.author_id = ad.admin_id WHERE is_archived = 0 ORDER BY created_at DESC LIMIT 20");
?>

<div class="page-content">
    <!-- Hero Section -->
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
            <!-- Section Header -->
            <div class="section-header">
                <span class="section-label">LATEST NEWS</span>
                <h2 class="section-title">Barangay Announcements</h2>
                <p class="section-subtitle">
                    Stay up to date with the latest news, events, and important announcements 
                    from your barangay.
                </p>
            </div>

            <?php if (empty($announcements)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    No announcements available at this time. Please check back later.
                </div>
            <?php else: ?>
                <!-- Category Filters -->
                <div class="announcement-filters">
                    <button class="filter-btn active" data-filter="all">All Updates</button>
                    <button class="filter-btn" data-filter="announcement">Announcements</button>
                    <button class="filter-btn" data-filter="event">Events</button>
                    <button class="filter-btn" data-filter="emergency">Emergency</button>
                    <button class="filter-btn" data-filter="news">News</button>
                </div>

                <!-- Updates Grid -->
                <div class="updates-grid">
                    <?php foreach ($announcements as $index => $announcement): 
                        $category = strtolower($announcement['category']);
                        $isEmergency = $category === 'emergency';
                        $animationDelay = $index * 0.1;
                    ?>
                        <div class="update-card <?php echo $isEmergency ? 'emergency-card' : ''; ?>" 
                             data-category="<?php echo htmlspecialchars($category); ?>"
                             style="animation-delay: <?php echo $animationDelay; ?>s">
                            
                            <?php if ($isEmergency): ?>
                                <div class="emergency-badge">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    EMERGENCY ALERT
                                </div>
                            <?php endif; ?>
                            
                            <div class="update-header">
                                <!-- Category Badge -->
                                <div class="category-badge category-<?php echo htmlspecialchars($category); ?>">
                                    <?php 
                                    $categoryIcon = '';
                                    switch($category) {
                                        case 'emergency':
                                            $categoryIcon = 'fa-exclamation-circle';
                                            break;
                                        case 'event':
                                            $categoryIcon = 'fa-calendar-alt';
                                            break;
                                        case 'news':
                                            $categoryIcon = 'fa-newspaper';
                                            break;
                                        default:
                                            $categoryIcon = 'fa-bullhorn';
                                    }
                                    ?>
                                    <i class="fas <?php echo $categoryIcon; ?>"></i>
                                    <?php echo htmlspecialchars($announcement['category']); ?>
                                </div>
                                
                                <h2 class="update-title"><?php echo htmlspecialchars($announcement['title']); ?></h2>
                                
                                <div class="update-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?php echo date('F d, Y', strtotime($announcement['created_at'])); ?>
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <?php echo date('h:i A', strtotime($announcement['created_at'])); ?>
                                    </span>
                                    <?php if (!empty($announcement['first_name'])): ?>
                                        <span class="meta-item">
                                            <i class="fas fa-user-circle"></i>
                                            <?php echo htmlspecialchars($announcement['first_name'] . ' ' . $announcement['last_name']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="update-content">
                                <?php 
                                $content = nl2br(htmlspecialchars($announcement['content']));
                                // Truncate long content for preview
                                if (strlen($announcement['content']) > 300) {
                                    $preview = substr(htmlspecialchars($announcement['content']), 0, 300) . '...';
                                    echo '<div class="content-preview">' . nl2br($preview) . '</div>';
                                    echo '<button class="read-more-btn" onclick="toggleContent(this)">Read More</button>';
                                    echo '<div class="content-full" style="display: none;">' . $content . '</div>';
                                } else {
                                    echo $content;
                                }
                                ?>
                            </div>
                            
                            <?php if (!empty($announcement['attachment'])): ?>
                                <div class="update-attachment">
                                    <i class="fas fa-paperclip"></i>
                                    <a href="uploads/<?php echo htmlspecialchars($announcement['attachment']); ?>" target="_blank">
                                        View Attachment
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Social Share Buttons -->
                            <div class="share-buttons">
                                <span class="share-label">Share:</span>
                                <a href="#" onclick="shareOnFacebook('<?php echo urlencode($announcement['title']); ?>')" class="share-btn facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" onclick="shareOnTwitter('<?php echo urlencode($announcement['title']); ?>')" class="share-btn twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" onclick="shareOnWhatsApp('<?php echo urlencode($announcement['title']); ?>')" class="share-btn whatsapp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Load More Button -->
                <?php if (count($announcements) >= 20): ?>
                    <div class="text-center mt-4">
                        <button class="btn-outline load-more-btn" onclick="loadMoreAnnouncements()">
                            <i class="fas fa-sync-alt"></i>
                            Load More Updates
                        </button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
</div>

<!-- JavaScript for Interactive Features -->
<script>
// Toggle Read More/Less
function toggleContent(btn) {
    const card = btn.closest('.update-card');
    const preview = card.querySelector('.content-preview');
    const full = card.querySelector('.content-full');
    
    if (full.style.display === 'none') {
        preview.style.display = 'none';
        full.style.display = 'block';
        btn.textContent = 'Read Less';
    } else {
        preview.style.display = 'block';
        full.style.display = 'none';
        btn.textContent = 'Read More';
    }
}

// Category Filtering
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const updateCards = document.querySelectorAll('.update-card');
    
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            updateCards.forEach(card => {
                if (filter === 'all' || card.dataset.category === filter) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
});

// Social Sharing Functions
function shareOnFacebook(title) {
    const url = window.location.href;
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}&quote=${encodeURIComponent(title)}`, '_blank');
}

function shareOnTwitter(title) {
    const url = window.location.href;
    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`, '_blank');
}

function shareOnWhatsApp(title) {
    const url = window.location.href;
    window.open(`https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}`, '_blank');
}

// Load More Function (simulated)
function loadMoreAnnouncements() {
    const btn = document.querySelector('.load-more-btn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    btn.disabled = true;
    
    // Simulate loading - replace with actual AJAX call
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-check"></i> No More Updates';
        btn.disabled = true;
    }, 2000);
}
</script>

<?php include 'includes/footer.php'; ?>