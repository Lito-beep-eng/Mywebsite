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
        body {
            padding-top: 80px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-container {
            width: min(500px, 95%);
            background: rgba(255, 255, 255, 0.95);
            color: #1b441f;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
            margin: 20px;
        }

        .register-container h1 {
            margin: 0 0 24px;
            font-size: 1.6rem;
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
            color: #1b441f;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #b6d8a9;
            border-radius: 6px;
            font-size: 0.95rem;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3e8f15;
            box-shadow: 0 0 8px rgba(62, 143, 21, 0.3);
        }

        .error-message {
            background: #ffeded;
            color: #9b1e2e;
            border: 1px solid #e1a5a5;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 16px;
        }

        .error-list {
            list-style: disc;
            margin: 8px 0 0 20px;
        }

        .error-list li {
            margin-bottom: 4px;
        }

        .success-message {
            background: #e1f5e1;
            color: #1e4e22;
            border: 1px solid #a5e1a5;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 16px;
            text-align: center;
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .button-group button,
        .button-group a {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            transition: 0.2s;
        }

        .button-group button[type="submit"] {
            background: linear-gradient(120deg, #3e8f15, #74bf33);
            color: white;
        }

        .button-group button[type="submit"]:hover {
            opacity: 0.9;
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

        .success-message a {
            display: inline-block;
            background: linear-gradient(120deg, #3e8f15, #74bf33);
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 12px;
        }

        @media (max-width: 640px) {
            .register-container {
                padding: 20px;
            }

            .register-container h1 {
                font-size: 1.3rem;
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
