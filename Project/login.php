<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'db.php';

session_start();

if (isAuthenticated()) {
    redirect('dashboard.php');
}

$error = '';
$username = '';
$remember = false;

if (isset($_COOKIE['remembered_username'])) {
    $username = sanitizeInput($_COOKIE['remembered_username']);
    $remember = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrf_token)) {
        $error = 'Security validation failed.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']) && $_POST['remember'] === '1';

        if (empty($username) || empty($password)) {
            $error = 'Please enter username and password.';
        } else {
            try {
                $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['login_time'] = time();

                    if ($remember) {
                        setcookie('remembered_username', $user['username'], time() + REMEMBER_DURATION, '/', '', false, true);
                    } else {
                        setcookie('remembered_username', '', time() - 3600, '/', '', false, true);
                    }

                    redirect('dashboard.php');
                } else {
                    $error = 'Invalid username or password.';
                }
            } catch (PDOException $e) {
                $error = 'Login failed. Please try again.';
                logError("Login error: " . $e->getMessage());
            }
        }
    }
}

$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Login to <?php echo APP_NAME; ?>">
    <title>Login - <?php echo APP_NAME; ?></title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #18511f 0%, #0c3f16 100%);
            color: #f8f8f8;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-container {
            width: min(400px, 95%);
            background: rgba(255, 255, 255, 0.95);
            color: #1b441f;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
        }

        .login-container h1 {
            margin: 0 0 24px;
            font-size: 1.5rem;
            color: #1e4e22;
            text-align: center;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #b6d8a9;
            border-radius: 6px;
            font-size: 0.95rem;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3e8f15;
            box-shadow: 0 0 8px rgba(62, 143, 21, 0.3);
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 16px;
            border: none;
            border-radius: 6px;
            background: linear-gradient(120deg, #3e8f15, #74bf33);
            color: white;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            opacity: 0.9;
        }

        .error-message {
            background: #ffeded;
            color: #9b1e2e;
            border: 1px solid #e1a5a5;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 16px;
            text-align: center;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin: 12px 0;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin: 0 8px 0 0;
        }

        .form-footer {
            margin-top: 16px;
            text-align: center;
            font-size: 0.9rem;
        }

        .form-footer a {
            color: #105b0f;
            font-weight: 600;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 20px;
            }

            .login-container h1 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1><?php echo APP_NAME; ?> Login</h1>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            
            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username"
                    name="username" 
                    value="<?php echo htmlspecialchars($username); ?>" 
                    required
                    autocomplete="username"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password"
                    name="password" 
                    required
                    autocomplete="current-password"
                >
            </div>

            <div class="checkbox-group">
                <input 
                    type="checkbox" 
                    id="remember" 
                    name="remember" 
                    value="1"
                    <?php echo $remember ? 'checked' : ''; ?>
                >
                <label for="remember" style="margin: 0;">Remember me</label>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="form-footer">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
            <p><a href="index.html">Back to Home</a></p>
        </div>
    </div>
</body>
</html>
