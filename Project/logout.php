<?php
/**
 * Logout Handler
 * 
 * Securely destroys user session
 */

require_once 'config.php';
require_once 'utils.php';

session_start();

// Log the logout
if (!empty($_SESSION['username'])) {
    logError("User '{$_SESSION['username']}' logged out", 'info');
}

// Clear all session variables
$_SESSION = [];

// Destroy session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Destroy the session
session_destroy();

// Clear remember me cookie
setcookie('remembered_username', '', time() - 3600, '/', '', false, true);

// Redirect to login page with success message
header('Location: login.php?logout=success');
exit;
?>