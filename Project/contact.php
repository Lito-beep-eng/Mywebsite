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
    <div class="section1" style="min-height:auto; padding:80px 20px; text-align:left;">
        <h1>Contact Us</h1>
        <?php if ($sent): ?><div style="background:#d4f5d2;color:#14650f;padding:12px;border-radius:8px;margin-bottom:12px;"><?php echo htmlspecialchars($sent); ?></div><?php endif; ?>
        <?php if ($error): ?><div style="background:#ffe0e0;color:#8b1a1a;padding:12px;border-radius:8px;margin-bottom:12px;"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <form action="contact.php" method="POST" style="max-width:480px; margin:auto;">
            <label>Name
                <input type="text" name="name" required style="width:100%;padding:10px;margin:8px 0;border-radius:8px;border:1px solid #b6d8a9;" />
            </label>
            <label>Email
                <input type="email" name="email" required style="width:100%;padding:10px;margin:8px 0;border-radius:8px;border:1px solid #b6d8a9;" />
            </label>
            <label>Message
                <textarea name="message" rows="5" required style="width:100%;padding:10px;margin:8px 0;border-radius:8px;border:1px solid #b6d8a9;"></textarea>
            </label>
            <button type="submit">Send Message</button>
        </form>
        <p><a href="index.php">Return to Home</a></p>
    </div>
</body>
</html>
