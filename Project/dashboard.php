<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'db.php';

session_start();

if (!isAuthenticated()) {
    redirect('login.php');
}

if (time() - ($_SESSION['login_time'] ?? 0) > SESSION_TIMEOUT) {
    session_destroy();
    redirect('login.php?session_expired=1');
}

$user = getCurrentUser();
$username = htmlspecialchars($user['username']);
$role = htmlspecialchars($user['role']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        body { padding-top: 80px; }
        .dashboard { width: min(1200px, 94%); margin: 40px auto; padding: 0 20px; }
        .header { background: rgba(255, 255, 255, 0.1); border-radius: 8px; padding: 30px; margin-bottom: 30px; }
        .header h1 { margin: 0 0 10px; }
        .header p { margin: 10px 0; opacity: 0.9; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 30px 0; }
        .card { background: rgba(255, 255, 255, 0.1); border-radius: 8px; padding: 20px; text-align: center; border: 1px solid rgba(255, 255, 255, 0.2); transition: 0.3s; }
        .card:hover { transform: translateY(-4px); background: rgba(255, 255, 255, 0.15); }
        .card h3 { margin: 0 0 10px; }
        .card a { display: inline-block; margin-top: 12px; padding: 8px 16px; background: linear-gradient(120deg, #3e8f15, #74bf33); color: white; border-radius: 6px; font-weight: 600; transition: 0.2s; }
        .card a:hover { opacity: 0.9; }
        @media (max-width: 768px) { .cards { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header class="nav">
        <div class="brand">
            <img src="image/b.png" alt="<?php echo APP_NAME; ?>" style="height: 48px;">
            <img src="image/logo.png" alt="Logo" style="height: 48px;">
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php" class="btn-login">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard">
        <div class="header">
            <h1>Welcome, <?php echo $username; ?>!</h1>
            <p>Role: <strong><?php echo getRoleDisplayName($role); ?></strong></p>
        </div>

        <div class="cards">
            <div class="card">
                <h3>📄 Documents</h3>
                <p>Request barangay certificates</p>
                <a href="#">Request</a>
            </div>
            <div class="card">
                <h3>🏛️ Facilities</h3>
                <p>Book barangay facilities</p>
                <a href="#">Book</a>
            </div>
            <div class="card">
                <h3>📋 Requests</h3>
                <p>View your requests</p>
                <a href="#">View</a>
            </div>
            <div class="card">
                <h3>👤 Profile</h3>
                <p>Update account info</p>
                <a href="#">Edit</a>
            </div>
            <div class="card">
                <h3>🔐 Security</h3>
                <p>Change password</p>
                <a href="#">Change</a>
            </div>
            <div class="card">
                <h3>🚪 Sign Out</h3>
                <p>Logout from account</p>
                <a href="logout.php" style="background: #d62e2e;">Logout</a>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2026 <?php echo APP_NAME; ?>. All rights reserved.</p>
    </footer>
</body>
</html>