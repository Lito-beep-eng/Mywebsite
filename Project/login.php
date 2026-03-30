<?php
include "db.php";
session_start();

$error = '';
$username = $_COOKIE['remembered_username'] ?? '';
$remember = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $sql = "SELECT username, password, role FROM users WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($remember) {
                setcookie('remembered_username', $username, time() + (86400 * 30), '/');
            } else {
                setcookie('remembered_username', '', time() - 3600, '/');
            }

            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Barangay San Juan</title>
    <style>
        body {margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #18511f 0%, #0c3f16 100%); color:#f8f8f8; font-family:'Segoe UI',sans-serif;}
        .login-card {width:min(420px,96%); background:rgba(255,255,255,0.95); color:#1b441f; border-radius:14px; padding:24px; box-shadow:0 14px 34px rgba(0,0,0,0.35);}
        .login-card h1 {margin:0 0 18px; font-size:1.6rem;}
        .login-card input {width:100%; padding:10px 12px; margin:8px 0; border:1px solid #b6d8a9; border-radius:8px;}
        .login-card button {width:100%; margin-top:12px; padding:10px 14px; border:none; border-radius:8px; background:linear-gradient(120deg, #3e8f15, #74bf33); color:#fff; font-weight:700; cursor:pointer;}
        .login-card a {color:#105b0f; text-decoration:underline;}
        .error {background:#ffeded; color:#9b1e2e; border:1px solid #e1a5a5; padding:10px 12px; border-radius:8px; margin-bottom:12px;}
        .toggle-password {margin-top: -34px; margin-bottom: 10px; float: right; background:none; border:none; color:#06530b; font-weight:700; cursor:pointer;}
        @media (max-width: 640px){ .login-card {padding:18px;} }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>Barangay San Juan Login</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php" novalidate>
            <label>
                Username
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </label>
            <label style="display:block; position:relative;">
                Password
                <input type="password" name="password" id="password" required>
                <button type="button" class="toggle-password" id="togglePassword">Show</button>
            </label>
            <label style="display:flex; align-items:center; margin:8px 0;">
                <input type="checkbox" name="remember" value="1" <?php echo $remember ? 'checked' : ''; ?> style="margin-right:8px;">
                Remember me
            </label>
            <button type="submit">Login</button>
        </form>
        <p style="margin-top:14px;">Don't have an account? <a href="index.html">Go to Register</a></p>
    </div>

    <script>
        const passwordField = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');

        toggleBtn.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });
    </script>
</body>
</html>
