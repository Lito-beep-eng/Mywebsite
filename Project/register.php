<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'db.php';

session_start();

if (isAuthenticated()) {
    redirect('dashboard.php');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrf_token)) {
        $errors[] = 'Security validation failed. Please try again.';
    } else {
        $fullname = sanitizeInput($_POST['fullname'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $address = sanitizeInput($_POST['address'] ?? '');
        $birthdate = trim($_POST['birthdate'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validate fields
        if (empty($fullname) || empty($email) || empty($phone) || empty($address) || empty($birthdate) || empty($gender) || empty($username) || empty($password) || empty($confirm_password)) {
            $errors[] = 'All fields are required.';
        }

        if (!empty($email) && !isValidEmail($email)) {
            $errors[] = 'Invalid email address.';
        }

        if (!empty($phone) && !isValidPhone($phone)) {
            $errors[] = 'Invalid phone number.';
        }

        if (!empty($username) && !isValidUsername($username)) {
            $errors[] = 'Username: 3-20 characters, letters, numbers, underscores only.';
        }

        if (!empty($password)) {
            $passwordCheck = validatePassword($password);
            if (!$passwordCheck['valid']) {
                $errors[] = $passwordCheck['message'];
            }
        }

        if ($password !== $confirm_password) {
            $errors[] = 'Passwords do not match.';
        }

        // Check if username/email exists
        if (empty($errors)) {
            try {
                $checkSql = "SELECT COUNT(*) as count FROM users WHERE username = ? OR email = ?";
                $checkStmt = $pdo->prepare($checkSql);
                $checkStmt->execute([$username, $email]);
                $result = $checkStmt->fetch();
                
                if ($result['count'] > 0) {
                    $errors[] = 'Username or email already exists.';
                }
            } catch (PDOException $e) {
                $errors[] = 'Database error. Please try again later.';
                logError("Registration check error: " . $e->getMessage());
            }
        }

        // Handle file upload if no errors
        $profileImagePath = null;
        if (empty($errors) && !empty($_FILES['profile_image']['name'])) {
            $file = $_FILES['profile_image'];
            
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'File upload error.';
            } elseif ($file['size'] > MAX_UPLOAD_SIZE) {
                $errors[] = 'File too large (max 5MB).';
            } elseif (!isValidImageType($file['type'])) {
                $errors[] = 'Invalid file type. Use JPG, PNG, GIF, or WebP.';
            } else {
                if (!is_dir(UPLOAD_DIR)) {
                    mkdir(UPLOAD_DIR, 0755, true);
                }
                
                $profileImagePath = UPLOAD_DIR . generateUniqueFilename($file['name']);
                
                if (!move_uploaded_file($file['tmp_name'], $profileImagePath)) {
                    $errors[] = 'Failed to upload file.';
                    $profileImagePath = null;
                }
            }
        }

        // Insert user if no errors
        if (empty($errors)) {
            try {
                $hashedPassword = password_hash($password, PASSWORD_HASH_ALGO);
                
                $sql = "INSERT INTO users (fullname, email, phone, address, birthdate, gender, username, password, profile_image, role, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $fullname,
                    $email,
                    $phone,
                    $address,
                    $birthdate,
                    $gender,
                    $username,
                    $hashedPassword,
                    $profileImagePath,
                    ROLE_USER
                ]);
                
                $success = true;
                logError("New user registered: {$username}", 'info');
                
            } catch (PDOException $e) {
                $errors[] = 'Registration failed. Please try again.';
                logError("Registration error: " . $e->getMessage());
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
    <meta name="description" content="Register for <?php echo APP_NAME; ?>">
    <title>Register - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2c5a2d 0%, #1e4e22 100%);
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            padding: 20px;
        }

        .register-container {
            width: min(520px, 95%);
            background: white;
            color: #1b441f;
            border-radius: 14px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(0,0,0,0.06);
        }

        .register-container h1 {
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

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #d0d0d0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3e8f15;
            box-shadow: 0 0 0 3px rgba(62,143,21,0.1);
        }

        .error-message {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error-list {
            list-style: disc;
            margin: 8px 0 0 20px;
        }

        .error-list li {
            margin-bottom: 6px;
            font-size: 14px;
        }

        .success-message {
            background: #efe;
            color: #263;
            border: 1px solid #cec;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success-message p {
            margin: 8px 0;
            font-size: 14px;
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .button-group button,
        .button-group a {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
            font-size: 15px;
            text-decoration: none;
        }

        .button-group button[type="submit"] {
            background: linear-gradient(135deg, #3e8f15 0%, #2d6610 100%);
            color: white;
        }

        .button-group button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(62,143,21,0.3);
        }

        .form-footer {
            margin-top: 20px;
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

        .success-message a {
            display: inline-block;
            background: linear-gradient(135deg, #3e8f15 0%, #2d6610 100%);
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 16px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .success-message a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(62,143,21,0.3);
        }

        @media (max-width: 640px) {
            .register-container {
                padding: 30px 20px;
            }

            .register-container h1 {
                font-size: 22px;
            }

            .button-group {
                flex-direction: column;
            }

            .button-group button,
            .button-group a {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1><?php echo APP_NAME; ?> Registration</h1>

        <?php if (!$success && !empty($errors)): ?>
            <div class="error-message">
                <strong>Error:</strong>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-message">
                <p><strong>Success!</strong></p>
                <p>Your account has been created. You can now log in.</p>
                <a href="login.php">Go to Login</a>
            </div>
        <?php else: ?>
            <form method="POST" action="register.php" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                <div class="form-group">
                    <label for="fullname">Full Name *</label>
                    <input type="text" id="fullname" name="fullname" required autocomplete="name">
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="phone">Phone *</label>
                    <input type="tel" id="phone" name="phone" required autocomplete="tel" placeholder="09123456789">
                </div>

                <div class="form-group">
                    <label for="address">Address *</label>
                    <textarea id="address" name="address" required rows="2" autocomplete="street-address"></textarea>
                </div>

                <div class="form-group">
                    <label for="birthdate">Birthdate *</label>
                    <input type="date" id="birthdate" name="birthdate" required autocomplete="bday">
                </div>

                <div class="form-group">
                    <label for="gender">Gender *</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" required autocomplete="username" placeholder="3-20 characters">
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="At least 8 characters">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password">
                </div>

                <div class="form-group">
                    <label for="profile_image">Profile Picture</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*">
                    <small style="color: #666;">Max 5MB. JPG, PNG, GIF, WebP</small>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; margin-top: 20px;">Create Account</button>
            </form>

            <div class="form-footer">
                <p>Already have an account? <a href="login.php">Login here</a></p>
                <p><a href="index.php">Back to Home</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
