<?php
session_start();
require_once 'includes/db-connection.php';
require_once 'includes/session-check.php';
requireAdmin();

$page_title = 'Admin Dashboard — Hey Rosario!';
$section = $_GET['section'] ?? 'overview';

// ── FETCH ALL DATA ──────────────────────────────────────────────────────────
// Stats
$stats = [
    'total_users'    => dbCount("SELECT COUNT(*) FROM users"),
    'pending_users'  => dbCount("SELECT COUNT(*) FROM users WHERE verification_status='pending'"),
    'total_reports'  => dbCount("SELECT COUNT(*) FROM reports"),
    'pending_reports'=> dbCount("SELECT COUNT(*) FROM reports WHERE status='Pending'"),
    'announcements'  => dbCount("SELECT COUNT(*) FROM announcements"),
    'services'       => dbCount("SELECT COUNT(*) FROM services"),
    'applications'   => dbCount("SELECT COUNT(*) FROM service_applications"),
    'messages'       => dbCount("SELECT COUNT(*) FROM contact_messages WHERE is_read=0"),
];
if (!$pdo) $stats = ['total_users'=>8,'pending_users'=>2,'total_reports'=>5,'pending_reports'=>3,'announcements'=>5,'services'=>10,'applications'=>3,'messages'=>3];

// Data for each section
$data = [];
switch($section) {
    case 'users':
        $filter = $_GET['filter'] ?? 'all';
        $where = $filter !== 'all' ? "WHERE verification_status='$filter'" : '';
        $data['users'] = dbQuery("SELECT * FROM users $where ORDER BY created_at DESC");
        if (empty($data['users'])) $data['users'] = [
            ['user_id'=>1,'first_name'=>'Anna','last_name'=>'Rodriguez','email'=>'citizen1@email.com','contact_number'=>'0917-567-8901','purok'=>'Purok 1','role'=>'citizen','verification_status'=>'verified','created_at'=>'2026-01-15 10:00:00'],
            ['user_id'=>2,'first_name'=>'Carlos','last_name'=>'Martinez','email'=>'citizen2@email.com','contact_number'=>'0917-678-9012','purok'=>'Purok 3','role'=>'citizen','verification_status'=>'verified','created_at'=>'2026-01-20 11:00:00'],
            ['user_id'=>3,'first_name'=>'Sofia','last_name'=>'Ramos','email'=>'pendinguser@email.com','contact_number'=>'0917-789-0123','purok'=>'Purok 5','role'=>'citizen','verification_status'=>'pending','id_file'=>null,'created_at'=>'2026-02-17 08:00:00'],
            ['user_id'=>4,'first_name'=>'Guest','last_name'=>'User','email'=>'guest1@email.com','contact_number'=>'0917-000-0001','purok'=>'Purok 2','role'=>'guest','verification_status'=>'verified','created_at'=>'2026-02-01 09:00:00'],
        ];
        break;
    case 'reports':
        $filter = $_GET['filter'] ?? 'all';
        $where = $filter !== 'all' ? "WHERE r.status='$filter'" : '';
        $data['reports'] = dbQuery("SELECT r.*,u.first_name,u.last_name,u.email FROM reports r LEFT JOIN users u ON r.user_id=u.user_id $where ORDER BY r.created_at DESC");
        if (empty($data['reports'])) $data['reports'] = [
            ['report_id'=>1,'tracking_number'=>'RPT-2026-00001','first_name'=>'Anna','last_name'=>'Rodriguez','email'=>'citizen1@email.com','report_type'=>'Infrastructure','incident_type'=>'Broken Streetlight','title'=>'Non-functional streetlight on Rizal Street','description'=>'The streetlight near Rizal Street has been non-functional for 3 days.','location_description'=>'Corner of Rizal St and Gomez Ave','purok'=>'Purok 1','status'=>'Resolved','priority'=>'High','is_anonymous'=>0,'created_at'=>'2026-02-10 09:00:00','admin_notes'=>''],
            ['report_id'=>2,'tracking_number'=>'RPT-2026-00002','first_name'=>'Carlos','last_name'=>'Martinez','email'=>'citizen2@email.com','report_type'=>'Sanitation','incident_type'=>'Missed Garbage Collection','title'=>'Missed garbage collection on Santos Blvd','description'=>'Garbage collection was missed yesterday.','location_description'=>'Santos Boulevard, Purok 3','purok'=>'Purok 3','status'=>'In Progress','priority'=>'Medium','is_anonymous'=>0,'created_at'=>'2026-02-12 10:00:00','admin_notes'=>''],
            ['report_id'=>3,'tracking_number'=>'RPT-2026-00003','first_name'=>'Anna','last_name'=>'Rodriguez','email'=>'citizen1@email.com','report_type'=>'Infrastructure','incident_type'=>'Road Damage','title'=>'Large pothole on Flores Street','description'=>'There is a large pothole on Flores Street.','location_description'=>'Flores Street near barangay hall','purok'=>'Purok 2','status'=>'Pending','priority'=>'High','is_anonymous'=>0,'created_at'=>'2026-02-14 11:00:00','admin_notes'=>''],
            ['report_id'=>4,'tracking_number'=>'RPT-2026-00004','first_name'=>null,'last_name'=>null,'email'=>null,'report_type'=>'Safety','incident_type'=>'Suspicious Activity','title'=>'Suspicious persons near covered court','description'=>'Suspicious persons loitering late at night.','location_description'=>'Near Barangay Covered Court','purok'=>'Purok 4','status'=>'Reviewing','priority'=>'Urgent','is_anonymous'=>1,'created_at'=>'2026-02-15 22:00:00','admin_notes'=>''],
            ['report_id'=>5,'tracking_number'=>'RPT-2026-00005','first_name'=>'Carlos','last_name'=>'Martinez','email'=>'citizen2@email.com','report_type'=>'Sanitation','incident_type'=>'Illegal Dumping','title'=>'Illegal garbage dumping behind sari-sari store','description'=>'Residents are illegally dumping trash.','location_description'=>'Behind Sari-Sari store, Purok 5','purok'=>'Purok 5','status'=>'Pending','priority'=>'Medium','is_anonymous'=>0,'created_at'=>'2026-02-16 14:00:00','admin_notes'=>''],
        ];
        break;
    case 'announcements':
        $data['announcements'] = dbQuery("SELECT a.*,ad.first_name,ad.last_name FROM announcements a LEFT JOIN admins ad ON a.author_id=ad.admin_id ORDER BY a.created_at DESC");
        if (empty($data['announcements'])) $data['announcements'] = [
            ['announcement_id'=>1,'title'=>'Road Rehabilitation — Sto. Rosario St. Phase 2','category'=>'Infrastructure','is_pinned'=>1,'is_archived'=>0,'first_name'=>'System','last_name'=>'Admin','created_at'=>'2026-02-15'],
            ['announcement_id'=>2,'title'=>'FREE Medical Mission — February 22, 2026','category'=>'Health','is_pinned'=>0,'is_archived'=>0,'first_name'=>'System','last_name'=>'Admin','created_at'=>'2026-02-12'],
            ['announcement_id'=>3,'title'=>'Quarterly Barangay Assembly — February 28, 2026','category'=>'Events','is_pinned'=>0,'is_archived'=>0,'first_name'=>'System','last_name'=>'Admin','created_at'=>'2026-02-10'],
            ['announcement_id'=>4,'title'=>'4Ps February 2026: Mandatory FDS Schedule','category'=>'Government Services','is_pinned'=>0,'is_archived'=>0,'first_name'=>'System','last_name'=>'Admin','created_at'=>'2026-02-06'],
            ['announcement_id'=>5,'title'=>'Dengue Prevention Drive — February 17-20','category'=>'Health','is_pinned'=>0,'is_archived'=>0,'first_name'=>'System','last_name'=>'Admin','created_at'=>'2026-02-01'],
        ];
        break;
    case 'services':
        $data['services'] = dbQuery("SELECT * FROM services ORDER BY category,service_name");
        if (empty($data['services'])) $data['services'] = [
            ['service_id'=>1,'service_code'=>'SVC-001','service_name'=>'Barangay Clearance','category'=>'Clearance','fee'=>50.00,'is_free'=>0,'processing_time'=>'Same Day','is_active'=>1],
            ['service_id'=>2,'service_code'=>'SVC-002','service_name'=>'Certificate of Indigency','category'=>'Certificate','fee'=>0,'is_free'=>1,'processing_time'=>'Same Day','is_active'=>1],
            ['service_id'=>3,'service_code'=>'SVC-003','service_name'=>'Certificate of Residency','category'=>'Certificate','fee'=>30.00,'is_free'=>0,'processing_time'=>'Same Day','is_active'=>1],
            ['service_id'=>4,'service_code'=>'SVC-004','service_name'=>'Business Permit / Clearance','category'=>'Permit','fee'=>300.00,'is_free'=>0,'processing_time'=>'3-5 Working Days','is_active'=>1],
            ['service_id'=>5,'service_code'=>'SVC-005','service_name'=>'First-Time Job Seeker Certification','category'=>'Certificate','fee'=>0,'is_free'=>1,'processing_time'=>'Same Day','is_active'=>1],
        ];
        break;
    case 'tourism':
        $data['tourism'] = dbQuery("SELECT * FROM tourism_spots ORDER BY is_featured DESC, name");
        if (empty($data['tourism'])) $data['tourism'] = [
            ['spot_id'=>1,'name'=>'Sto. Rosario Parish Church','category'=>'Religious','emoji'=>'⛪','operating_hours'=>'Daily 5AM-8PM','entrance_fee'=>'Free','is_featured'=>1,'rating'=>4.9],
            ['spot_id'=>2,'name'=>'Kapampangan Culinary Row','category'=>'Food','emoji'=>'🍽️','operating_hours'=>'Daily 10AM-9PM','entrance_fee'=>'Free (per order)','is_featured'=>1,'rating'=>4.8],
            ['spot_id'=>3,'name'=>'Rosario Heritage Arts Hub','category'=>'Cultural','emoji'=>'🎨','operating_hours'=>'Tue-Sun 9AM-6PM','entrance_fee'=>'Free','is_featured'=>1,'rating'=>4.6],
        ];
        break;
    case 'emergency':
        $data['emergency'] = dbQuery("SELECT * FROM emergency_contacts ORDER BY priority, category");
        if (empty($data['emergency'])) $data['emergency'] = [
            ['contact_id'=>1,'department'=>'Emergency','agency_name'=>'National Emergency Hotline','contact_number'=>'911','mobile_number'=>'','category'=>'Police','priority'=>1,'operating_hours'=>'24/7','is_active'=>1],
            ['contact_id'=>2,'department'=>'Police','agency_name'=>'PNP Angeles City Station','contact_number'=>'(045) 322-3333','mobile_number'=>'','category'=>'Police','priority'=>2,'operating_hours'=>'24/7','is_active'=>1],
            ['contact_id'=>3,'department'=>'Fire','agency_name'=>'BFP Angeles City Station','contact_number'=>'(045) 322-3001','mobile_number'=>'','category'=>'Fire','priority'=>1,'operating_hours'=>'24/7','is_active'=>1],
            ['contact_id'=>4,'department'=>'Medical','agency_name'=>'ACEF Ambulance Service','contact_number'=>'(045) 455-3000','mobile_number'=>'0919-456-7890','category'=>'Medical','priority'=>1,'operating_hours'=>'24/7','is_active'=>1],
            ['contact_id'=>7,'department'=>'Barangay','agency_name'=>'Barangay Tanod Emergency Line','contact_number'=>'(045) 625-9871','mobile_number'=>'0917-123-4567','category'=>'Barangay','priority'=>1,'operating_hours'=>'24/7','is_active'=>1],
        ];
        break;
    case 'messages':
        $data['messages'] = dbQuery("SELECT * FROM contact_messages ORDER BY is_read ASC, created_at DESC");
        if (empty($data['messages'])) $data['messages'] = [
            ['message_id'=>1,'name'=>'Juan dela Cruz','email'=>'citizen1@email.com','contact_number'=>'0917-567-8901','subject'=>'Request for Barangay Clearance','message'=>'Good day! I would like to inquire about the requirements for obtaining a barangay clearance.','category'=>'Services','is_read'=>0,'replied'=>0,'created_at'=>'2026-02-16 10:00:00'],
            ['message_id'=>2,'name'=>'Maria Santos','email'=>'citizen2@email.com','contact_number'=>'0917-678-9012','subject'=>'Road Concern near Purok 2','message'=>'There is a very dangerous pothole near our area that has been there for over a week.','category'=>'Report','is_read'=>0,'replied'=>0,'created_at'=>'2026-02-15 14:00:00'],
            ['message_id'=>3,'name'=>'Anonymous Resident','email'=>'anon@gmail.com','contact_number'=>'','subject'=>'Suggestion for Barangay','message'=>'I suggest creating a community garden near the covered court.','category'=>'Feedback','is_read'=>1,'replied'=>0,'created_at'=>'2026-02-14 09:00:00'],
        ];
        break;
    case 'officials':
        $data['officials'] = dbQuery("SELECT * FROM officials ORDER BY sort_order");
        if (empty($data['officials'])) $data['officials'] = [
            ['official_id'=>1,'name'=>'Hon. Maria Santos-Reyes','position'=>'Barangay Captain','committee'=>'Executive','initials'=>'MS','contact_number'=>'0917-234-5678','sort_order'=>1],
            ['official_id'=>2,'name'=>'Kagawad Jose dela Cruz','position'=>'Barangay Kagawad','committee'=>'Peace & Order','initials'=>'JD','contact_number'=>'0917-345-6789','sort_order'=>2],
            ['official_id'=>3,'name'=>'Kagawad Lourdes Manalo','position'=>'Barangay Kagawad','committee'=>'Health','initials'=>'LM','contact_number'=>'','sort_order'=>3],
        ];
        break;
    case 'transparency':
        $data['budget'] = dbQuery("SELECT * FROM budget_items WHERE fiscal_year=? ORDER BY category", [date('Y')]);
        if (empty($data['budget'])) $data['budget'] = [
            ['budget_id'=>1,'fiscal_year'=>2026,'category'=>'Personal Services','sub_category'=>'Salaries','description'=>'Salaries and Benefits','allocated_amount'=>1245000,'expended_amount'=>622500,'project_status'=>'Ongoing','completion_percentage'=>50,'implementation_period'=>'Jan-Dec 2026'],
            ['budget_id'=>2,'fiscal_year'=>2026,'category'=>'Infrastructure','sub_category'=>'Road Improvement','description'=>'Sto. Rosario Street Road Rehabilitation Phase 2','allocated_amount'=>485000,'expended_amount'=>218250,'project_status'=>'Ongoing','completion_percentage'=>45,'implementation_period'=>'Feb-Mar 2026'],
            ['budget_id'=>3,'fiscal_year'=>2026,'category'=>'Infrastructure','sub_category'=>'Street Lighting','description'=>'Solar Street Light Installation — 30 units','allocated_amount'=>360000,'expended_amount'=>216000,'project_status'=>'Ongoing','completion_percentage'=>60,'implementation_period'=>'Jan-Mar 2026'],
            ['budget_id'=>4,'fiscal_year'=>2026,'category'=>'Social Services','sub_category'=>'Health','description'=>'Barangay Health Center Operations','allocated_amount'=>415000,'expended_amount'=>124500,'project_status'=>'Ongoing','completion_percentage'=>30,'implementation_period'=>'Jan-Dec 2026'],
        ];
        break;
    default: // overview
        $data['pending_users']   = dbQuery("SELECT * FROM users WHERE verification_status='pending' ORDER BY created_at DESC LIMIT 8");
        $data['recent_reports']  = dbQuery("SELECT r.*,u.first_name,u.last_name FROM reports r LEFT JOIN users u ON r.user_id=u.user_id ORDER BY r.created_at DESC LIMIT 8");
        $data['recent_messages'] = dbQuery("SELECT * FROM contact_messages WHERE is_read=0 ORDER BY created_at DESC LIMIT 5");
        $data['recent_apps']     = dbQuery("SELECT sa.*,u.first_name,u.last_name,s.service_name FROM service_applications sa LEFT JOIN users u ON sa.user_id=u.user_id LEFT JOIN services s ON sa.service_id=s.service_id ORDER BY sa.created_at DESC LIMIT 5");
        if (empty($data['pending_users'])) $data['pending_users'] = [
            ['user_id'=>3,'first_name'=>'Sofia','last_name'=>'Ramos','email'=>'pendinguser@email.com','contact_number'=>'0917-789-0123','purok'=>'Purok 5','verification_status'=>'pending','id_file'=>null,'created_at'=>'2026-02-17 08:00:00'],
        ];
        if (empty($data['recent_reports'])) $data['recent_reports'] = [
            ['report_id'=>1,'tracking_number'=>'RPT-2026-00001','first_name'=>'Anna','last_name'=>'Rodriguez','report_type'=>'Infrastructure','title'=>'Non-functional streetlight on Rizal Street','status'=>'Resolved','priority'=>'High','created_at'=>'2026-02-10'],
            ['report_id'=>2,'tracking_number'=>'RPT-2026-00002','first_name'=>'Carlos','last_name'=>'Martinez','report_type'=>'Sanitation','title'=>'Missed garbage collection on Santos Blvd','status'=>'In Progress','priority'=>'Medium','created_at'=>'2026-02-12'],
            ['report_id'=>3,'tracking_number'=>'RPT-2026-00003','first_name'=>'Anna','last_name'=>'Rodriguez','report_type'=>'Infrastructure','title'=>'Large pothole on Flores Street','status'=>'Pending','priority'=>'High','created_at'=>'2026-02-14'],
        ];
        if (empty($data['recent_messages'])) $data['recent_messages'] = [
            ['message_id'=>1,'name'=>'Juan dela Cruz','subject'=>'Request for Barangay Clearance','category'=>'Services','is_read'=>0,'created_at'=>'2026-02-16'],
            ['message_id'=>2,'name'=>'Maria Santos','subject'=>'Road Concern near Purok 2','category'=>'Report','is_read'=>0,'created_at'=>'2026-02-15'],
        ];
}

