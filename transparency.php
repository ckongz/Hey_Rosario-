<?php
$page_title = 'Transparency Portal - Hey Rosario!';
require_once 'includes/db-connection.php';
require_once 'includes/session-check.php';
include 'includes/header.php';

// Get budget items for 2026
$budget_items = fetchAll($pdo, "SELECT * FROM budget_items WHERE fiscal_year = 2026 ORDER BY category, sub_category");

// Calculate totals
$total_allocated = 0;
$total_expended = 0;
foreach ($budget_items as $item) {
    $total_allocated += $item['allocated_amount'];
    $total_expended += $item['expended_amount'];
}
$balance = $total_allocated - $total_expended;
?>

<div class="page-content">
    <!-- Hero Section - Matching your CSS structure -->
    <section class="hero page-hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Transparency & Accountability</h1>
                <p class="tagline">Your right to know, our duty to inform</p>
            </div>
        </div>
    </section>

    <!-- Transparency Section -->
    <section class="section">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header">
                <span class="section-label">OPEN GOVERNMENT</span>
                <h2 class="section-title">Financial Transparency Portal</h2>
                <p class="section-subtitle">
                    Track how your barangay allocates and spends public funds. 
                    We believe in complete transparency and accountability.
                </p>
            </div>

            <!-- Summary Cards -->
            <div class="budget-summary">
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="summary-content">
                        <span class="summary-label">Fiscal Year</span>
                        <span class="summary-value">2026</span>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="summary-content">
                        <span class="summary-label">Total Allocated</span>
                        <span class="summary-value">₱<?php echo number_format($total_allocated, 2); ?></span>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="summary-content">
                        <span class="summary-label">Total Expended</span>
                        <span class="summary-value">₱<?php echo number_format($total_expended, 2); ?></span>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <div class="summary-content">
                        <span class="summary-label">Remaining Balance</span>
                        <span class="summary-value <?php echo $balance >= 0 ? 'positive' : 'negative'; ?>">
                            ₱<?php echo number_format($balance, 2); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Category Filters -->
            <div class="budget-filters">
                <button class="filter-btn active" data-filter="all">All Categories</button>
                <button class="filter-btn" data-filter="Administration">Administration</button>
                <button class="filter-btn" data-filter="Infrastructure">Infrastructure</button>
                <button class="filter-btn" data-filter="Social Services">Social Services</button>
                <button class="filter-btn" data-filter="Health">Health</button>
                <button class="filter-btn" data-filter="Education">Education</button>
                <button class="filter-btn" data-filter="Disaster">Disaster & Emergency</button>
            </div>

            <?php if (empty($budget_items)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    No budget data available for 2026. Please check back later.
                </div>
            <?php else: ?>
                <!-- Budget Table Card -->
                <div class="budget-card">
                    <div class="budget-header">
                        <h3 class="budget-title">
                            <i class="fas fa-chart-pie"></i>
                            2026 Detailed Budget Allocation
                        </h3>
                        <div class="budget-actions">
                            <button class="btn-outline btn-small" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </button>
                            <button class="btn-outline btn-small" onclick="exportToExcel()">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                        </div>
                    </div>

                    <!-- Table Wrapper for Responsive Design -->
                    <div class="table-wrapper">
                        <table class="budget-table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Sub-Category</th>
                                    <th>Description / Project</th>
                                    <th>Allocated (₱)</th>
                                    <th>Expended (₱)</th>
                                    <th>Balance (₱)</th>
                                    <th>Utilization</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $current_category = '';
                                foreach ($budget_items as $item): 
                                    $allocated = $item['allocated_amount'];
                                    $expended = $item['expended_amount'];
                                    $item_balance = $allocated - $expended;
                                    $utilization_percent = $allocated > 0 ? round(($expended / $allocated) * 100) : 0;
                                    
                                    // Determine status color
                                    $status_class = '';
                                    $status = strtolower($item['project_status']);
                                    if ($status === 'completed') {
                                        $status_class = 'status-completed';
                                    } elseif ($status === 'ongoing') {
                                        $status_class = 'status-ongoing';
                                    } elseif ($status === 'planned') {
                                        $status_class = 'status-planned';
                                    } elseif ($status === 'pending') {
                                        $status_class = 'status-pending';
                                    }
                                ?>
                                    <?php if ($current_category !== $item['category']): ?>
                                        <?php $current_category = $item['category']; ?>
                                        <tr class="category-header">
                                            <td colspan="8">
                                                <i class="fas fa-folder-open"></i>
                                                <?php echo htmlspecialchars($item['category']); ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    
                                    <tr class="budget-row" data-category="<?php echo htmlspecialchars($item['category']); ?>">
                                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                                        <td><?php echo htmlspecialchars($item['sub_category'] ?: '—'); ?></td>
                                        <td class="description-cell">
                                            <?php echo htmlspecialchars($item['description']); ?>
                                            <?php if (!empty($item['remarks'])): ?>
                                                <span class="remarks-tooltip" title="<?php echo htmlspecialchars($item['remarks']); ?>">
                                                    <i class="fas fa-info-circle"></i>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="amount">₱<?php echo number_format($allocated, 2); ?></td>
                                        <td class="amount">₱<?php echo number_format($expended, 2); ?></td>
                                        <td class="amount <?php echo $item_balance >= 0 ? 'positive' : 'negative'; ?>">
                                            ₱<?php echo number_format($item_balance, 2); ?>
                                        </td>
                                        <td class="utilization">
                                            <div class="progress-bar-container" title="<?php echo $utilization_percent; ?>% utilized">
                                                <div class="progress-bar" style="width: <?php echo $utilization_percent; ?>%;">
                                                    <span class="progress-text"><?php echo $utilization_percent; ?>%</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge <?php echo $status_class; ?>">
                                                <?php echo htmlspecialchars($item['project_status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="3"><strong>TOTAL</strong></td>
                                    <td class="amount"><strong>₱<?php echo number_format($total_allocated, 2); ?></strong></td>
                                    <td class="amount"><strong>₱<?php echo number_format($total_expended, 2); ?></strong></td>
                                    <td class="amount <?php echo $balance >= 0 ? 'positive' : 'negative'; ?>">
                                        <strong>₱<?php echo number_format($balance, 2); ?></strong>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="charts-grid">
                    <div class="chart-card">
                        <h4 class="chart-title">
                            <i class="fas fa-chart-pie"></i>
                            Allocation by Category
                        </h4>
                        <div class="chart-container" id="allocationChart">
                            <!-- Chart will be rendered here -->
                            <div class="chart-placeholder">
                                <i class="fas fa-chart-pie"></i>
                                <p>Chart visualization coming soon</p>
                            </div>
                        </div>
                    </div>

                    <div class="chart-card">
                        <h4 class="chart-title">
                            <i class="fas fa-chart-line"></i>
                            Monthly Expenditure Trend
                        </h4>
                        <div class="chart-container" id="trendChart">
                            <!-- Chart will be rendered here -->
                            <div class="chart-placeholder">
                                <i class="fas fa-chart-line"></i>
                                <p>Chart visualization coming soon</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Documents Section -->
            <div class="documents-section">
                <h3 class="documents-title">
                    <i class="fas fa-file-alt"></i>
                    Supporting Documents
                </h3>
                <div class="documents-grid">
                    <a href="#" class="document-card">
                        <i class="fas fa-file-pdf"></i>
                        <span>2026 Annual Budget Report</span>
                    </a>
                    <a href="#" class="document-card">
                        <i class="fas fa-file-excel"></i>
                        <span>Quarterly Financial Statements - Q1 2026</span>
                    </a>
                    <a href="#" class="document-card">
                        <i class="fas fa-file-pdf"></i>
                        <span>Audit Report 2025</span>
                    </a>
                    <a href="#" class="document-card">
                        <i class="fas fa-file-pdf"></i>
                        <span>Procurement Plan 2026</span>
                    </a>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="faq-section">
                <h3 class="faq-title">
                    <i class="fas fa-question-circle"></i>
                    Frequently Asked Questions
                </h3>
                <div class="faq-grid">
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>How often is this information updated?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            Budget information is updated monthly as expenditures are recorded and verified.
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>Where can I find previous years' budgets?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            Previous years' budgets are available in our archives section. Please contact the barangay hall for access.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript for Interactive Features -->
<script>
// Category Filtering
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.budget-filters .filter-btn');
    const budgetRows = document.querySelectorAll('.budget-row');
    
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            budgetRows.forEach(row => {
                if (filter === 'all' || row.dataset.category === filter) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});

// Toggle FAQ
function toggleFAQ(element) {
    const faqItem = element.closest('.faq-item');
    const answer = faqItem.querySelector('.faq-answer');
    const icon = element.querySelector('i');
    
    if (answer.style.display === 'none' || !answer.style.display) {
        answer.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    } else {
        answer.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    }
}

// Export functions
function exportToPDF() {
    alert('PDF export feature coming soon!');
}

function exportToExcel() {
    alert('Excel export feature coming soon!');
}
</script>

<?php include 'includes/footer.php'; ?>