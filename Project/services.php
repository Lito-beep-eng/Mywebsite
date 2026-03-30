<?php
include "db.php";
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_type = trim($_POST['service_type'] ?? '');
    $detail = trim($_POST['detail'] ?? '');
    $name = htmlspecialchars($_SESSION['username']);

    if (!$service_type || !$detail) {
        $error = 'Please choose a service and provide details.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO service_requests (username, service_type, details, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([$name, $service_type, $detail]);
        $success = 'Your request is submitted. We will review it shortly.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Service Request - Barangay San Juan</title>
    <link rel="stylesheet" href="Landingpage.css" />
</head>
<body>
    <div class="section1" style="min-height:auto; padding:80px 20px; text-align:left;">
        <h1>Submit Service Request</h1>
        <?php if ($success): ?><div style="background:#d4f5d2;color:#14650f;padding:12px;border-radius:8px;margin-bottom:12px;"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
        <?php if ($error): ?><div style="background:#ffe0e0;color:#8b1a1a;padding:12px;border-radius:8px;margin-bottom:12px;"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <form action="services.php" method="POST" style="max-width:480px; margin:auto;">
            <label>Service Type
                <select name="service_type" required><option value="">Select service</option><option>Barangay Clearance</option><option>Certificate of Indigency</option><option>Facility Booking</option></select>
            </label>
            <label>Details
                <textarea name="detail" rows="4" style="width:100%;margin:8px 0;padding:10px;border-radius:8px;border:1px solid #b6d8a9;" required></textarea>
            </label>
            <button type="submit">Submit Request</button>
        </form>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
