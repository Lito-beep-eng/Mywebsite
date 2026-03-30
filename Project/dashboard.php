<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'db.php';

session_start();

if (!isAuthenticated()) {
    redirect('login.php');
}

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo APP_NAME; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8f9fa; padding: 20px; }
        .nav { background: linear-gradient(135deg, #2c5a2d 0%, #1e4e22 100%); padding: 16px 24px; color: white; display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
        .nav h2 { margin: 0; font-size: 20px; font-weight: 600; }
        .nav a { color: rgba(255,255,255,0.95); text-decoration: none; margin: 0 12px; font-size: 14px; transition: color 0.3s ease; }
        .nav a:hover { color: #fff; text-shadow: 0 0 8px rgba(255,255,255,0.3); }
        .container { max-width: 1100px; margin: 0 auto; }
        .header { background: white; padding: 32px; border-radius: 12px; margin-bottom: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05); }
        .header h1 { color: #1e4e22; margin-bottom: 8px; font-size: 28px; font-weight: 600; }
        .header p { color: #6c757d; margin: 0; font-size: 15px; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; }
        .card { background: white; padding: 24px; text-align: center; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05); }
        .card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
        .card h3 { color: #1e4e22; margin-bottom: 10px; font-size: 18px; font-weight: 600; }
        .card a { display: inline-block; margin-top: 16px; padding: 10px 20px; background: linear-gradient(135deg, #3e8f15 0%, #2d6610 100%); color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.3s ease; }
        .card a:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(62,143,21,0.3); }
        .admin { background: linear-gradient(135deg, #f0f8ec 0%, #e8f5e3 100%); border-left: 4px solid #3e8f15; }
        .footer { text-align: center; margin-top: 40px; color: #9ca3af; font-size: 13px; }
    </style>
</head>
<body>
    <div class="nav">
        <h2><?php echo APP_NAME; ?></h2>
        <div>
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h1>Welcome, <?php echo htmlspecialchars($user['fullname']); ?>!</h1>
            <p>Role: <strong><?php echo ucfirst($user['role']); ?></strong></p>
        </div>

        <div class="cards">
            <div class="card">
                <h3>📄 Request Certificate</h3>
                <p style="font-size: 13px; color: #666; margin: 8px 0;">Submit Indigency, Clearance, etc.</p>
                <a href="request.php">Request</a>
            </div>

            <div class="card">
                <h3>🏛️ Book Facility</h3>
                <p style="font-size: 13px; color: #666; margin: 8px 0;">Book court, hall, vehicle</p>
                <a href="booking.php">Book Now</a>
            </div>

            <div class="card">
                <h3>📋 My Requests</h3>
                <p style="font-size: 13px; color: #666; margin: 8px 0;">Track certificate status</p>
                <a href="view_requests.php">View</a>
            </div>

            <div class="card">
                <h3>📅 My Bookings</h3>
                <p style="font-size: 13px; color: #666; margin: 8px 0;">Track booking status</p>
                <a href="view_bookings.php">View</a>
            </div>

            <?php if (in_array($user['role'], ['admin', 'staff'])): ?>
            <div class="card admin">
                <h3>⚙️ Requests</h3>
                <p style="font-size: 13px; color: #666; margin: 8px 0;">Manage requests</p>
                <a href="admin_requests.php">Manage</a>
            </div>

            <div class="card admin">
                <h3>⚙️ Bookings</h3>
                <p style="font-size: 13px; color: #666; margin: 8px 0;">Manage bookings</p>
                <a href="admin_bookings.php">Manage</a>
            </div>
            <?php endif; ?>

            <div class="card">
                <h3>🚪 Sign Out</h3>
                <p style="font-size: 13px; color: #666; margin: 8px 0;">Logout account</p>
                <a href="logout.php" style="background: #dc3545;">Logout</a>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2026 <?php echo APP_NAME; ?>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>