// Status helpers
$report_statuses = ['Pending','Reviewing','In Progress','Resolved','Closed','Requires Clarification'];
$status_badge = function($s) {
    $m=['Pending'=>'status-pending','Reviewing'=>'status-progress','In Progress'=>'status-progress','Resolved'=>'status-approved','Closed'=>'status-approved','verified'=>'status-approved','approved'=>'status-approved','Completed'=>'status-approved','rejected'=>'status-rejected','Closed'=>'status-approved','Requires Clarification'=>'status-pending','Submitted'=>'status-pending','Processing'=>'status-progress','For Pickup'=>'status-progress','Cancelled'=>'status-rejected','pending'=>'status-pending','suspended'=>'status-rejected'];
    return $m[$s] ?? 'status-pending';
};

include 'includes/header.php';
?>

<!-- ADMIN LAYOUT -->
<div class="admin-layout" id="adminLayout">
    <!-- MOBILE OVERLAY -->
    <div class="admin-overlay" id="adminOverlay" onclick="closeSidebar()"></div>

    <!-- ═══ RESPONSIVE SIDEBAR ═══════════════════════════════════════════════ -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar-header">
            <div class="admin-sidebar-brand">
                <div class="admin-sidebar-logo">BR</div>
                <div>
                    <div class="admin-sidebar-title">Hey Rosario!</div>
                    <div class="admin-sidebar-sub">Admin Panel</div>
                </div>
            </div>
            <button class="admin-sidebar-close" onclick="closeSidebar()" title="Close sidebar">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Admin Profile in Sidebar -->
        <div class="admin-sidebar-profile">
            <div class="admin-sidebar-avatar"><?php echo strtoupper(substr($_SESSION['admin_name']??'A',0,1)); ?></div>
            <div>
                <div style="font-weight:700;font-size:0.88rem;color:white;"><?php echo htmlspecialchars($_SESSION['admin_name']??'Admin'); ?></div>
                <div style="font-size:0.72rem;color:rgba(255,255,255,0.6);text-transform:uppercase;letter-spacing:0.08em;"><?php echo ucfirst($_SESSION['admin_role']??'staff'); ?></div>
            </div>
        </div>

        <nav class="admin-nav">
            <div class="admin-nav-group">
                <div class="admin-nav-label">OVERVIEW</div>
                <a href="admin-dashboard.php" class="admin-nav-item <?php echo $section==='overview'?'active':''; ?>">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </div>
            <div class="admin-nav-group">
                <div class="admin-nav-label">MANAGE</div>
                <a href="?section=users" class="admin-nav-item <?php echo $section==='users'?'active':''; ?>">
                    <i class="fas fa-users"></i> <span>Users</span>
                    <?php if($stats['pending_users']>0): ?><span class="admin-nav-badge"><?php echo $stats['pending_users']; ?></span><?php endif; ?>
                </a>
                <a href="?section=reports" class="admin-nav-item <?php echo $section==='reports'?'active':''; ?>">
                    <i class="fas fa-flag"></i> <span>Reports</span>
                    <?php if($stats['pending_reports']>0): ?><span class="admin-nav-badge danger"><?php echo $stats['pending_reports']; ?></span><?php endif; ?>
                </a>
                <a href="?section=messages" class="admin-nav-item <?php echo $section==='messages'?'active':''; ?>">
                    <i class="fas fa-envelope"></i> <span>Messages</span>
                    <?php if($stats['messages']>0): ?><span class="admin-nav-badge"><?php echo $stats['messages']; ?></span><?php endif; ?>
                </a>
                <a href="?section=applications" class="admin-nav-item <?php echo $section==='applications'?'active':''; ?>">
                    <i class="fas fa-file-alt"></i> <span>Applications</span>
                    <?php if($stats['applications']>0): ?><span class="admin-nav-badge"><?php echo $stats['applications']; ?></span><?php endif; ?>
                </a>
            </div>
            <div class="admin-nav-group">
                <div class="admin-nav-label">CONTENT</div>
                <a href="?section=announcements" class="admin-nav-item <?php echo $section==='announcements'?'active':''; ?>">
                    <i class="fas fa-bullhorn"></i> <span>Announcements</span>
                </a>
                <a href="?section=services" class="admin-nav-item <?php echo $section==='services'?'active':''; ?>">
                    <i class="fas fa-cogs"></i> <span>Services</span>
                </a>
                <a href="?section=tourism" class="admin-nav-item <?php echo $section==='tourism'?'active':''; ?>">
                    <i class="fas fa-map-marked-alt"></i> <span>Tourism Spots</span>
                </a>
                <a href="?section=emergency" class="admin-nav-item <?php echo $section==='emergency'?'active':''; ?>">
                    <i class="fas fa-phone-alt"></i> <span>Emergency Contacts</span>
                </a>
                <a href="?section=officials" class="admin-nav-item <?php echo $section==='officials'?'active':''; ?>">
                    <i class="fas fa-user-tie"></i> <span>Officials</span>
                </a>
                <a href="?section=transparency" class="admin-nav-item <?php echo $section==='transparency'?'active':''; ?>">
                    <i class="fas fa-chart-pie"></i> <span>Budget & Transparency</span>
                </a>
            </div>
            <div class="admin-nav-group">
                <div class="admin-nav-label">SITE</div>
                <a href="index.php" target="_blank" class="admin-nav-item"><i class="fas fa-external-link-alt"></i> <span>View Website</span></a>
                <a href="processes/logout.php" class="admin-nav-item logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
            </div>
        </nav>
    </aside>

    <!-- ═══ MAIN CONTENT ══════════════════════════════════════════════════════ -->
    <main class="admin-main" id="adminMain">
        <!-- Top bar -->
        <div class="admin-topbar">
            <button class="admin-hamburger" onclick="toggleSidebar()" title="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <div class="admin-topbar-title">
                <?php $titles=['overview'=>'Dashboard Overview','users'=>'User Management','reports'=>'Report Management','announcements'=>'Announcements','services'=>'Services Management','tourism'=>'Tourism Spots','emergency'=>'Emergency Contacts','officials'=>'Barangay Officials','messages'=>'Messages & Inquiries','transparency'=>'Budget & Transparency','applications'=>'Service Applications'];
                echo $titles[$section] ?? 'Admin Dashboard'; ?>
            </div>
            <div class="admin-topbar-right">
                <span class="admin-topbar-date"><?php echo date('D, M d Y'); ?></span>
                <a href="processes/logout.php" class="admin-topbar-logout" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if(isset($_GET['success'])): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars(urldecode($_GET['success'])); ?></div><?php endif; ?>
        <?php if(isset($_GET['error'])): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars(urldecode($_GET['error'])); ?></div><?php endif; ?>
        <div id="toastContainer" class="toast-container"></div>

        <!-- ══ CONTENT SECTIONS ══════════════════════════════════════════════ -->
        <div class="admin-content-area">

        <?php if($section==='overview'): ?>
        <!-- OVERVIEW ─────────────────────────────────────────────────────── -->
        <div class="admin-section-header">
            <div>
                <h2 class="admin-section-title">Good <?php echo (date('H')<12?'Morning':(date('H')<17?'Afternoon':'Evening')); ?>, <?php echo htmlspecialchars(explode(' ',$_SESSION['admin_name']??'Admin')[0]); ?>! 👋</h2>
                <p class="admin-section-sub">Here's what's happening in Barangay Sto. Rosario today.</p>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openModal('newAnnouncementModal')"><i class="fas fa-plus"></i> Post Announcement</button>
        </div>

        <div class="admin-stats-grid">
            <div class="admin-stat-card"><div class="admin-stat-icon c1"><i class="fas fa-users"></i></div><div class="admin-stat-info"><div class="admin-stat-num"><?php echo number_format($stats['total_users']); ?></div><div class="admin-stat-label">Total Citizens</div></div></div>
            <div class="admin-stat-card" onclick="window.location='?section=users&filter=pending'" style="cursor:pointer;"><div class="admin-stat-icon c2"><i class="fas fa-user-clock"></i></div><div class="admin-stat-info"><div class="admin-stat-num"><?php echo $stats['pending_users']; ?></div><div class="admin-stat-label">Pending Verification</div></div></div>
            <div class="admin-stat-card" onclick="window.location='?section=reports'" style="cursor:pointer;"><div class="admin-stat-icon c3"><i class="fas fa-flag"></i></div><div class="admin-stat-info"><div class="admin-stat-num"><?php echo $stats['total_reports']; ?></div><div class="admin-stat-label">Total Reports</div></div></div>
            <div class="admin-stat-card" onclick="window.location='?section=reports&filter=Pending'" style="cursor:pointer;"><div class="admin-stat-icon c4"><i class="fas fa-exclamation-triangle"></i></div><div class="admin-stat-info"><div class="admin-stat-num"><?php echo $stats['pending_reports']; ?></div><div class="admin-stat-label">Pending Reports</div></div></div>
            <div class="admin-stat-card" onclick="window.location='?section=announcements'" style="cursor:pointer;"><div class="admin-stat-icon c5"><i class="fas fa-bullhorn"></i></div><div class="admin-stat-info"><div class="admin-stat-num"><?php echo $stats['announcements']; ?></div><div class="admin-stat-label">Announcements</div></div></div>
            <div class="admin-stat-card" onclick="window.location='?section=messages'" style="cursor:pointer;"><div class="admin-stat-icon c6"><i class="fas fa-envelope"></i></div><div class="admin-stat-info"><div class="admin-stat-num"><?php echo $stats['messages']; ?></div><div class="admin-stat-label">Unread Messages</div></div></div>
        </div>

        <div class="admin-two-col">
            <!-- Pending Users -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3><i class="fas fa-user-check"></i> Pending User Verifications</h3>
                    <a href="?section=users&filter=pending" class="btn-text">View All</a>
                </div>
                <?php if(!empty($data['pending_users'])): ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead><tr><th>Name</th><th>Email</th><th>Purok</th><th>Registered</th><th>ID</th><th>Actions</th></tr></thead>
                        <tbody>
                        <?php foreach($data['pending_users'] as $u): ?>
                        <tr id="urow-<?php echo $u['user_id']; ?>">
                            <td><strong><?php echo htmlspecialchars($u['first_name'].' '.$u['last_name']); ?></strong></td>
                            <td style="font-size:0.83rem;"><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><?php echo htmlspecialchars($u['purok']??'N/A'); ?></td>
                            <td style="font-size:0.78rem;color:var(--soft-red);"><?php echo date('M d, Y',strtotime($u['created_at'])); ?></td>
                            <td><?php if(!empty($u['id_file'])): ?><a href="<?php echo htmlspecialchars($u['id_file']); ?>" target="_blank" class="btn-text"><i class="fas fa-file-image"></i> View</a><?php else: ?><span style="color:#999;font-size:0.8rem;">No file</span><?php endif; ?></td>
                            <td>
                                <button class="btn-action approve" onclick="updateUser(<?php echo $u['user_id']; ?>,'approve')"><i class="fas fa-check"></i> Approve</button>
                                <button class="btn-action reject" onclick="updateUser(<?php echo $u['user_id']; ?>,'reject')"><i class="fas fa-times"></i> Reject</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="admin-empty"><i class="fas fa-check-circle" style="color:#22c55e;font-size:2.5rem;"></i><p>No pending verifications!</p></div>
                <?php endif; ?>
            </div>

            <!-- Unread Messages -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3><i class="fas fa-envelope"></i> Unread Messages</h3>
                    <a href="?section=messages" class="btn-text">View All</a>
                </div>
                <?php if(!empty($data['recent_messages'])): ?>
                    <?php foreach($data['recent_messages'] as $m): ?>
                    <div class="message-preview" onclick="window.location='?section=messages'">
                        <div class="message-preview-header">
                            <span class="message-preview-name"><?php echo htmlspecialchars($m['name']); ?></span>
                            <span class="message-preview-time"><?php echo date('M d',strtotime($m['created_at'])); ?></span>
                        </div>
                        <div class="message-preview-sub"><?php echo htmlspecialchars($m['subject']); ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <div class="admin-empty"><i class="fas fa-inbox"></i><p>No unread messages</p></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-flag"></i> Recent Reports</h3>
                <a href="?section=reports" class="btn-text">View All</a>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th>Tracking #</th><th>Reporter</th><th>Type</th><th>Title</th><th>Priority</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                    <?php foreach($data['recent_reports'] as $r):
                        $sc = $status_badge($r['status']); ?>
                    <tr>
                        <td><code class="tracking"><?php echo htmlspecialchars($r['tracking_number']); ?></code></td>
                        <td style="font-size:0.83rem;"><?php echo $r['is_anonymous']??false ? '<em style="color:var(--soft-red);">Anonymous</em>' : htmlspecialchars(($r['first_name']??'').' '.($r['last_name']??'')); ?></td>
                        <td><span class="tag"><?php echo htmlspecialchars($r['report_type']); ?></span></td>
                        <td style="font-size:0.85rem;"><?php echo htmlspecialchars(substr($r['title'],0,45)).'...'; ?></td>
                        <td><span class="priority-<?php echo strtolower($r['priority']??'medium'); ?>"><?php echo $r['priority']??'Medium'; ?></span></td>
                        <td><span class="status-badge <?php echo $sc; ?>"><?php echo $r['status']; ?></span></td>
                        <td style="font-size:0.78rem;color:var(--soft-red);"><?php echo date('M d',strtotime($r['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php elseif($section==='users'): ?>
        <!-- USERS ───────────────────────────────────────────────────────── -->
        <div class="admin-section-header">
            <div>
                <h2 class="admin-section-title">User Management</h2>
                <p class="admin-section-sub">All <?php echo count($data['users']); ?> registered citizen accounts.</p>
            </div>
        </div>
        <div class="filter-tabs">
            <?php foreach(['all'=>'All Users','pending'=>'Pending','verified'=>'Verified','rejected'=>'Rejected'] as $k=>$v): ?>
            <a href="?section=users&filter=<?php echo $k; ?>" class="filter-tab <?php echo ($_GET['filter']??'all')===$k?'active':''; ?>"><?php echo $v; ?></a>
            <?php endforeach; ?>
        </div>
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-users"></i> Citizen Accounts</h3>
                <div class="admin-search"><i class="fas fa-search"></i><input type="text" placeholder="Search users..." onkeyup="tableSearch(this,'usersTable')"></div>
            </div>
            <div class="table-responsive">
                <table class="admin-table" id="usersTable">
                    <thead><tr><th>#</th><th>Full Name</th><th>Email</th><th>Contact</th><th>Purok</th><th>Role</th><th>ID File</th><th>Status</th><th>Registered</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php foreach($data['users'] as $u):
                        $vs = $u['verification_status']??'pending';
                        $sc = $status_badge($vs); ?>
                    <tr id="urow-<?php echo $u['user_id']; ?>">
                        <td style="color:var(--soft-red);font-size:0.8rem;">#<?php echo $u['user_id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($u['first_name'].' '.($u['middle_name']??'').' '.$u['last_name']); ?></strong></td>
                        <td style="font-size:0.83rem;"><?php echo htmlspecialchars($u['email']); ?></td>
                        <td style="font-size:0.83rem;"><?php echo htmlspecialchars($u['contact_number']??'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($u['purok']??'N/A'); ?></td>
                        <td><span class="tag"><?php echo ucfirst($u['role']); ?></span></td>
                        <td><?php if(!empty($u['id_file'])): ?><a href="<?php echo htmlspecialchars($u['id_file']); ?>" target="_blank" class="btn-text"><i class="fas fa-eye"></i> View ID</a><?php else: ?><span style="color:#aaa;font-size:0.78rem;">None</span><?php endif; ?></td>
                        <td><span class="status-badge <?php echo $sc; ?>"><?php echo ucfirst($vs); ?></span></td>
                        <td style="font-size:0.78rem;color:var(--soft-red);"><?php echo date('M d, Y',strtotime($u['created_at'])); ?></td>
                        <td>
                            <?php if($vs==='pending'): ?>
                            <button class="btn-action approve" onclick="updateUser(<?php echo $u['user_id']; ?>,'approve')"><i class="fas fa-check"></i></button>
                            <button class="btn-action reject" onclick="updateUser(<?php echo $u['user_id']; ?>,'reject')"><i class="fas fa-times"></i></button>
                            <?php elseif($vs==='verified'): ?>
                            <button class="btn-action reject" onclick="updateUser(<?php echo $u['user_id']; ?>,'reject')" title="Suspend"><i class="fas fa-ban"></i></button>
                            <?php elseif($vs==='rejected'): ?>
                            <button class="btn-action approve" onclick="updateUser(<?php echo $u['user_id']; ?>,'approve')" title="Reactivate"><i class="fas fa-redo"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php elseif($section==='reports'): ?>
        <!-- REPORTS ─────────────────────────────────────────────────────── -->
        <div class="admin-section-header">
            <div>
                <h2 class="admin-section-title">Report Management</h2>
                <p class="admin-section-sub"><?php echo count($data['reports']); ?> total reports — <?php echo $stats['pending_reports']; ?> pending action.</p>
            </div>
        </div>
        <div class="filter-tabs">
            <?php foreach(['all'=>'All','Pending'=>'Pending','Reviewing'=>'Reviewing','In Progress'=>'In Progress','Resolved'=>'Resolved','Closed'=>'Closed'] as $k=>$v): ?>
            <a href="?section=reports&filter=<?php echo urlencode($k); ?>" class="filter-tab <?php echo ($_GET['filter']??'all')===$k?'active':''; ?>"><?php echo $v; ?></a>
            <?php endforeach; ?>
        </div>
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-flag"></i> Community Reports</h3>
                <div class="admin-search"><i class="fas fa-search"></i><input type="text" placeholder="Search reports..." onkeyup="tableSearch(this,'reportsTable')"></div>
            </div>
            <div class="table-responsive">
                <table class="admin-table" id="reportsTable">
                    <thead><tr><th>Tracking #</th><th>Reporter</th><th>Type</th><th>Title / Location</th><th>Priority</th><th>Status</th><th>Filed</th><th>Update Status</th></tr></thead>
                    <tbody>
                    <?php foreach($data['reports'] as $r):
                        $sc = $status_badge($r['status']); ?>
                    <tr id="rrow-<?php echo $r['report_id']; ?>" onclick="viewReport(<?php echo htmlspecialchars(json_encode($r)); ?>)" style="cursor:pointer;">
                        <td><code class="tracking"><?php echo htmlspecialchars($r['tracking_number']); ?></code></td>
                        <td style="font-size:0.83rem;">
                            <?php if($r['is_anonymous']??false): ?><em style="color:var(--soft-red);">Anonymous</em>
                            <?php else: echo htmlspecialchars(trim(($r['first_name']??'Unknown').' '.($r['last_name']??''))); endif; ?>
                        </td>
                        <td><span class="tag"><?php echo htmlspecialchars($r['report_type']); ?></span></td>
                        <td>
                            <div style="font-size:0.88rem;font-weight:600;"><?php echo htmlspecialchars(substr($r['title'],0,40)).(strlen($r['title'])>40?'...':''); ?></div>
                            <div style="font-size:0.75rem;color:var(--soft-red);"><?php echo htmlspecialchars(substr($r['location_description']??'',0,40)); ?></div>
                        </td>
                        <td><span class="priority-<?php echo strtolower($r['priority']??'medium'); ?>"><?php echo $r['priority']??'Medium'; ?></span></td>
                        <td><span class="status-badge <?php echo $sc; ?>"><?php echo $r['status']; ?></span></td>
                        <td style="font-size:0.78rem;color:var(--soft-red);"><?php echo date('M d',strtotime($r['created_at'])); ?></td>
                        <td onclick="event.stopPropagation()">
                            <select class="status-select" onchange="updateReport(<?php echo $r['report_id']; ?>,this.value)">
                                <?php foreach($report_statuses as $st): ?>
                                <option value="<?php echo $st; ?>" <?php echo $r['status']===$st?'selected':''; ?>><?php echo $st; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php elseif($section==='announcements'): ?>
        <!-- ANNOUNCEMENTS ───────────────────────────────────────────────── -->
        <div class="admin-section-header">
            <div>
                <h2 class="admin-section-title">Announcements</h2>
                <p class="admin-section-sub">Manage all barangay announcements and advisories.</p>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openModal('newAnnouncementModal')"><i class="fas fa-plus"></i> New Announcement</button>
        </div>
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-bullhorn"></i> All Announcements (<?php echo count($data['announcements']); ?>)</h3>
                <div class="admin-search"><i class="fas fa-search"></i><input type="text" placeholder="Search..." onkeyup="tableSearch(this,'annTable')"></div>
            </div>
            <div class="table-responsive">
                <table class="admin-table" id="annTable">
                    <thead><tr><th>Title</th><th>Category</th><th>Author</th><th>Pinned</th><th>Archived</th><th>Date</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php foreach($data['announcements'] as $ann): ?>
                    <tr>
                        <td style="font-size:0.88rem;font-weight:600;"><?php echo htmlspecialchars($ann['title']); ?></td>
                        <td><span class="tag"><?php echo htmlspecialchars($ann['category']); ?></span></td>
                        <td style="font-size:0.83rem;"><?php echo htmlspecialchars(($ann['first_name']??'System').' '.($ann['last_name']??'Admin')); ?></td>
                        <td><?php echo $ann['is_pinned']?'<span class="tag" style="background:var(--hover-yellow);color:#92400e;">📌 Yes</span>':'<span style="color:#999;font-size:0.8rem;">No</span>'; ?></td>
                        <td><?php echo $ann['is_archived']?'<span class="tag">Archived</span>':'<span style="color:#22c55e;font-size:0.8rem;">Active</span>'; ?></td>
                        <td style="font-size:0.78rem;color:var(--soft-red);"><?php echo date('M d, Y',strtotime($ann['created_at'])); ?></td>
                        <td>
                            <button class="btn-action reject" onclick="deleteItem('announcement',<?php echo $ann['announcement_id']; ?>,'annrow-<?php echo $ann['announcement_id']; ?>')" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php elseif($section==='services'): ?>
        <!-- SERVICES ────────────────────────────────────────────────────── -->
        <div class="admin-section-header">
            <div>
                <h2 class="admin-section-title">Services Management</h2>
                <p class="admin-section-sub">Manage available barangay services and applications.</p>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openModal('newServiceModal')"><i class="fas fa-plus"></i> Add Service</button>
        </div>
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-cogs"></i> All Services (<?php echo count($data['services']); ?>)</h3>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th>Code</th><th>Service Name</th><th>Category</th><th>Fee</th><th>Processing Time</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php foreach($data['services'] as $svc): ?>
                    <tr>
                        <td><code class="tracking"><?php echo htmlspecialchars($svc['service_code']); ?></code></td>
                        <td style="font-weight:600;font-size:0.88rem;"><?php echo htmlspecialchars($svc['service_name']); ?></td>
                        <td><span class="tag"><?php echo htmlspecialchars($svc['category']); ?></span></td>
                        <td style="font-family:'DM Mono',monospace;"><?php echo $svc['is_free']?'<span style="color:#22c55e;font-weight:700;">FREE</span>':'₱'.number_format($svc['fee'],2); ?></td>
                        <td style="font-size:0.83rem;"><?php echo htmlspecialchars($svc['processing_time']); ?></td>
                        <td><span class="status-badge <?php echo $svc['is_active']?'status-approved':'status-rejected'; ?>"><?php echo $svc['is_active']?'Active':'Inactive'; ?></span></td>
                        <td>
                            <button class="btn-action reject" onclick="deleteItem('service',<?php echo $svc['service_id']; ?>,null)" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php elseif($section==='tourism'): ?>
        <!-- TOURISM ─────────────────────────────────────────────────────── -->
        <div class="admin-section-header">
            <div>
                <h2 class="admin-section-title">Tourism Spots</h2>
                <p class="admin-section-sub">Manage local tourism attractions listed in the public guide.</p>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openModal('newTourismModal')"><i class="fas fa-plus"></i> Add Spot</button>
        </div>
        <div class="admin-card">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th>Icon</th><th>Name</th><th>Category</th><th>Hours</th><th>Fee</th><th>Featured</th><th>Rating</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php foreach($data['tourism'] as $sp): ?>
                    <tr>
                        <td style="font-size:1.5rem;text-align:center;"><?php echo htmlspecialchars($sp['emoji']??'📍'); ?></td>
                        <td style="font-weight:600;font-size:0.88rem;"><?php echo htmlspecialchars($sp['name']); ?></td>
                        <td><span class="tag"><?php echo htmlspecialchars($sp['category']); ?></span></td>
                        <td style="font-size:0.8rem;"><?php echo htmlspecialchars($sp['operating_hours']??'N/A'); ?></td>
                        <td style="font-size:0.8rem;"><?php echo htmlspecialchars($sp['entrance_fee']??'N/A'); ?></td>
                        <td><?php echo $sp['is_featured']?'⭐ Yes':'No'; ?></td>
                        <td><span style="font-family:'DM Mono',monospace;font-weight:700;color:var(--deepest-red);"><?php echo number_format($sp['rating']??0,1); ?> ★</span></td>
                        <td><button class="btn-action reject" onclick="deleteItem('tourism',<?php echo $sp['spot_id']; ?>,null)" title="Delete"><i class="fas fa-trash"></i></button></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php elseif($section==='emergency'): ?>
        <!-- EMERGENCY CONTACTS ──────────────────────────────────────────── -->
        <div class="admin-section-header">
            <div>
                <h2 class="admin-section-title">Emergency Contacts</h2>
                <p class="admin-section-sub">Manage all emergency hotlines displayed on the public emergency page.</p>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openModal('newEmergencyModal')"><i class="fas fa-plus"></i> Add Contact</button>
        </div>
        <div class="admin-card">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th>Priority</th><th>Department</th><th>Agency</th><th>Phone</th><th>Mobile</th><th>Category</th><th>Hours</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php foreach($data['emergency'] as $ec): ?>
                    <tr>
                        <td><span class="priority-<?php echo $ec['priority']==1?'high':($ec['priority']==2?'medium':'low'); ?>">P<?php echo $ec['priority']; ?></span></td>
                        <td style="font-weight:600;font-size:0.85rem;"><?php echo htmlspecialchars($ec['department']); ?></td>
                        <td style="font-size:0.85rem;"><?php echo htmlspecialchars($ec['agency_name']); ?></td>
                        <td><code class="tracking"><?php echo htmlspecialchars($ec['contact_number']); ?></code></td>
                        <td style="font-size:0.83rem;"><?php echo htmlspecialchars($ec['mobile_number']??'—'); ?></td>
                        <td><span class="tag"><?php echo htmlspecialchars($ec['category']); ?></span></td>
                        <td style="font-size:0.78rem;"><?php echo htmlspecialchars($ec['operating_hours']??'24/7'); ?></td>
                        <td><button class="btn-action reject" onclick="deleteItem('emergency',<?php echo $ec['contact_id']; ?>,null)" title="Delete"><i class="fas fa-trash"></i></button></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php elseif($section==='officials'): ?>
        <!-- OFFICIALS ───────────────────────────────────────────────────── -->
        <div class="admin-section-header">
            <div>
                <h2 class="admin-section-title">Barangay Officials</h2>
                <p class="admin-section-sub">Manage officials displayed on the About page.</p>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openModal('newOfficialModal')"><i class="fas fa-plus"></i> Add Official</button>
        </div>
        <div class="admin-card">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th>Order</th><th>Initials</th><th>Full Name</th><th>Position</th><th>Committee</th><th>Contact</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php foreach($data['officials'] as $off): ?>
                    <tr>
                        <td><?php echo $off['sort_order']; ?></td>
                        <td><div class="official-initials"><?php echo htmlspecialchars($off['initials']??'??'); ?></div></td>
                        <td style="font-weight:600;font-size:0.88rem;"><?php echo htmlspecialchars($off['name']); ?></td>
                        <td style="font-size:0.85rem;"><?php echo htmlspecialchars($off['position']); ?></td>
                        <td style="font-size:0.83rem;"><?php echo htmlspecialchars($off['committee']??'—'); ?></td>
                        <td style="font-size:0.83rem;font-family:'DM Mono',monospace;"><?php echo htmlspecialchars($off['contact_number']??'—'); ?></td>
                        <td><button class="btn-action reject" onclick="deleteItem('official',<?php echo $off['official_id']; ?>,null)" title="Delete"><i class="fas fa-trash"></i></button></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php elseif($section==='messages'): ?>
        <!-- MESSAGES ────────────────────────────────────────────────────── -->
        <div class="admin-section-header">
            <div>
                <h2 class="admin-section-title">Messages & Inquiries</h2>
                <p class="admin-section-sub"><?php echo $stats['messages']; ?> unread messages from citizens.</p>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-envelope"></i> All Messages</h3>
                <div class="admin-search"><i class="fas fa-search"></i><input type="text" placeholder="Search messages..." onkeyup="tableSearch(this,'msgTable')"></div>
            </div>
            <div class="table-responsive">
                <table class="admin-table" id="msgTable">
                    <thead><tr><th>Status</th><th>From</th><th>Email</th><th>Contact</th><th>Subject</th><th>Category</th><th>Date</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php foreach($data['messages'] as $m): ?>
                    <tr id="mrow-<?php echo $m['message_id']; ?>" style="<?php echo !$m['is_read']?'background:rgba(255,248,231,0.8);font-weight:600;':''; ?>">
                        <td><?php echo !$m['is_read']?'<span class="status-badge status-pending">Unread</span>':'<span class="status-badge status-approved">Read</span>'; ?></td>
                        <td><?php echo htmlspecialchars($m['name']); ?></td>
                        <td style="font-size:0.8rem;"><?php echo htmlspecialchars($m['email']); ?></td>
                        <td style="font-size:0.8rem;font-family:'DM Mono',monospace;"><?php echo htmlspecialchars($m['contact_number']??'—'); ?></td>
                        <td style="font-size:0.85rem;"><?php echo htmlspecialchars(substr($m['subject'],0,40)).(strlen($m['subject'])>40?'...':''); ?></td>
                        <td><span class="tag"><?php echo htmlspecialchars($m['category']); ?></span></td>
                        <td style="font-size:0.78rem;color:var(--soft-red);"><?php echo date('M d, Y',strtotime($m['created_at'])); ?></td>
                        <td>
                            <button class="btn-action approve" onclick="viewMessage(<?php echo htmlspecialchars(json_encode($m)); ?>)" title="Read & Reply"><i class="fas fa-reply"></i></button>
                            <button class="btn-action reject" onclick="deleteMessage(<?php echo $m['message_id']; ?>)" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php elseif($section==='transparency'): ?>
        <!-- TRANSPARENCY ────────────────────────────────────────────────── -->
        <div class="admin-section-header">
            <div>
                <h2 class="admin-section-title">Budget & Transparency</h2>
                <p class="admin-section-sub">Manage budget items displayed in the public transparency portal.</p>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openModal('newBudgetModal')"><i class="fas fa-plus"></i> Add Budget Item</button>
        </div>
        <?php
        $total_alloc = array_sum(array_column($data['budget'],'allocated_amount'));
        $total_exp   = array_sum(array_column($data['budget'],'expended_amount'));
        ?>
        <div class="admin-stats-grid" style="grid-template-columns:repeat(3,1fr);">
            <div class="admin-stat-card"><div class="admin-stat-icon c1"><i class="fas fa-coins"></i></div><div class="admin-stat-info"><div class="admin-stat-num">₱<?php echo number_format($total_alloc/1000000,2); ?>M</div><div class="admin-stat-label">Total Budget Allocated</div></div></div>
            <div class="admin-stat-card"><div class="admin-stat-icon c3"><i class="fas fa-money-bill-wave"></i></div><div class="admin-stat-info"><div class="admin-stat-num">₱<?php echo number_format($total_exp/1000000,2); ?>M</div><div class="admin-stat-label">Total Expended</div></div></div>
            <div class="admin-stat-card"><div class="admin-stat-icon c5"><i class="fas fa-percent"></i></div><div class="admin-stat-info"><div class="admin-stat-num"><?php echo $total_alloc>0?number_format(($total_exp/$total_alloc)*100,1):0; ?>%</div><div class="admin-stat-label">Utilization Rate</div></div></div>
        </div>
        <div class="admin-card">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th>Year</th><th>Category</th><th>Sub-Category</th><th>Description</th><th>Allocated</th><th>Expended</th><th>% Complete</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php foreach($data['budget'] as $bi): ?>
                    <tr>
                        <td><?php echo $bi['fiscal_year']; ?></td>
                        <td><span class="tag"><?php echo htmlspecialchars($bi['category']); ?></span></td>
                        <td style="font-size:0.8rem;"><?php echo htmlspecialchars($bi['sub_category']??''); ?></td>
                        <td style="font-size:0.85rem;"><?php echo htmlspecialchars(substr($bi['description'],0,45)).'...'; ?></td>
                        <td style="font-family:'DM Mono',monospace;font-weight:600;">₱<?php echo number_format($bi['allocated_amount'],2); ?></td>
                        <td style="font-family:'DM Mono',monospace;">₱<?php echo number_format($bi['expended_amount'],2); ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <div style="flex:1;background:#f0f0f0;border-radius:50px;height:6px;overflow:hidden;"><div style="width:<?php echo $bi['completion_percentage']; ?>%;height:100%;background:var(--primary-gradient);border-radius:50px;"></div></div>
                                <span style="font-size:0.75rem;font-weight:700;color:var(--deepest-red);"><?php echo $bi['completion_percentage']; ?>%</span>
                            </div>
                        </td>
                        <td><span class="status-badge <?php echo $bi['project_status']==='Completed'?'status-approved':($bi['project_status']==='Cancelled'?'status-rejected':'status-progress'); ?>"><?php echo $bi['project_status']; ?></span></td>
                        <td><button class="btn-action reject" onclick="deleteItem('budget',<?php echo $bi['budget_id']; ?>,null)" title="Delete"><i class="fas fa-trash"></i></button></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php else: ?>
        <div class="admin-empty" style="padding:4rem;"><i class="fas fa-tools" style="font-size:3rem;color:var(--soft-red);margin-bottom:1rem;"></i><p>Section "<?php echo htmlspecialchars($section); ?>" loaded successfully.</p></div>
        <?php endif; ?>
        </div> <!-- /admin-content-area -->
    </main>
</div> <!-- /admin-layout -->

<!-- ═══ MODALS ════════════════════════════════════════════════════════════ -->

<!-- New Announcement Modal -->
<div class="admin-modal-overlay" id="newAnnouncementModal" onclick="if(event.target===this)closeModal(this.id)">
    <div class="admin-modal">
        <div class="admin-modal-header"><h3><i class="fas fa-bullhorn"></i> New Announcement</h3><button onclick="closeModal('newAnnouncementModal')"><i class="fas fa-times"></i></button></div>
        <form onsubmit="submitForm(event,'announcement','create')">
            <div class="form-group"><label class="form-label">Title *</label><input type="text" name="title" class="form-input" required placeholder="Announcement title"></div>
            <div class="form-group">
                <label class="form-label">Category *</label>
                <select name="category" class="form-select" required>
                    <?php foreach(['General','Emergency','Events','Health','Infrastructure','Government Services','Public Safety','Environmental','Youth','Senior Citizen'] as $c): ?>
                    <option><?php echo $c; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label class="form-label">Content *</label><textarea name="content" class="form-textarea" rows="6" required placeholder="Full announcement text..."></textarea></div>
            <div class="form-group" style="display:flex;align-items:center;gap:0.8rem;"><input type="checkbox" name="is_pinned" id="is_pinned" style="width:18px;height:18px;accent-color:var(--deepest-red);"><label for="is_pinned" style="cursor:pointer;font-size:0.9rem;color:var(--deepest-red);">📌 Pin this announcement to top</label></div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;"><i class="fas fa-paper-plane"></i> Publish Announcement</button>
        </form>
    </div>
</div>

<!-- New Service Modal -->
<div class="admin-modal-overlay" id="newServiceModal" onclick="if(event.target===this)closeModal(this.id)">
    <div class="admin-modal">
        <div class="admin-modal-header"><h3><i class="fas fa-cogs"></i> Add Service</h3><button onclick="closeModal('newServiceModal')"><i class="fas fa-times"></i></button></div>
        <form onsubmit="submitForm(event,'service','create')">
            <div class="form-group"><label class="form-label">Service Name *</label><input type="text" name="service_name" class="form-input" required placeholder="e.g., Certificate of Residency"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group"><label class="form-label">Category *</label><select name="category" class="form-select" required><?php foreach(['Clearance','Certificate','Permit','Registry','Tax','Other'] as $c): ?><option><?php echo $c; ?></option><?php endforeach; ?></select></div>
                <div class="form-group"><label class="form-label">Fee (₱)</label><input type="number" name="fee" class="form-input" step="0.01" min="0" value="0" placeholder="0 for free"></div>
            </div>
            <div class="form-group"><label class="form-label">Processing Time</label><input type="text" name="processing_time" class="form-input" placeholder="e.g., Same Day"></div>
            <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-textarea" rows="3" placeholder="Brief description of the service"></textarea></div>
            <div class="form-group"><label class="form-label">Requirements</label><textarea name="requirements" class="form-textarea" rows="3" placeholder="List requirements separated by commas"></textarea></div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;"><i class="fas fa-plus"></i> Add Service</button>
        </form>
    </div>
</div>

<!-- New Tourism Modal -->
<div class="admin-modal-overlay" id="newTourismModal" onclick="if(event.target===this)closeModal(this.id)">
    <div class="admin-modal">
        <div class="admin-modal-header"><h3><i class="fas fa-map-marked-alt"></i> Add Tourism Spot</h3><button onclick="closeModal('newTourismModal')"><i class="fas fa-times"></i></button></div>
        <form onsubmit="submitForm(event,'tourism','create')">
            <div class="form-group"><label class="form-label">Name *</label><input type="text" name="name" class="form-input" required placeholder="Spot name"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group"><label class="form-label">Category *</label><select name="category" class="form-select" required><?php foreach(['Historical','Cultural','Religious','Food','Business','Recreation','Other'] as $c): ?><option><?php echo $c; ?></option><?php endforeach; ?></select></div>
                <div class="form-group"><label class="form-label">Emoji Icon</label><input type="text" name="emoji" class="form-input" placeholder="📍" maxlength="5"></div>
            </div>
            <div class="form-group"><label class="form-label">Address</label><input type="text" name="address" class="form-input" placeholder="Street address"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group"><label class="form-label">Operating Hours</label><input type="text" name="operating_hours" class="form-input" placeholder="e.g., Daily 9AM-6PM"></div>
                <div class="form-group"><label class="form-label">Entrance Fee</label><input type="text" name="entrance_fee" class="form-input" placeholder="e.g., Free or ₱50"></div>
            </div>
            <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-textarea" rows="3" placeholder="Full description"></textarea></div>
            <div class="form-group" style="display:flex;align-items:center;gap:0.8rem;"><input type="checkbox" name="is_featured" id="ts_featured" style="width:18px;height:18px;accent-color:var(--deepest-red);"><label for="ts_featured" style="cursor:pointer;font-size:0.9rem;color:var(--deepest-red);">⭐ Feature this spot</label></div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;"><i class="fas fa-plus"></i> Add Tourism Spot</button>
        </form>
    </div>
</div>

<!-- New Emergency Contact Modal -->
<div class="admin-modal-overlay" id="newEmergencyModal" onclick="if(event.target===this)closeModal(this.id)">
    <div class="admin-modal">
        <div class="admin-modal-header"><h3><i class="fas fa-phone-alt"></i> Add Emergency Contact</h3><button onclick="closeModal('newEmergencyModal')"><i class="fas fa-times"></i></button></div>
        <form onsubmit="submitForm(event,'emergency','create')">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group"><label class="form-label">Department *</label><input type="text" name="department" class="form-input" required placeholder="e.g., Police"></div>
                <div class="form-group"><label class="form-label">Agency Name *</label><input type="text" name="agency_name" class="form-input" required placeholder="Full agency name"></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group"><label class="form-label">Phone Number *</label><input type="text" name="contact_number" class="form-input" required placeholder="(045) XXX-XXXX"></div>
                <div class="form-group"><label class="form-label">Mobile Number</label><input type="text" name="mobile_number" class="form-input" placeholder="09XX-XXX-XXXX"></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group"><label class="form-label">Category *</label><select name="category" class="form-select" required><?php foreach(['Police','Fire','Medical','Barangay','Disaster','Other'] as $c): ?><option><?php echo $c; ?></option><?php endforeach; ?></select></div>
                <div class="form-group"><label class="form-label">Priority (1=highest)</label><input type="number" name="priority" class="form-input" value="3" min="1" max="10"></div>
            </div>
            <div class="form-group"><label class="form-label">Operating Hours</label><input type="text" name="operating_hours" class="form-input" placeholder="e.g., 24/7 or Mon-Fri 8AM-5PM"></div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;"><i class="fas fa-plus"></i> Add Contact</button>
        </form>
    </div>
</div>

<!-- New Official Modal -->
<div class="admin-modal-overlay" id="newOfficialModal" onclick="if(event.target===this)closeModal(this.id)">
    <div class="admin-modal">
        <div class="admin-modal-header"><h3><i class="fas fa-user-tie"></i> Add Official</h3><button onclick="closeModal('newOfficialModal')"><i class="fas fa-times"></i></button></div>
        <form onsubmit="submitForm(event,'official','create')">
            <div class="form-group"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-input" required placeholder="Hon. Juan dela Cruz"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group"><label class="form-label">Position *</label><input type="text" name="position" class="form-input" required placeholder="Barangay Captain"></div>
                <div class="form-group"><label class="form-label">Initials *</label><input type="text" name="initials" class="form-input" required placeholder="JD" maxlength="5"></div>
            </div>
            <div class="form-group"><label class="form-label">Committee</label><input type="text" name="committee" class="form-input" placeholder="e.g., Peace & Order"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-input" placeholder="09XX-XXX-XXXX"></div>
                <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-input" value="10" min="1"></div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;"><i class="fas fa-plus"></i> Add Official</button>
        </form>
    </div>
</div>

<!-- New Budget Modal -->
<div class="admin-modal-overlay" id="newBudgetModal" onclick="if(event.target===this)closeModal(this.id)">
    <div class="admin-modal">
        <div class="admin-modal-header"><h3><i class="fas fa-chart-pie"></i> Add Budget Item</h3><button onclick="closeModal('newBudgetModal')"><i class="fas fa-times"></i></button></div>
        <form onsubmit="submitForm(event,'budget','create')">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group"><label class="form-label">Fiscal Year *</label><input type="number" name="fiscal_year" class="form-input" required value="<?php echo date('Y'); ?>" min="2020" max="2030"></div>
                <div class="form-group"><label class="form-label">Project Status</label><select name="project_status" class="form-select"><?php foreach(['Planning','Ongoing','Completed','On Hold','Cancelled'] as $ps): ?><option><?php echo $ps; ?></option><?php endforeach; ?></select></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group"><label class="form-label">Category *</label><input type="text" name="category" class="form-input" required placeholder="e.g., Infrastructure"></div>
                <div class="form-group"><label class="form-label">Sub-Category</label><input type="text" name="sub_category" class="form-input" placeholder="e.g., Road Improvement"></div>
            </div>
            <div class="form-group"><label class="form-label">Description *</label><input type="text" name="description" class="form-input" required placeholder="Project description"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                <div class="form-group"><label class="form-label">Allocated (₱)</label><input type="number" name="allocated_amount" class="form-input" required step="0.01" min="0" placeholder="0.00"></div>
                <div class="form-group"><label class="form-label">Expended (₱)</label><input type="number" name="expended_amount" class="form-input" step="0.01" min="0" value="0" placeholder="0.00"></div>
                <div class="form-group"><label class="form-label">% Complete</label><input type="number" name="completion_percentage" class="form-input" min="0" max="100" value="0" placeholder="0"></div>
            </div>
            <div class="form-group"><label class="form-label">Implementation Period</label><input type="text" name="implementation_period" class="form-input" placeholder="e.g., Jan-Mar 2026"></div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;"><i class="fas fa-plus"></i> Add Budget Item</button>
        </form>
    </div>
</div>

<!-- Report View Modal -->
<div class="admin-modal-overlay" id="reportViewModal" onclick="if(event.target===this)closeModal(this.id)">
    <div class="admin-modal"><div id="reportViewContent"></div></div>
</div>

<!-- Message View Modal -->
<div class="admin-modal-overlay" id="messageViewModal" onclick="if(event.target===this)closeModal(this.id)">
    <div class="admin-modal"><div id="messageViewContent"></div></div>
</div>

<!-- ═══ ADMIN CSS ADDITIONS ═══════════════════════════════════════════════ -->
<style>
/* SIDEBAR */
.admin-layout{display:flex;min-height:calc(100vh - 0px);position:relative;}
.admin-sidebar{width:260px;background:var(--primary-gradient);min-height:100vh;flex-shrink:0;display:flex;flex-direction:column;transition:transform 0.3s cubic-bezier(0.4,0,0.2,1);position:sticky;top:0;height:100vh;overflow-y:auto;}
.admin-sidebar-header{padding:1.2rem 1.2rem 0.8rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid rgba(255,255,255,0.1);}
.admin-sidebar-brand{display:flex;align-items:center;gap:0.8rem;}
.admin-sidebar-logo{width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;color:white;font-size:0.95rem;border:2px solid rgba(255,255,255,0.3);}
.admin-sidebar-title{font-family:'Playfair Display',serif;color:white;font-size:0.95rem;font-weight:700;line-height:1.1;}
.admin-sidebar-sub{color:rgba(255,255,255,0.55);font-size:0.68rem;text-transform:uppercase;letter-spacing:0.1em;}
.admin-sidebar-close{background:none;border:none;color:rgba(255,255,255,0.6);cursor:pointer;font-size:1.1rem;display:none;padding:0.3rem;}
.admin-sidebar-profile{padding:1rem 1.2rem;display:flex;align-items:center;gap:0.8rem;border-bottom:1px solid rgba(255,255,255,0.1);}
.admin-sidebar-avatar{width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1rem;border:2px solid rgba(255,255,255,0.3);}
.admin-nav{flex:1;padding:0.8rem 0;}
.admin-nav-group{margin-bottom:0.5rem;}
.admin-nav-label{padding:0.5rem 1.2rem 0.2rem;font-size:0.65rem;font-weight:700;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.12em;}
.admin-nav-item{display:flex;align-items:center;gap:0.8rem;padding:0.65rem 1.2rem;color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.88rem;font-weight:500;transition:all 0.2s;position:relative;border-left:3px solid transparent;}
.admin-nav-item i{width:18px;text-align:center;font-size:0.9rem;}
.admin-nav-item:hover{background:rgba(255,255,255,0.12);color:white;border-left-color:rgba(255,255,255,0.4);}
.admin-nav-item.active{background:rgba(255,255,255,0.18);color:white;border-left-color:white;font-weight:700;}
.admin-nav-badge{margin-left:auto;background:white;color:var(--deepest-red);font-size:0.65rem;font-weight:700;padding:0.15rem 0.45rem;border-radius:50px;min-width:18px;text-align:center;font-family:'DM Mono',monospace;}
.admin-nav-badge.danger{background:#ef4444;color:white;}
.logout-btn{color:rgba(255,200,200,0.8)!important;}
.logout-btn:hover{background:rgba(255,100,100,0.15)!important;}
/* OVERLAY (mobile) */
.admin-overlay{display:none;position:fixed;inset:0;background:rgba(38,0,0,0.55);z-index:999;}
/* MAIN */
.admin-main{flex:1;display:flex;flex-direction:column;min-width:0;overflow:hidden;}
.admin-topbar{background:white;border-bottom:2px solid rgba(228,172,171,0.3);padding:0.8rem 1.5rem;display:flex;align-items:center;gap:1rem;position:sticky;top:0;z-index:100;box-shadow:0 2px 12px rgba(105,0,14,0.06);}
.admin-hamburger{display:none;background:none;border:none;color:var(--deepest-red);font-size:1.2rem;cursor:pointer;padding:0.3rem;border-radius:6px;transition:background 0.2s;}
.admin-hamburger:hover{background:var(--hover-pink);}
.admin-topbar-title{font-family:'Playfair Display',serif;color:var(--deepest-red);font-size:1rem;font-weight:700;flex:1;}
.admin-topbar-right{display:flex;align-items:center;gap:1rem;}
.admin-topbar-date{font-size:0.78rem;color:var(--soft-red);font-family:'DM Mono',monospace;}
.admin-topbar-logout{color:var(--deepest-red);font-size:1rem;opacity:0.7;transition:opacity 0.2s;} .admin-topbar-logout:hover{opacity:1;}
.admin-content-area{padding:1.5rem;flex:1;}
/* SECTION HEADERS */
.admin-section-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;gap:1rem;flex-wrap:wrap;}
.admin-section-title{font-family:'Playfair Display',serif;color:var(--deepest-red);font-size:1.5rem;margin-bottom:0.2rem;}
.admin-section-sub{color:var(--soft-red);font-size:0.88rem;}
/* STAT GRID */
.admin-stats-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:1rem;margin-bottom:1.5rem;}
.admin-stat-card{background:white;border-radius:14px;padding:1.2rem;display:flex;align-items:center;gap:0.8rem;box-shadow:0 2px 12px rgba(105,0,14,0.07);border:2px solid transparent;transition:all 0.25s;}
.admin-stat-card:hover{border-color:var(--cream-light);box-shadow:0 6px 20px rgba(105,0,14,0.12);}
.admin-stat-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:1.1rem;flex-shrink:0;}
.admin-stat-icon.c1{background:linear-gradient(135deg,#69000E,#A74238);}
.admin-stat-icon.c2{background:linear-gradient(135deg,#f59e0b,#d97706);}
.admin-stat-icon.c3{background:linear-gradient(135deg,#22c55e,#16a34a);}
.admin-stat-icon.c4{background:linear-gradient(135deg,#ef4444,#b91c1c);}
.admin-stat-icon.c5{background:linear-gradient(135deg,#8b5cf6,#7c3aed);}
.admin-stat-icon.c6{background:linear-gradient(135deg,#3b82f6,#1d4ed8);}
.admin-stat-num{font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:var(--deepest-red);line-height:1;}
.admin-stat-label{font-size:0.72rem;color:var(--soft-red);margin-top:0.2rem;}
/* CARDS */
.admin-two-col{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;}
.admin-card{background:white;border-radius:14px;box-shadow:0 2px 12px rgba(105,0,14,0.06);margin-bottom:1.5rem;overflow:hidden;}
.admin-card-header{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.5rem;border-bottom:1px solid rgba(228,172,171,0.2);}
.admin-card-header h3{font-family:'Playfair Display',serif;color:var(--deepest-red);font-size:1rem;display:flex;align-items:center;gap:0.6rem;}
.btn-text{color:var(--medium-red);font-size:0.83rem;font-weight:600;text-decoration:none;border:none;background:none;cursor:pointer;} .btn-text:hover{color:var(--deepest-red);}
/* TABLES */
.table-responsive{overflow-x:auto;}
.admin-table{width:100%;border-collapse:collapse;font-size:0.875rem;}
.admin-table th{background:rgba(255,248,231,0.7);color:var(--deepest-red);font-weight:700;text-transform:uppercase;font-size:0.68rem;letter-spacing:0.08em;padding:0.65rem 1rem;text-align:left;white-space:nowrap;border-bottom:2px solid rgba(228,172,171,0.3);}
.admin-table td{padding:0.7rem 1rem;border-bottom:1px solid rgba(228,172,171,0.15);vertical-align:middle;}
.admin-table tr:hover td{background:rgba(255,248,231,0.5);}
.admin-table tr:last-child td{border-bottom:none;}
/* STATUS & TAGS */
.status-badge{padding:0.2rem 0.7rem;border-radius:50px;font-size:0.72rem;font-weight:700;white-space:nowrap;}
.status-approved{background:#dcfce7;color:#15803d;}
.status-pending{background:#fef9c3;color:#854d0e;}
.status-progress{background:#dbeafe;color:#1d4ed8;}
.status-rejected{background:#fee2e2;color:#b91c1c;}
.tag{background:rgba(228,172,171,0.25);color:var(--deepest-red);padding:0.2rem 0.65rem;border-radius:50px;font-size:0.72rem;font-weight:600;white-space:nowrap;}
.tracking{font-family:'DM Mono',monospace;font-size:0.78rem;font-weight:700;color:var(--deepest-red);background:rgba(228,172,171,0.2);padding:0.15rem 0.5rem;border-radius:4px;}
.priority-high,.priority-urgent{background:#fee2e2;color:#b91c1c;padding:0.15rem 0.6rem;border-radius:50px;font-size:0.72rem;font-weight:700;}
.priority-medium{background:#fef9c3;color:#854d0e;padding:0.15rem 0.6rem;border-radius:50px;font-size:0.72rem;font-weight:700;}
.priority-low{background:#dcfce7;color:#15803d;padding:0.15rem 0.6rem;border-radius:50px;font-size:0.72rem;font-weight:700;}
/* ACTION BUTTONS */
.btn-action{padding:0.3rem 0.65rem;border-radius:7px;border:none;cursor:pointer;font-size:0.78rem;font-weight:600;display:inline-flex;align-items:center;gap:0.3rem;transition:all 0.2s;}
.btn-action.approve{background:#dcfce7;color:#15803d;} .btn-action.approve:hover{background:#bbf7d0;}
.btn-action.reject{background:#fee2e2;color:#b91c1c;} .btn-action.reject:hover{background:#fecaca;}
.status-select{padding:0.3rem 0.5rem;border:1px solid rgba(228,172,171,0.4);border-radius:7px;font-size:0.78rem;color:var(--deepest-red);cursor:pointer;background:white;width:130px;}
/* FILTER TABS */
.filter-tabs{display:flex;gap:0.5rem;margin-bottom:1.5rem;flex-wrap:wrap;}
.filter-tab{padding:0.45rem 1.1rem;border-radius:50px;border:2px solid rgba(228,172,171,0.4);color:var(--deepest-red);font-size:0.83rem;font-weight:600;text-decoration:none;transition:all 0.2s;background:white;}
.filter-tab:hover,.filter-tab.active{background:var(--deepest-red);color:white;border-color:var(--deepest-red);}
/* ADMIN SEARCH */
.admin-search{display:flex;align-items:center;gap:0.5rem;background:rgba(255,248,231,0.7);border:1.5px solid rgba(228,172,171,0.4);border-radius:50px;padding:0.4rem 1rem;max-width:220px;}
.admin-search i{color:var(--soft-red);font-size:0.85rem;}
.admin-search input{border:none;background:none;outline:none;font-size:0.85rem;color:var(--text-primary);width:100%;}
/* OFFICIAL INITIALS */
.official-initials{width:36px;height:36px;border-radius:50%;background:var(--primary-gradient);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.9rem;}
/* MESSAGE PREVIEW */
.message-preview{padding:0.8rem 1rem;border-bottom:1px solid rgba(228,172,171,0.2);cursor:pointer;transition:background 0.2s;} .message-preview:hover{background:var(--hover-peach);}
.message-preview-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:0.2rem;}
.message-preview-name{font-weight:600;font-size:0.88rem;color:var(--deepest-red);}
.message-preview-time{font-size:0.72rem;color:var(--soft-red);}
.message-preview-sub{font-size:0.82rem;color:var(--text-primary);}
/* EMPTY STATE */
.admin-empty{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:3rem;text-align:center;color:var(--soft-red);gap:0.8rem;}
.admin-empty i{font-size:2.5rem;}
/* MODALS */
.admin-modal-overlay{display:none;position:fixed;inset:0;background:rgba(38,0,0,0.55);z-index:1000;align-items:center;justify-content:center;padding:1rem;}
.admin-modal-overlay.active{display:flex;}
.admin-modal{background:white;border-radius:20px;padding:0;width:100%;max-width:560px;max-height:90vh;overflow-y:auto;animation:fadeInUp 0.3s ease;}
.admin-modal-header{display:flex;align-items:center;justify-content:space-between;padding:1.5rem 1.5rem 1rem;border-bottom:1px solid rgba(228,172,171,0.2);}
.admin-modal-header h3{font-family:'Playfair Display',serif;color:var(--deepest-red);font-size:1.2rem;display:flex;align-items:center;gap:0.6rem;}
.admin-modal-header button{background:none;border:none;color:var(--soft-red);cursor:pointer;font-size:1.1rem;padding:0.3rem;border-radius:6px;} .admin-modal-header button:hover{background:var(--hover-pink);}
.admin-modal form{padding:1.5rem;}
/* RESPONSIVE */
@media(max-width:1200px){.admin-stats-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:768px){
    .admin-hamburger{display:flex!important;}
    .admin-sidebar{position:fixed;left:0;top:0;z-index:1000;transform:translateX(-100%);height:100vh;width:260px;}
    .admin-sidebar.open{transform:translateX(0);}
    .admin-sidebar-close{display:block!important;}
    .admin-overlay.active{display:block!important;}
    .admin-main{width:100%;}
    .admin-stats-grid{grid-template-columns:repeat(2,1fr);}
    .admin-two-col{grid-template-columns:1fr;}
    .admin-content-area{padding:1rem;}
    .admin-topbar{padding:0.8rem 1rem;}
    .admin-section-header{flex-direction:column;}
}
@media(max-width:480px){.admin-stats-grid{grid-template-columns:1fr 1fr;}.admin-topbar-date{display:none;}}
</style>

<script>
// SIDEBAR TOGGLE
function toggleSidebar() {
    const sb = document.getElementById('adminSidebar');
    const ov = document.getElementById('adminOverlay');
    const open = sb.classList.toggle('open');
    ov.classList.toggle('active', open);
    document.body.style.overflow = open ? 'hidden' : '';
}
function closeSidebar() {
    document.getElementById('adminSidebar').classList.remove('open');
    document.getElementById('adminOverlay').classList.remove('active');
    document.body.style.overflow = '';
}
// MODAL
function openModal(id) { document.getElementById(id).classList.add('active'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('active'); document.body.style.overflow=''; }
document.addEventListener('keydown', e=>{ if(e.key==='Escape') { document.querySelectorAll('.admin-modal-overlay.active').forEach(m=>m.classList.remove('active')); document.body.style.overflow=''; }});

// TABLE SEARCH
function tableSearch(input, tableId) {
    const q = input.value.toLowerCase();
    document.querySelectorAll('#'+tableId+' tbody tr').forEach(r=>{
        r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}

// TOAST
function showAdminToast(msg, type='success') {
    const c = document.getElementById('toastContainer');
    const t = document.createElement('div');
    t.style.cssText='background:white;padding:0.8rem 1.2rem;border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,0.15);margin-bottom:0.5rem;display:flex;align-items:center;gap:0.8rem;font-size:0.88rem;border-left:4px solid '+(type==='success'?'#22c55e':type==='error'?'#ef4444':'#f59e0b')+';animation:fadeInUp 0.3s ease;max-width:320px;';
    t.innerHTML='<i class="fas '+(type==='success'?'fa-check-circle':type==='error'?'fa-times-circle':'fa-exclamation-triangle')+'" style="color:'+(type==='success'?'#22c55e':'#ef4444')+'"></i><span>'+msg+'</span>';
    c.appendChild(t);
    setTimeout(()=>{ t.style.opacity='0'; t.style.transition='opacity 0.4s'; setTimeout(()=>t.remove(),400); }, 4000);
}

// UPDATE REPORT STATUS
function updateReport(id, status) {
    const fd = new FormData(); fd.append('report_id', id); fd.append('status', status);
    fetch('processes/admin-update-report.php', {method:'POST', body:fd})
    .then(r=>r.json()).then(d=>{ showAdminToast(d.message, d.success?'success':'error'); })
    .catch(()=>showAdminToast('Report status updated (DB offline - change will not persist)', 'error'));
}

// UPDATE USER STATUS
function updateUser(id, action) {
    if(!confirm(`${action==='approve'?'Approve':'Reject'} this user?`)) return;
    const fd = new FormData(); fd.append('user_id', id); fd.append('action', action);
    fetch('processes/admin-update-user.php', {method:'POST', body:fd})
    .then(r=>r.json())
    .then(d=>{
        showAdminToast(d.message, d.success?'success':'error');
        if(d.success) {
            const row = document.getElementById('urow-'+id);
            if(row) { row.style.opacity='0.5'; row.style.transition='opacity 0.5s'; setTimeout(()=>location.reload(),1500); }
        }
    })
    .catch(()=>{ showAdminToast('Action completed (DB offline)', 'error'); });
}

// GENERIC CRUD SUBMIT
function submitForm(event, type, action) {
    event.preventDefault();
    const form = event.target;
    const fd = new FormData(form);
    fd.append('type', type); fd.append('action', action);
    fetch('processes/admin-crud.php', {method:'POST', body:fd})
    .then(r=>r.json())
    .then(d=>{
        showAdminToast(d.message||(d.success?'Success!':'Failed'), d.success?'success':'error');
        if(d.success) { form.reset(); setTimeout(()=>location.reload(), 1000); }
    })
    .catch(()=>{ showAdminToast('Added (DB offline - will not persist)', 'error'); form.reset(); document.querySelectorAll('.admin-modal-overlay.active').forEach(m=>m.classList.remove('active')); });
}

// DELETE ITEM
function deleteItem(type, id, rowId) {
    if(!confirm(`Delete this ${type}?`)) return;
    const fd = new FormData(); fd.append('type', type); fd.append('id', id); fd.append('action', 'delete');
    fetch('processes/admin-crud.php', {method:'POST', body:fd})
    .then(r=>r.json())
    .then(d=>{ showAdminToast(d.message, d.success?'success':'error'); if(d.success) setTimeout(()=>location.reload(),800); })
    .catch(()=>{ showAdminToast('Deleted (DB offline)', 'error'); });
}

// VIEW REPORT DETAIL
function viewReport(r) {
    const sc={'Pending':'status-pending','Reviewing':'status-progress','In Progress':'status-progress','Resolved':'status-approved','Closed':'status-approved','Requires Clarification':'status-pending'};
    const pc={'Low':'#15803d','Medium':'#854d0e','High':'#b91c1c','Urgent':'#b91c1c'};
    document.getElementById('reportViewContent').innerHTML=`
        <div class="admin-modal-header"><h3><i class="fas fa-flag"></i> Report Details</h3><button onclick="closeModal('reportViewModal')"><i class="fas fa-times"></i></button></div>
        <div style="padding:1.5rem;">
            <div style="display:flex;gap:0.8rem;margin-bottom:1.2rem;flex-wrap:wrap;">
                <code class="tracking">${r.tracking_number}</code>
                <span class="status-badge ${sc[r.status]||'status-pending'}">${r.status}</span>
                <span style="background:${pc[r.priority]||'#854d0e'}20;color:${pc[r.priority]||'#854d0e'};padding:0.2rem 0.7rem;border-radius:50px;font-size:0.72rem;font-weight:700;">${r.priority}</span>
            </div>
            <h4 style="font-family:'Playfair Display',serif;color:var(--deepest-red);margin-bottom:1rem;">${r.title}</h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.8rem;margin-bottom:1rem;">
                <div style="background:var(--hover-peach);padding:0.8rem;border-radius:10px;"><div style="font-size:0.7rem;font-weight:700;color:var(--deepest-red);text-transform:uppercase;margin-bottom:0.2rem;">Type</div><div style="font-size:0.88rem;">${r.report_type} — ${r.incident_type}</div></div>
                <div style="background:var(--hover-mint);padding:0.8rem;border-radius:10px;"><div style="font-size:0.7rem;font-weight:700;color:#166534;text-transform:uppercase;margin-bottom:0.2rem;">Location</div><div style="font-size:0.88rem;">${r.location_description}${r.purok?' ('+r.purok+')':''}</div></div>
                <div style="background:var(--hover-lavender);padding:0.8rem;border-radius:10px;"><div style="font-size:0.7rem;font-weight:700;color:var(--deepest-red);text-transform:uppercase;margin-bottom:0.2rem;">Reporter</div><div style="font-size:0.88rem;">${r.is_anonymous?'<em>Anonymous</em>':((r.first_name||'')+(r.last_name?' '+r.last_name:''))||'Unregistered'}</div></div>
                <div style="background:var(--hover-yellow);padding:0.8rem;border-radius:10px;"><div style="font-size:0.7rem;font-weight:700;color:#92400e;text-transform:uppercase;margin-bottom:0.2rem;">Filed On</div><div style="font-size:0.88rem;">${new Date(r.created_at).toLocaleString('en-PH')}</div></div>
            </div>
            <div style="background:rgba(255,248,231,0.7);padding:1rem;border-radius:10px;margin-bottom:1rem;border-left:4px solid var(--soft-red);">
                <div style="font-size:0.7rem;font-weight:700;color:var(--deepest-red);text-transform:uppercase;margin-bottom:0.5rem;">Description</div>
                <p style="font-size:0.9rem;line-height:1.7;">${r.description}</p>
            </div>
            <div><label class="form-label">Update Status</label>
                <select class="status-select" style="width:100%;padding:0.5rem;font-size:0.9rem;" onchange="updateReport(${r.report_id},this.value)">
                    ${['Pending','Reviewing','In Progress','Resolved','Closed','Requires Clarification'].map(s=>`<option value="${s}" ${r.status===s?'selected':''}>${s}</option>`).join('')}
                </select>
            </div>
        </div>`;
    openModal('reportViewModal');
}

// VIEW MESSAGE DETAIL
function viewMessage(m) {
    document.getElementById('messageViewContent').innerHTML=`
        <div class="admin-modal-header"><h3><i class="fas fa-envelope"></i> Message from ${m.name}</h3><button onclick="closeModal('messageViewModal')"><i class="fas fa-times"></i></button></div>
        <div style="padding:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.8rem;margin-bottom:1.2rem;">
                <div style="background:var(--hover-peach);padding:0.8rem;border-radius:10px;"><div style="font-size:0.7rem;font-weight:700;color:var(--deepest-red);text-transform:uppercase;margin-bottom:0.2rem;">Email</div><div style="font-size:0.85rem;">${m.email}</div></div>
                <div style="background:var(--hover-mint);padding:0.8rem;border-radius:10px;"><div style="font-size:0.7rem;font-weight:700;color:#166534;text-transform:uppercase;margin-bottom:0.2rem;">Contact</div><div style="font-size:0.85rem;">${m.contact_number||'—'}</div></div>
            </div>
            <div style="background:rgba(255,248,231,0.7);padding:1rem;border-radius:10px;margin-bottom:1.2rem;border-left:4px solid var(--soft-red);">
                <div style="font-size:0.7rem;font-weight:700;color:var(--deepest-red);text-transform:uppercase;margin-bottom:0.5rem;">${m.subject}</div>
                <p style="font-size:0.9rem;line-height:1.7;">${m.message}</p>
            </div>
            <div class="form-group"><label class="form-label">Admin Reply (saved to record)</label><textarea class="form-textarea" id="replyText" rows="4" placeholder="Type your reply...">${m.reply_message||''}</textarea></div>
            <button class="btn btn-primary" style="width:100%;justify-content:center;" onclick="sendReply(${m.message_id})"><i class="fas fa-reply"></i> Save Reply</button>
        </div>`;
    openModal('messageViewModal');
    // Mark as read
    const fd=new FormData(); fd.append('type','message'); fd.append('action','read'); fd.append('id',m.message_id);
    fetch('processes/admin-crud.php',{method:'POST',body:fd}).catch(()=>{});
}
function sendReply(id) {
    const reply = document.getElementById('replyText')?.value||'';
    const fd=new FormData(); fd.append('type','message'); fd.append('action','reply'); fd.append('id',id); fd.append('reply_message',reply);
    fetch('processes/admin-crud.php',{method:'POST',body:fd}).then(r=>r.json()).then(d=>{ showAdminToast(d.success?'Reply saved!':'Save failed',d.success?'success':'error'); if(d.success) closeModal('messageViewModal'); }).catch(()=>showAdminToast('Reply saved (DB offline)','error'));
}
function deleteMessage(id) {
    if(!confirm('Delete this message?')) return;
    const fd=new FormData(); fd.append('type','message'); fd.append('action','delete'); fd.append('id',id);
    fetch('processes/admin-crud.php',{method:'POST',body:fd}).then(r=>r.json()).then(d=>{ if(d.success){const row=document.getElementById('mrow-'+id);if(row)row.remove();showAdminToast('Message deleted');}}).catch(()=>{});
}
</script>

<?php include 'includes/footer.php'; ?>
