<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'db.php';

session_start();

if (!isAuthenticated()) {
    redirect('login.php');
}

$user = getCurrentUser();
$success = '';
$error = '';
$facilities = ['Basketball Court', 'Sound System', 'Multipurpose Hall', 'Barangay Vehicle'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Security validation failed.';
    } else {
        $facility = trim($_POST['facility_type'] ?? '');
        $event = sanitizeInput($_POST['event_name'] ?? '');
        $date = trim($_POST['event_date'] ?? '');
        $start = trim($_POST['start_time'] ?? '');
        $end = trim($_POST['end_time'] ?? '');
        $attendees = intval($_POST['number_of_attendees'] ?? 0);
        $phone = sanitizeInput($_POST['contact_number'] ?? '');
        $purpose = sanitizeInput($_POST['purpose'] ?? '');

        if (empty($facility) || empty($event) || empty($date) || empty($start) || empty($end) || empty($attendees) || empty($phone) || empty($purpose)) {
            $error = 'All fields are required.';
        } elseif (!in_array($facility, $facilities)) {
            $error = 'Invalid facility.';
        } elseif ($attendees < 1) {
            $error = 'Must have at least 1 attendee.';
        } elseif (!isValidPhone($phone)) {
            $error = 'Invalid phone number.';
        } elseif (strtotime($date) < strtotime('today')) {
            $error = 'Date cannot be in the past.';
        } elseif (strtotime($start) >= strtotime($end)) {
            $error = 'End time must be after start time.';
        } else {
            try {
                $sql = "INSERT INTO bookings (user_id, username, facility_type, event_name, event_date, start_time, end_time, number_of_attendees, contact_number, purpose, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user['id'], $user['username'], $facility, $event, $date, $start, $end, $attendees, $phone, $purpose]);
                $success = 'Booking submitted successfully!';
                $_POST = [];
            } catch (PDOException $e) {
                $error = 'Failed to submit. Try again.';
                logError("Booking: " . $e->getMessage());
            }
        }
    }
}

$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Facility - <?php echo APP_NAME; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8f9fa; padding: 20px; }
        .nav { background: linear-gradient(135deg, #2c5a2d 0%, #1e4e22 100%); padding: 16px 24px; color: white; display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
        .nav h2 { margin: 0; font-size: 20px; font-weight: 600; }
        .nav a { color: rgba(255,255,255,0.95); text-decoration: none; margin: 0 12px; font-size: 14px; transition: color 0.3s ease; }
        .nav a:hover { color: #fff; }
        .container { max-width: 700px; margin: 0 auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05); }
        h1 { color: #1e4e22; margin-bottom: 24px; text-align: center; font-size: 26px; font-weight: 600; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #1e4e22; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 12px 14px; border: 1.5px solid #d0d0d0; border-radius: 8px; font-size: 14px; font-family: inherit; transition: all 0.3s ease; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #3e8f15; box-shadow: 0 0 0 3px rgba(62,143,21,0.1); }
        textarea { resize: vertical; min-height: 100px; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        button { width: 100%; padding: 12px; background: linear-gradient(135deg, #3e8f15 0%, #2d6610 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 15px; font-weight: 600; transition: all 0.3s ease; }
        button:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(62,143,21,0.3); }
        .success { background: #efe; color: #263; padding: 14px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #cec; }
        .error { background: #fee; color: #c33; padding: 14px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fcc; }
        .info { background: #eef; color: #036; padding: 14px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ccf; font-size: 14px; }
        .user-info { background: #f8f9fa; padding: 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e9ecef; }
        .user-info p { margin: 8px 0; font-size: 14px; color: #555; }
        .links { text-align: center; margin-top: 24px; }
        .links a { color: #3e8f15; text-decoration: none; margin: 0 12px; font-size: 14px; font-weight: 600; transition: color 0.3s ease; }
        .links a:hover { color: #2d6610; }
        @media (max-width: 600px) { .row { grid-template-columns: 1fr; } }
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
        <h1>Book a Facility</h1>

        <div class="user-info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
        </div>

        <?php if ($success): ?>
            <div class="success">✓ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error">✗ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="info">
            <strong>Note:</strong> Your booking will be reviewed and approved by barangay staff.
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Facility *</label>
                <select name="facility_type" required>
                    <option value="">-- Select --</option>
                    <?php foreach ($facilities as $f): ?>
                        <option value="<?php echo htmlspecialchars($f); ?>"><?php echo htmlspecialchars($f); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Event Name *</label>
                <input type="text" name="event_name" placeholder="e.g., Basketball Tournament" required>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Event Date *</label>
                    <input type="date" name="event_date" required>
                </div>
                <div class="form-group">
                    <label>Attendees *</label>
                    <input type="number" name="number_of_attendees" min="1" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Start Time *</label>
                    <input type="time" name="start_time" required>
                </div>
                <div class="form-group">
                    <label>End Time *</label>
                    <input type="time" name="end_time" required>
                </div>
            </div>

            <div class="form-group">
                <label>Contact Number *</label>
                <input type="tel" name="contact_number" placeholder="09xxxxxxxxx" required>
            </div>

            <div class="form-group">
                <label>Purpose/Details *</label>
                <textarea name="purpose" placeholder="Describe your event..." required></textarea>
            </div>

            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            <button type="submit">Submit Booking</button>
        </form>

        <div class="links">
            <a href="view_bookings.php">My Bookings</a>
            <a href="request.php">Request Certificate</a>
        </div>
    </div>
</body>
</html>
