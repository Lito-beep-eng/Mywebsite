<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'db.php';

session_start();

if (!isAuthenticated()) {
    redirect('login.php');
}

$user = getCurrentUser();

try {
    $sql = "SELECT * FROM certificate_requests WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user['id']]);
    $requests = $stmt->fetchAll();
} catch (PDOException $e) {
    logError("Error: " . $e->getMessage());
    $requests = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests - <?php echo APP_NAME; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8f9fa; padding: 20px; }
        .nav { background: linear-gradient(135deg, #2c5a2d 0%, #1e4e22 100%); padding: 16px 24px; color: white; display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
        .nav h2 { margin: 0; font-size: 20px; font-weight: 600; }
        .nav a { color: rgba(255,255,255,0.95); text-decoration: none; margin: 0 12px; font-size: 14px; transition: color 0.3s ease; }
        .nav a:hover { color: #fff; }
        .container { max-width: 1100px; margin: 0 auto; }
        h1 { color: #1e4e22; text-align: center; margin-bottom: 12px; font-size: 26px; font-weight: 600; }
        .header-info { text-align: center; margin-bottom: 28px; }
        .header-info p { color: #666; margin-bottom: 12px; }
        .header-info a { color: white; text-decoration: none; font-weight: 600; padding: 10px 20px; background: linear-gradient(135deg, #3e8f15 0%, #2d6610 100%); border-radius: 8px; display: inline-block; transition: all 0.3s ease; }
        .header-info a:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(62,143,21,0.3); }
        table { width: 100%; background: white; border-collapse: collapse; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 12px; overflow: hidden; border: 1px solid rgba(0,0,0,0.05); }
        th { background: linear-gradient(135deg, #2c5a2d 0%, #1e4e22 100%); color: white; padding: 16px; text-align: left; font-weight: 600; font-size: 14px; }
        td { padding: 14px 16px; border-bottom: 1px solid #e9ecef; font-size: 14px; }
        tr:hover { background: #fafbfc; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 20px; color: white; font-size: 12px; font-weight: 600; }
        .pending { background: #ffc107; color: #333; }
        .approved { background: #28a745; }
        .completed { background: #17a2b8; }
        .rejected { background: #dc3545; }
        .empty { background: white; padding: 60px 20px; text-align: center; border-radius: 12px; border: 1px solid rgba(0,0,0,0.05); }
        .empty h2 { color: #1e4e22; margin-bottom: 8px; }
        .empty p { color: #666; margin: 8px 0; }
        .empty a { color: #3e8f15; text-decoration: none; font-weight: 600; }
        .links { text-align: center; margin-top: 28px; }
        .links a { color: #3e8f15; text-decoration: none; margin: 0 12px; font-size: 14px; font-weight: 600; transition: color 0.3s ease; }
        .links a:hover { color: #2d6610; }
    </style>
        .empty { background: white; padding: 40px; text-align: center; border-radius: 5px; }
        .empty a { color: #3e8f15; text-decoration: none; font-weight: bold; }
        .links { text-align: center; margin-top: 20px; }
        .links a { color: #3e8f15; text-decoration: none; margin: 0 10px; }
    </style>
</head>
<body>
    <div class="nav">
        <h2><?php echo APP_NAME; ?></h2>
        <div>
            <a href="index.php">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h1>My Certificate Requests</h1>
        <div class="header-info">
            <p>Track the status of your requests</p>
            <a href="request.php">+ New Request</a>
        </div>

        <?php if (empty($requests)): ?>
            <div class="empty">
                <h2>No Requests Yet</h2>
                <p>You haven't submitted any requests.</p>
                <p><a href="request.php">Submit your first request →</a></p>
            </div>
        <?php else: ?>
            <table>
                <tr>
                    <th>Certificate Type</th>
                    <th>Date Requested</th>
                    <th>Status</th>
                    <th>Purpose</th>
                </tr>
                <?php foreach ($requests as $r): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($r['certificate_type']); ?></strong></td>
                    <td><?php echo date('M d, Y', strtotime($r['created_at'])); ?></td>
                    <td><span class="badge <?php echo htmlspecialchars($r['status']); ?>"><?php echo ucfirst($r['status']); ?></span></td>
                    <td><?php echo htmlspecialchars(substr($r['purpose'], 0, 50)) . (strlen($r['purpose']) > 50 ? '...' : ''); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <div class="links">
            <a href="request.php">New Request</a>
            <a href="view_bookings.php">My Bookings</a>
            <a href="dashboard.php">Dashboard</a>
        </div>
    </div>
</body>
</html>
