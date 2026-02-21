<?php
/**
 * Database Connection File
 * Uses PDO for secure database operations with prepared statements
 * File: includes/db-connection.php
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'barangay_rosario');
define('DB_USER', 'root');  // Change to your MySQL username
define('DB_PASS', '');      // Change to your MySQL password
define('DB_CHARSET', 'utf8mb4');

// PDO options for secure connections
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                   // Use real prepared statements
];

// Create PDO connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Log error but don't show details to users
    error_log("Database Connection Error: " . $e->getMessage());
    $pdo = null; // Set to null for fallback handling
}

/**
 * Helper function to execute queries with parameters
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters to bind
 * @return array|false|int
 */
function dbQuery($sql, $params = []) {
    global $pdo;
    
    if (!$pdo) {
        error_log("Database connection not available");
        return [];
    }
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Query Error: " . $e->getMessage());
        return [];
    }
}

/**
 * Helper function to fetch single row
 * @param string $sql SQL query
 * @param array $params Parameters
 * @return array|null
 */
function dbOne($sql, $params = []) {
    global $pdo;
    
    if (!$pdo) {
        error_log("Database connection not available");
        return null;
    }
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    } catch (PDOException $e) {
        error_log("Query Error: " . $e->getMessage());
        return null;
    }
}

/**
 * Helper function to insert data and return last insert ID
 * @param string $sql SQL query
 * @param array $params Parameters
 * @return int Last insert ID or 0 on failure
 */
function dbInsert($sql, $params = []) {
    global $pdo;
    
    if (!$pdo) {
        error_log("Database connection not available");
        return 0;
    }
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Insert Error: " . $e->getMessage());
        return 0;
    }
}

/**
 * Helper function to execute queries that don't return data (UPDATE, DELETE)
 * @param string $sql SQL query
 * @param array $params Parameters
 * @return bool Success or failure
 */
function dbExec($sql, $params = []) {
    global $pdo;
    
    if (!$pdo) {
        error_log("Database connection not available");
        return false;
    }
    
    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        error_log("Execute Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Helper function to count rows
 * @param string $sql SQL query (should be a COUNT query)
 * @param array $params Parameters
 * @return int Count result or 0 on failure
 */
function dbCount($sql, $params = []) {
    global $pdo;
    
    if (!$pdo) {
        error_log("Database connection not available");
        return 0;
    }
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Count Error: " . $e->getMessage());
        return 0;
    }
}

/**
 * Legacy function names for backward compatibility
 */
function executeQuery($pdo, $sql, $params = []) {
    return dbExec($sql, $params);
}

function fetchOne($pdo, $sql, $params = []) {
    return dbOne($sql, $params);
}

function fetchAll($pdo, $sql, $params = []) {
    return dbQuery($sql, $params);
}
?>