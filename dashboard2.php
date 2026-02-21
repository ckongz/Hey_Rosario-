<?php
$page_title = 'Visitor Dashboard - Hey Rosario!';
require_once 'includes/db-connection.php';
require_once 'includes/session-check.php';
requireLogin();
include 'includes/header.php';
?>
<div class="container">
    <div class="hero"><h1>Welcome, Visitor!</h1>
    <p>Explore public information and services</p></div>
    <div class="grid grid-2 mt-3">
        <div class="card"><h3>Public Announcements</h3>
            <p>Stay updated with the latest community news and emergency alerts.</p>
            <a href="updates.php" class="btn btn-primary mt-2">View Updates</a>
        </div>
        <div class="card"><h3>Tourism Guide</h3>
            <p>Discover local attractions and cultural landmarks.</p>
            <a href="tourism.php" class="btn btn-primary mt-2">Explore Tourism</a>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>