<?php
/**
 * Database Connection
 * 
 * Establishes PDO connection with professional error handling
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/utils.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => false
        ]
    );
    
} catch (PDOException $e) {
    // Log error securely
    logError("Database connection failed: " . $e->getMessage());
    
    // Show user-friendly error message
    http_response_code(500);
    die("<h1>Service Unavailable</h1><p>We're experiencing technical difficulties. Please try again later.</p>");
}
?>