<?php
session_start();
require_once '../includes/db-connection.php';
require_once '../includes/session-check.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: ../report.php"); exit(); }

$title         = sanitizeInput($_POST['title'] ?? '');
$description   = sanitizeInput($_POST['description'] ?? '');
$report_type   = $_POST['report_type'] ?? 'Other';
$incident_type = sanitizeInput($_POST['incident_type'] ?? $report_type);
$location      = sanitizeInput($_POST['location_description'] ?? '');
$purok         = sanitizeInput($_POST['purok'] ?? '');
$is_anonymous  = isset($_POST['is_anonymous']) ? 1 : 0;
$reporter_name = sanitizeInput($_POST['reporter_name'] ?? '');
$reporter_phone= sanitizeInput($_POST['reporter_phone'] ?? '');
$priority      = in_array($_POST['priority']??'Medium',['Low','Medium','High','Urgent']) ? $_POST['priority'] : 'Medium';

if (!$title || !$description || !$location) { header("Location: ../report.php?error=Please+fill+in+all+required+fields."); exit(); }

$tracking = 'RPT-'.date('Y').'-'.str_pad(rand(1,99999),5,'0',STR_PAD_LEFT);
$evidence_path = null;
if (!empty($_FILES['evidence']['tmp_name'])) {
    $allowed = ['image/jpeg','image/png','image/gif'];
    if (in_array($_FILES['evidence']['type'],$allowed) && $_FILES['evidence']['size']<=5*1024*1024) {
        $dir = '../assets/images/uploads/reports/';
        if (!is_dir($dir)) mkdir($dir,0755,true);
        $ext = pathinfo($_FILES['evidence']['name'],PATHINFO_EXTENSION);
        $fn  = 'rpt_'.time().'_'.bin2hex(random_bytes(4)).'.'.$ext;
        move_uploaded_file($_FILES['evidence']['tmp_name'],$dir.$fn);
        $evidence_path = 'assets/images/uploads/reports/'.$fn;
    }
}

$user_id = isLoggedIn() ? getUserId() : null;
if ($pdo) {
    dbInsert("INSERT INTO reports (tracking_number,user_id,report_type,incident_type,title,description,location_description,purok,evidence_paths,is_anonymous,reporter_name,reporter_phone,priority,status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,'$priority','Pending')",
        [$tracking,$user_id,$report_type,$incident_type,$title,$description,$location,$purok,$evidence_path,$is_anonymous,$reporter_name,$reporter_phone]);
    logActivity($pdo,'Report Submitted','Tracking: '.$tracking);
}
header("Location: ../report.php?success=Report+submitted!&tracking=".urlencode($tracking));
exit();
