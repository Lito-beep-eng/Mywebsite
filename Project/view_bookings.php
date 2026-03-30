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
    $sql = "SELECT * FROM bookings WHERE user_id = ? ORDER BY event_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user['id']]);
    $bookings = $stmt->fetchAll();
} catch (PDOException $e) {
    logError("Error: " . $e->getMessage());
    $bookings = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - <?php echo APP_NAME; ?></title>
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
        .cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }
        .card { background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
        .card h3 { color: #1e4e22; margin-bottom: 8px; font-size: 18px; font-weight: 600; }
        .card .facility { font-size: 16px; font-weight: 600; color: #3e8f15; margin-bottom: 12px; }
        .card p { margin: 8px 0; font-size: 14px; color: #555; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 20px; color: white; font-size: 11px; font-weight: 600; margin-top: 12px; }
        .pending { background: #ffc107; color: #333; }
        .approved { background: #28a745; }
        .completed { background: #17a2b8; }
        .cancelled { background: #dc3545; }
        .empty { background: white; padding: 60px 20px; text-align: center; border-radius: 12px; border: 1px solid rgba(0,0,0,0.05); }
        .empty h2 { color: #1e4e22; margin-bottom: 8px; }
        .empty p { color: #666; margin: 8px 0; }
        .empty a { color: #3e8f15; text-decoration: none; font-weight: 600; }
        .links { text-align: center; margin-top: 28px; }
        .links a { color: #3e8f15; text-decoration: none; margin: 0 12px; font-size: 14px; font-weight: 600; transition: color 0.3s ease; }
        .links a:hover { color: #2d6610; }
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
        <h1>My Facility Bookings</h1>
        <div class="header-info">
            <p>Manage your facility bookings</p>
            <a href="booking.php">+ New Booking</a>
        </div>

        <?php if (empty($bookings)): ?>
            <div class="empty">
                <h2>No Bookings Yet</h2>
                <p>You haven't made any facility bookings.</p>
                <p><a href="booking.php">Make your first booking →</a></p>
            </div>
        <?php else: ?>
            <div class="cards">
                <?php foreach ($bookings as $b): ?>
                <div class="card">
                    <div class="facility"><?php echo htmlspecialchars($b['facility_type']); ?></div>
                    <h3><?php echo htmlspecialchars($b['event_name']); ?></h3>
                    
                    <p><strong>📅 Date:</strong> <?php echo date('M d, Y', strtotime($b['event_date'])); ?></p>
                    <p><strong>🕐 Time:</strong> <?php echo date('g:i A', strtotime($b['start_time'])); ?> - <?php echo date('g:i A', strtotime($b['end_time'])); ?></p>
                    <p><strong>👥 Attendees:</strong> <?php echo htmlspecialchars($b['number_of_attendees']); ?></p>
                    <p><strong>📞 Contact:</strong> <?php echo htmlspecialchars($b['contact_number']); ?></p>
                    
                    <span class="badge <?php echo htmlspecialchars($b['status']); ?>"><?php echo ucfirst($b['status']); ?></span>
                    
                    <?php if ($b['remarks']): ?>
                        <p style="margin-top: 10px; background: #f9f9f9; padding: 8px; border-radius: 3px; font-size: 12px;">
                            <strong>Remarks:</strong> <?php echo htmlspecialchars($b['remarks']); ?>
                        </p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="links">
            <a href="booking.php">New Booking</a>
            <a href="view_requests.php">My Requests</a>
            <a href="dashboard.php">Dashboard</a>
        </div>
    </div>
</body>
</html>
