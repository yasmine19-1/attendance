<?php
// backend/db.php
// Single, safe PDO connection file.
// Usage: require_once __DIR__ . '/db.php'; then use $pdo or call getConnection().

if (!defined('DB_CONFIG_DONE')) {
    define('DB_CONFIG_DONE', true);

    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');   // change if needed
    define('DB_NAME', 'attendance_system');

    function getConnection() {
        static $instance = null;
        if ($instance !== null) return $instance;

        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $instance = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $instance;
    }

    // also export $pdo for convenience
    try {
        $pdo = getConnection();
    } catch (Exception $e) {
        // log then show friendly message
        file_put_contents(__DIR__ . '/db_errors.log', "[" . date('c') . "] " . $e->getMessage() . PHP_EOL, FILE_APPEND);
        die("Database connection failed. Check backend/db.php settings.");
    }
}
