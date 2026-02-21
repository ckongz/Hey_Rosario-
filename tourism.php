<?php
$page_title = 'Tourism - Hey Rosario!';
require_once 'includes/db-connection.php';
require_once 'includes/session-check.php';
include 'includes/header.php';

// Fetch tourism spots from database
$spots = dbQuery("SELECT * FROM tourism_spots ORDER BY is_featured DESC, name");

// Get unique categories for filter buttons
$categories = dbQuery("SELECT DISTINCT category FROM tourism_spots WHERE category IS NOT NULL ORDER BY category");
?>

<div class="page-content">
    <!-- Hero Section - Matching your CSS structure -->
    <section class="hero page-hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Discover Sto. Rosario</h1>
                <p class="tagline">Where heritage meets heart</p>
            </div>
        </div>
    </section>

    <!-- Tourism Section -->
    <section class="section">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header">
                <span class="section-label">EXPLORE OUR BARANGAY</span>
                <h2 class="section-title">Tourist Spots & Attractions</h2>
                <p class="section-subtitle">
                    Discover the hidden gems, cultural heritage, and vibrant community spots 
                    that make Barangay Sto. Rosario truly special.
                </p>
            </div>

            <!-- Category Filters -->
            <?php if (!empty($categories)): ?>
            <div class="tourism-filters">
                <button class="filter-btn active" data-filter="all">All Spots</button>
                <?php foreach ($categories as $category): ?>
                    <button class="filter-btn" data-filter="<?php echo strtolower($category['category']); ?>">
                        <?php echo htmlspecialchars($category['category']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (empty($spots)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    No tourism spots available at the moment. Please check back later.
                </div>
            <?php else: ?>
                <!-- Tourism Grid - Using your grid system -->
                <div class="tourism-grid">
                    <?php foreach ($spots as $index => $spot): 
                        $featured = !empty($spot['is_featured']) ? 'featured' : '';
                        $category = strtolower($spot['category'] ?? 'uncategorized');
                        
                        // Map category to icon
                        $categoryIcon = 'fa-map-pin';
                        switch($category) {
                            case 'historical':
                                $categoryIcon = 'fa-landmark';
                                break;
                            case 'cultural':
                                $categoryIcon = 'fa-masks-theater';
                                break;
                            case 'food':
                            case 'food & dining':
                                $categoryIcon = 'fa-utensils';
                                break;
                            case 'nature':
                            case 'nature & parks':
                                $categoryIcon = 'fa-tree';
                                break;
                            case 'shopping':
                                $categoryIcon = 'fa-shopping-bag';
                                break;
                        }
                    ?>
                        <div class="tourism-card <?php echo $featured; ?>" 
                             data-category="<?php echo $category; ?>">
                            
                            <!-- Featured Badge -->
                            <?php if (!empty($spot['is_featured'])): ?>
                                <div class="featured-badge">
                                    <i class="fas fa-crown"></i>
                                    Featured Spot
                                </div>
                            <?php endif; ?>

                            <!-- Card Image -->
                            <div class="tourism-image">
                                <?php 
                                $imagePath = !empty($spot['image_path']) ? 'uploads/tourism/' . htmlspecialchars($spot['image_path']) : '';
                                if (!empty($spot['image_path']) && file_exists($imagePath)): ?>
                                    <img src="<?php echo $imagePath; ?>" 
                                         alt="<?php echo htmlspecialchars($spot['name']); ?>"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="image-placeholder">
                                        <i class="fas <?php echo $categoryIcon; ?>"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Card Content -->
                            <div class="tourism-content">
                                <!-- Category Badge -->
                                <?php if (!empty($spot['category'])): ?>
                                <div class="category-badge category-<?php echo $category; ?>">
                                    <i class="fas <?php echo $categoryIcon; ?>"></i>
                                    <?php echo htmlspecialchars($spot['category']); ?>
                                </div>
                                <?php endif; ?>

                                <!-- Title -->
                                <h3 class="tourism-title"><?php echo htmlspecialchars($spot['name']); ?></h3>

                                <!-- Description -->
                                <?php if (!empty($spot['short_description'])): ?>
                                <p class="tourism-description">
                                    <?php echo htmlspecialchars($spot['short_description']); ?>
                                </p>
                                <?php endif; ?>

                                <!-- Details -->
                                <div class="tourism-details">
                                    <?php if (!empty($spot['location'])): ?>
                                        <div class="detail-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?php echo htmlspecialchars($spot['location']); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($spot['operating_hours'])): ?>
                                        <div class="detail-item">
                                            <i class="fas fa-clock"></i>
                                            <span><?php echo htmlspecialchars($spot['operating_hours']); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($spot['contact_info'])): ?>
                                        <div class="detail-item">
                                            <i class="fas fa-phone"></i>
                                            <span><?php echo htmlspecialchars($spot['contact_info']); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($spot['admission_fee'])): ?>
                                        <div class="detail-item">
                                            <i class="fas fa-ticket-alt"></i>
                                            <span>Entrance: <?php echo htmlspecialchars($spot['admission_fee']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Action Buttons -->
                                <div class="tourism-actions">
                                    <button class="btn-outline btn-small" onclick="viewSpot(<?php echo $spot['id']; ?>)">
                                        <i class="fas fa-info-circle"></i>
                                        Learn More
                                    </button>
                                    <button class="btn-primary btn-small" onclick='getDirections(<?php echo json_encode($spot['location'] ?? ""); ?>)'>
                                        <i class="fas fa-directions"></i>
                                        Directions
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Featured Section (Optional) -->
    <?php 
    $featuredSpots = array_filter($spots, function($spot) {
        return !empty($spot['is_featured']);
    });
    if (!empty($featuredSpots)): 
    ?>
    <section class="section-alt">
        <div class="container">
            <div class="section-header">
                <span class="section-label">MUST VISIT</span>
                <h2 class="section-title">Featured Attractions</h2>
                <p class="section-subtitle">
                    Don't miss these popular spots recommended by locals
                </p>
            </div>

            <div class="featured-slider">
                <?php foreach ($featuredSpots as $spot): ?>
                    <div class="featured-card">
                        <div class="featured-content">
                            <h3><?php echo htmlspecialchars($spot['name']); ?></h3>
                            <p><?php echo htmlspecialchars($spot['short_description'] ?? ''); ?></p>
                            <a href="tourism-details.php?id=<?php echo $spot['id']; ?>" class="btn-primary">Explore Now</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</div>

<!-- JavaScript for Interactive Features -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.tourism-filters .filter-btn');
    const tourismCards = document.querySelectorAll('.tourism-card');
    
    // Filter function
    function filterCards(filterValue) {
        tourismCards.forEach(card => {
            const cardCategory = card.dataset.category;
            
            if (filterValue === 'all' || cardCategory === filterValue) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0) scale(1)';
                    card.style.visibility = 'visible';
                }, 50);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px) scale(0.95)';
                setTimeout(() => {
                    card.style.display = 'none';
                    card.style.visibility = 'hidden';
                }, 300);
            }
        });
    }
    
    // Add click event to filter buttons
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            filterCards(filter);
            
            // Update URL hash for shareable filters
            if (filter !== 'all') {
                window.location.hash = 'filter=' + filter;
            } else {
                window.location.hash = '';
            }
        });
    });
    
    // Check URL hash for initial filter
    if (window.location.hash) {
        const hashFilter = window.location.hash.replace('#filter=', '');
        const filterBtn = document.querySelector(`.filter-btn[data-filter="${hashFilter}"]`);
        if (filterBtn) {
            filterBtn.click();
        }
    }
});

// View spot details
function viewSpot(id) {
    if (id) {
        window.location.href = 'tourism-details.php?id=' + id;
    }
}

// Get directions - FIXED LINE 206
function getDirections(location) {
    if (location && location.trim() !== '') {
        // Open Google Maps with the location
        const query = encodeURIComponent(location + ', Barangay Sto. Rosario, Philippines');
        window.open(`https://www.google.com/maps/search/?api=1&query=${query}`, '_blank');
    } else {
        // Fallback to barangay center
        window.open('https://www.google.com/maps/search/?api=1&query=Barangay+Sto.+Rosario+Philippines', '_blank');
    }
}

// Lazy loading images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                }
                imageObserver.unobserve(img);
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}
</script>

<?php include 'includes/footer.php'; ?>