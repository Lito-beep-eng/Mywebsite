<?php

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function isValidPhone($phone) {
    return preg_match('/^[0-9\-\+\(\) ]{10,}$/', $phone) === 1;
}

function isValidUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username) === 1;
}

function validatePassword($password) {
    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        return [
            'valid' => false,
            'message' => 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters.'
        ];
    }
    return ['valid' => true, 'message' => ''];
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function isValidImageType($mimeType) {
    return in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
}

function generateUniqueFilename($originalName) {
    $pathInfo = pathinfo($originalName);
    $extension = $pathInfo['extension'] ?? '';
    $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $pathInfo['filename'] ?? 'file');
    return $name . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
}

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function redirect($url, $statusCode = 302) {
    header('Location: ' . $url, true, $statusCode);
    exit();
}

function logError($error, $type = 'error') {
    $logFile = __DIR__ . '/logs/error_log.txt';
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }
    error_log("[" . date('Y-m-d H:i:s') . "] [{$type}] {$error}" . PHP_EOL, 3, $logFile);
}

function isAuthenticated() {
    return !empty($_SESSION['username']);
}

function getCurrentUser() {
    if (!isAuthenticated()) return null;
    return [
        'username' => sanitizeInput($_SESSION['username']),
        'role' => $_SESSION['role'] ?? ROLE_USER
    ];
}

function getRoleDisplayName($role) {
    $roles = ['admin' => 'Administrator', 'staff' => 'Staff', 'user' => 'User'];
    return $roles[$role] ?? 'Unknown';
}
?>
