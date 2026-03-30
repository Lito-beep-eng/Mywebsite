<?php
include "db.php";

$sent = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $error = 'Please fill out all fields.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([$name, $email, $message]);
        $sent = 'Thank you for contacting us. We will get back to you soon.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us - Barangay San Juan</title>
    <link rel="stylesheet" href="Landingpage.css" />
</head>
<body>
    <div class="section1" style="min-height:auto; padding:100px 20px; text-align:left; background: white;">
        <div class="container" style="max-width: 700px;">
            <h1 style="color: #1e4e22; font-size: 28px; font-weight: 600; margin-bottom: 30px; text-align: center;">Contact Us</h1>
            
            <div style="background: #f8f9fa; padding: 24px; border-radius: 12px; margin-bottom: 30px; border: 1px solid #e9ecef;">
                <h3 style="color: #1e4e22; font-size: 18px; font-weight: 600; margin-bottom: 16px;">Barangay Office Information</h3>
                <p style="margin: 12px 0; color: #555; font-size: 15px;"><strong style="color: #1e4e22;">Address:</strong> 1920 Tanchoco Ave, Taytay, 1920 Metro Manila</p>
                <p style="margin: 12px 0; color: #555; font-size: 15px;"><strong style="color: #1e4e22;">Phone:</strong> 0998 220 5844</p>
                <p style="margin: 12px 0; color: #555; font-size: 15px;"><strong style="color: #1e4e22;">Email:</strong> brgy.sanjuan@example.com</p>
            </div>
            
            <?php if ($sent): ?><div style="background:#efe;color:#263;padding:14px;border-radius:8px;margin-bottom:20px;border: 1px solid #cec; font-size: 14px;">✓ <?php echo htmlspecialchars($sent); ?></div><?php endif; ?>
            <?php if ($error): ?><div style="background:#fee;color:#c33;padding:14px;border-radius:8px;margin-bottom:20px;border: 1px solid #fcc; font-size: 14px;">✗ <?php echo htmlspecialchars($error); ?></div><?php endif; ?>
            
            <h3 style="color: #1e4e22; font-size: 18px; font-weight: 600; margin-bottom: 20px; margin-top: 20px;">Send us a Message</h3>
            <form action="contact.php" method="POST">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e4e22; font-size: 14px;">Name</label>
                    <input type="text" name="name" required style="width:100%;padding:12px 14px;border:1.5px solid #d0d0d0;border-radius:8px;font-size:14px;font-family:inherit;" />
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e4e22; font-size: 14px;">Email</label>
                    <input type="email" name="email" required style="width:100%;padding:12px 14px;border:1.5px solid #d0d0d0;border-radius:8px;font-size:14px;font-family:inherit;" />
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e4e22; font-size: 14px;">Message</label>
                    <textarea name="message" rows="6" required style="width:100%;padding:12px 14px;border:1.5px solid #d0d0d0;border-radius:8px;font-size:14px;font-family:inherit;"></textarea>
                </div>
                <button type="submit" style="width:100%;padding:12px;background:linear-gradient(135deg, #3e8f15 0%, #2d6610 100%);color:white;border:none;border-radius:8px;cursor:pointer;font-size:15px;font-weight:600;transition:all 0.3s ease;">Send Message</button>
            </form>
            <p style="margin-top: 20px; text-align: center;"><a href="index.php" style="color:#3e8f15;text-decoration:none;font-weight:600;">← Return to Home</a></p>
        </div>
    </div>
</body>
</html>
