<?php
$page_title = 'My Dashboard - Hey Rosario!';
require_once 'includes/db-connection.php';
require_once 'includes/session-check.php';
requireLogin();
include 'includes/header.php';
$user_reports = fetchAll($pdo, "SELECT * FROM reports WHERE user_id = ? ORDER BY created_at DESC LIMIT 10", [getUserId()]);
?>
<div class="container">
    <div class="hero"><h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <p>Your Citizen Dashboard</p></div>
    <div class="grid grid-2 mt-3">
        <div class="card"><h3>My Reports</h3>
            <table class="table mt-2">
                <thead><tr><th>Tracking #</th><th>Type</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                <?php foreach ($user_reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['tracking_number']); ?></td>
                        <td><?php echo htmlspecialchars($report['report_type']); ?></td>
                        <td><span style="background: var(--hover-yellow); padding: 0.3rem 0.6rem; border-radius: 5px;"><?php echo $report['status']; ?></span></td>
                        <td><?php echo date('M d, Y', strtotime($report['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card"><h3>Quick Actions</h3>
            <a href="report.php" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">File New Report</a>
            <a href="services.php" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Apply for Services</a>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>