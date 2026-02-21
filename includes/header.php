<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Dynamic base URL - works regardless of folder name
if (!defined('BASE_URL')) {
    $script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME']));
    $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $base_path = str_replace($doc_root, '', $script_dir);
    // If called from a subdirectory (admin/, processes/), go up one level
    $includes_depth = substr_count(str_replace($doc_root, '', str_replace('\\', '/', __DIR__)), '/');
    $script_depth = substr_count($base_path, '/');
    if ($script_depth > $includes_depth) {
        $base_path = dirname($base_path);
    }
    define('BASE_URL', rtrim($base_path, '/'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Barangay Rosario - Angeles City'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
