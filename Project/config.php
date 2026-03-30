<?php
/**
 * Application Configuration File
 * 
 * Contains all application constants and configuration settings
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'project');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application Settings
define('APP_NAME', 'Barangay San Juan');
define('APP_URL', 'http://localhost/website/Project');
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_UPLOAD_SIZE', 5242880); // 5MB in bytes

// Security Settings
define('SESSION_TIMEOUT', 1800); // 30 minutes in seconds
define('REMEMBER_DURATION', 86400 * 30); // 30 days in seconds
define('PASSWORD_MIN_LENGTH', 8);
define('PASSWORD_HASH_ALGO', PASSWORD_DEFAULT);

// Regular Expressions for validation
define('EMAIL_PATTERN', '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/');
define('PHONE_PATTERN', '/^[0-9\-\+\(\) ]{10,}$/');
define('USERNAME_PATTERN', '/^[a-zA-Z0-9_]{3,20}$/');

// User Roles
define('ROLE_ADMIN', 'admin');
define('ROLE_STAFF', 'staff');
define('ROLE_USER', 'user');

// Error Messages
define('ERROR_MESSAGES', [
    'empty_fields' => 'All fields are required.',
    'invalid_email' => 'Please enter a valid email address.',
    'invalid_phone' => 'Please enter a valid phone number.',
    'invalid_username' => 'Username must be 3-20 characters (letters, numbers, underscores only).',
    'password_mismatch' => 'Passwords do not match.',
    'password_too_short' => 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters.',
    'username_exists' => 'Username already exists.',
    'email_exists' => 'Email already exists.',
    'invalid_credentials' => 'Invalid username or password.',
    'file_upload_error' => 'Error uploading file.',
    'file_too_large' => 'File size exceeds ' . (MAX_UPLOAD_SIZE / 1048576) . 'MB limit.',
    'invalid_file_type' => 'Invalid file type.',
    'db_error' => 'An error occurred. Please try again later.',
    'session_expired' => 'Your session has expired. Please login again.',
]);

// Success Messages
define('SUCCESS_MESSAGES', [
    'login_success' => 'Login successful! Redirecting...',
    'registration_success' => 'Registration successful! You can now login.',
    'logout_success' => 'You have been logged out successfully.',
    'profile_updated' => 'Profile updated successfully.',
]);
?>
