<?php
include "db.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $birthdate = trim($_POST['birthdate'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($fullname) || empty($email) || empty($phone) || empty($address) || empty($birthdate) || empty($gender) || empty($username) || empty($password) || empty($confirm_password)) {
        die("All fields are required.");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    $checkSql = "SELECT COUNT(*) FROM users WHERE username = ? OR email = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$username, $email]);
    if ($checkStmt->fetchColumn() > 0) {
        die("Username or Email already exists.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $profileImagePath = null;

    if (!empty($_FILES['profile_image']['name'])) {
        $fileName = basename($_FILES['profile_image']['name']);
        $profileImagePath = $uploadDir . uniqid('profile_') . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $profileImagePath);
    }

    $sql = "INSERT INTO users (fullname, email, phone, address, birthdate, gender, username, password, profile_image, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$fullname, $email, $phone, $address, $birthdate, $gender, $username, $hashedPassword, $profileImagePath, 'user']);

    echo "Registration successful! <a href='index.html'>Click here to login</a>";
}
?>
