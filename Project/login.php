<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'db.php';

session_start();

if (isAuthenticated()) {
    redirect('dashboard.php');
}

$error = '';
$success = '';
$username = '';
$remember = false;

// Check for logout success
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $success = 'You have been logged out successfully.';
}

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
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2c5a2d 0%, #1e4e22 100%);
            color: #f8f8f8;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .login-container {
            width: min(420px, 95%);
            background: white;
            color: #1b441f;
            border-radius: 14px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(0,0,0,0.06);
        }
        .login-container h1 {
            margin: 0 0 28px;
            font-size: 26px;
            color: #1e4e22;
            text-align: center;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1e4e22;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #d0d0d0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        .form-group input:focus {
            outline: none;
            border-color: #3e8f15;
            box-shadow: 0 0 0 3px rgba(62,143,21,0.1);
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 24px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #3e8f15 0%, #2d6610 100%);
            color: white;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(62,143,21,0.3);
        }
        .error-message {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }
        .success-message {
            background: #efe;
            color: #263;
            border: 1px solid #cec;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin: 20px 0;
            gap: 8px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #3e8f15;
        }

        .checkbox-group label {
            margin: 0;
            font-size: 14px;
            cursor: pointer;
            color: #555;
            font-weight: 500;
        }

        .form-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 14px;
        }

        .form-footer a {
            color: #3e8f15;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .form-footer a:hover {
            color: #2d6610;
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
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

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
