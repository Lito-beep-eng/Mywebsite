<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? htmlspecialchars($_SESSION['username']) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Barangay San Juan - Home</title>
    <link rel="stylesheet" href="Landingpage.css" />
</head>
<body>
    <header class="nav">
        <div class="brand">
            <img class="b" src="image/b.png" alt="Barangay Banner" />
            <img class="logo" src="image/logo.png" alt="Barangay Logo" />
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#section2">Announcement</a></li>
                <li><a href="index.php#section3">Online Services</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout (<?php echo $username; ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php if ($isLoggedIn): ?>
            <div class="profile-pic"><span>Welcome, <?php echo $username; ?></span></div>
        <?php endif; ?>
    </header>

    <main class="section1">
        <h1>Welcome to Barangay San Juan</h1>
        <p>Access local services online and track your requests from your dashboard.</p>
        <img class="captain" src="image/Barangaycaptain.png" alt="Barangay Captain" />
    </main>

    <section class="section2" id="section2">
        <h3>Announcement</h3>
        <p>We are now accepting online service requests for all barangay documents.</p>
    </section>

    <section class="section3" id="section3">
        <h1 class="serviceh1">Online Services</h1>
        <div class="services">
            <div class="doc">
                <h4>Barangay Documents</h4>
                <ul>
                    <li>Certificate of Indigency</li>
                    <li>Barangay Clearance</li>
                    <li>Solo Parent Certification</li>
                </ul>
            </div>
            <div class="bookingfacilities">
                <h4>Booking Facilities</h4>
                <ul>
                    <li>Barangay Hall</li>
                    <li>Multi-Purpose Hall</li>
                    <li>Covered Court</li>
                </ul>
            </div>
            <div class="requirements">
                <h4>Requirements</h4>
                <ul>
                    <li>Valid ID</li>
                    <li>Old Barangay Clearance</li>
                </ul>
            </div>
        </div>
        <a class="bt" href="services.php">Submit a Service Request</a>
    </section>

    <section class="sec">
        <p>Copyright &copy; 2026 Barangay San Juan. All rights reserved.</p>
    </section>

    <footer class="footer">
        <h2>Contact Us</h2>
        <p>Email: brgy.sanjuan@example.com | Phone: +63 912 345 6789</p>
        <p><a href="contact.php" style="color:#fff; text-decoration:underline;">Send a message</a></p>
    </footer>
</body>
</html>
