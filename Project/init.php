<?php
/**
 * Database Initialization Script
 * 
 * Run this script once to set up the database with all required tables
 * Access it via: http://localhost/website/Project/init.php
 */

require_once 'config.php';

try {
    // Read the database initialization SQL file
    $sql_file = __DIR__ . '/db_init.sql';
    
    if (!file_exists($sql_file)) {
        die("Error: db_init.sql file not found!");
    }
    
    $sql = file_get_contents($sql_file);
    
    // Create PDO connection (without specifying database initially)
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    // Execute the SQL file
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "<style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; border-radius: 6px; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; padding: 15px; border-radius: 6px; margin-top: 20px; }
        h1 { color: #1e4e22; }
        a { color: #3e8f15; text-decoration: none; font-weight: 600; }
        a:hover { text-decoration: underline; }
    </style>";
    
    echo "<h1>✓ Database Initialization Successful!</h1>";
    echo "<div class='success'>";
    echo "<p><strong>The following tables have been created:</strong></p>";
    echo "<ul>";
    echo "<li><strong>users</strong> - User accounts with personal information</li>";
    echo "<li><strong>certificate_requests</strong> - Certificate request tracking</li>";
    echo "<li><strong>bookings</strong> - Facility booking requests</li>";
    echo "<li><strong>service_requests</strong> - Service request tracking</li>";
    echo "<li><strong>contact_messages</strong> - Contact form submissions</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h2>Next Steps:</h2>";
    echo "<p>1. Delete or rename this init.php file for security</p>";
    echo "<p>2. <a href='register.php'>Create a user account</a> to get started</p>";
    echo "<p>3. <a href='login.php'>Login</a> to access the system</p>";
    echo "<p><strong>Admin Setup:</strong> To create an admin user, update the user record directly in the database and set the role to 'admin'</p>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .error { background: #ffeded; color: #9b1e2e; border: 1px solid #e1a5a5; padding: 15px; border-radius: 6px; }
        h1 { color: #d62e2e; }
    </style>";
    
    echo "<h1>✗ Database Initialization Failed</h1>";
    echo "<div class='error'>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Database Host:</strong> " . DB_HOST . "</p>";
    echo "<p><strong>Database Name:</strong> " . DB_NAME . "</p>";
    echo "<p>Please check your database credentials in config.php</p>";
    echo "</div>";
}
?>
