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
$cert_types = ['Certificate of Indigency', 'Barangay Clearance', 'Solo Parent Certification', 'Birth Certificate'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Security validation failed.';
    } else {
        $cert_type = trim($_POST['certificate_type'] ?? '');
        $purpose = sanitizeInput($_POST['purpose'] ?? '');

        if (empty($cert_type) || empty($purpose)) {
            $error = 'All fields are required.';
        } elseif (!in_array($cert_type, $cert_types)) {
            $error = 'Invalid certificate type.';
        } else {
            try {
                $sql = "INSERT INTO certificate_requests (user_id, username, certificate_type, fullname, purpose, status) 
                        VALUES (?, ?, ?, ?, ?, 'pending')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user['id'], $user['username'], $cert_type, $user['fullname'], $purpose]);
                $success = 'Request submitted successfully!';
                $_POST = [];
            } catch (PDOException $e) {
                $error = 'Failed to submit. Try again.';
                logError("Cert request: " . $e->getMessage());
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
    <title>Request Certificate - <?php echo APP_NAME; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8f9fa; padding: 20px; }
        .nav { background: linear-gradient(135deg, #2c5a2d 0%, #1e4e22 100%); padding: 16px 24px; color: white; display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
        .nav h2 { margin: 0; font-size: 20px; font-weight: 600; }
        .nav a { color: rgba(255,255,255,0.95); text-decoration: none; margin: 0 12px; font-size: 14px; transition: color 0.3s ease; }
        .nav a:hover { color: #fff; }
        .container { max-width: 680px; margin: 0 auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05); }
        h1 { color: #1e4e22; margin-bottom: 24px; text-align: center; font-size: 26px; font-weight: 600; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #1e4e22; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 12px 14px; border: 1.5px solid #d0d0d0; border-radius: 8px; font-size: 14px; font-family: inherit; transition: all 0.3s ease; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #3e8f15; box-shadow: 0 0 0 3px rgba(62,143,21,0.1); }
        textarea { resize: vertical; min-height: 120px; }
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
        <h1>Request Certificate</h1>

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
            <strong>Note:</strong> Your request will be reviewed by barangay staff. Processing usually takes 2-3 business days.
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Certificate Type *</label>
                <select name="certificate_type" required>
                    <option value="">-- Select --</option>
                    <?php foreach ($cert_types as $type): ?>
                        <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Purpose/Details *</label>
                <textarea name="purpose" required placeholder="Why do you need this certificate?"></textarea>
            </div>

            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            <button type="submit">Submit Request</button>
        </form>

        <div class="links">
            <a href="view_requests.php">View My Requests</a>
            <a href="booking.php">Book Facility</a>
        </div>
    </div>
</body>
</html>
