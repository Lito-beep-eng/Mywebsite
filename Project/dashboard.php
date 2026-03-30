<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$fullname = htmlspecialchars($_SESSION['username']);
$role = htmlspecialchars($_SESSION['role'] ?? 'user');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Barangay San Juan</title>
    <style>
        body {margin:0; font-family:'Segoe UI',sans-serif; background:#f0f4f2; color:#193a1b;}
        .container {max-width:900px; margin: 40px auto; padding:24px; background:#fff; border-radius:12px; box-shadow:0 12px 24px rgba(0,0,0,0.12);}
        h1 {margin:0 0 12px;}
        .info {margin-top:20px; background:#e5f0df; border:1px solid #b8d5ae; border-radius:10px; padding:14px;}
        .logout {display:inline-block; margin-top:18px; padding:8px 14px; background:#d62e2e; color:#fff; text-decoration:none; border-radius:8px;}
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $fullname; ?>!</h1>
        <p>Role: <?php echo $role; ?></p>
        <div class="info">
            <p>This is your protected dashboard. Add admin links or user pages here.</p>
            <ul>
                <li>View service requests</li>
                <li>Submit document requests</li>
                <li>Manage profile</li>
            </ul>
        </div>
        <a class="logout" href="logout.php">Logout</a>
    </div>
</body>
</html>