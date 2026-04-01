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
    <title>About Us - Barangay San Juan</title>
    <link rel="stylesheet" href="Landingpage.css" />
    <style>
        .about-container {
            max-width: 1200px;
            margin: 80px auto 40px;
            padding: 0 20px;
            color: #f8f8f8;
        }

        .about-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .about-header h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #fff;
        }

        .about-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .about-section {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 10px;
            border-left: 5px solid #3e8f15;
        }

        .about-section h2 {
            color: #7dd321;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .about-section p {
            font-size: 1rem;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .about-section ul {
            list-style: none;
            padding-left: 0;
        }

        .about-section li {
            padding: 8px 0 8px 25px;
            position: relative;
        }

        .about-section li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #7dd321;
            font-weight: bold;
        }

        .mission-vision {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .mission-box, .vision-box {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 10px;
            border-top: 3px solid #7dd321;
        }

        .mission-box h3, .vision-box h3 {
            color: #7dd321;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .mission-box p, .vision-box p {
            font-size: 1rem;
            line-height: 1.8;
        }

        .team-section {
            margin-top: 40px;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .team-member {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .team-member h4 {
            color: #7dd321;
            margin: 15px 0 5px 0;
        }

        .team-member p {
            font-size: 0.9rem;
            opacity: 0.85;
            margin-bottom: 5px;
        }

        @media (max-width: 768px) {
            .about-header h1 {
                font-size: 2rem;
            }

            .mission-vision {
                grid-template-columns: 1fr;
            }

            .about-section {
                padding: 20px;
            }
        }
    </style>
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
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="index.php#section2">Announcement</a></li>
                <li><a href="index.php#section3">Online Services</a></li>
                <li><a href="contact.php">Contact</a></li>
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

    <div class="about-container">
        <div class="about-header">
            <h1>About Barangay San Juan</h1>
            <p>Building a Community of Progress and Excellence</p>
        </div>

        <div class="mission-vision">
            <div class="mission-box">
                <h3>Our Mission</h3>
                <p>To serve the people of Barangay San Juan with integrity, transparency, and dedication, providing quality services and programs that promote community development, social welfare, and economic progress for all citizens.</p>
            </div>
            <div class="vision-box">
                <h3>Our Vision</h3>
                <p>A progressive barangay where all residents enjoy peace, prosperity, and equal opportunities for growth. A community united by shared values of respect, cooperation, and commitment to sustainable development.</p>
            </div>
        </div>

        <div class="about-section">
            <h2>Who We Are</h2>
            <p>Barangay San Juan is one of the vibrant communities dedicated to serving its residents with excellence. With a rich history and a commitment to community development, we strive to provide accessible services, maintain peace and order, and foster economic growth for all.</p>
            <p>Our barangay is governed by a dedicated leadership team working together with the community to address local concerns and implement programs that benefit residents of all ages and backgrounds.</p>
        </div>

        <div class="about-section">
            <h2>Our Core Values</h2>
            <ul>
                <li><strong>Integrity</strong> - We uphold honesty and transparency in all our dealings</li>
                <li><strong>Service</strong> - We prioritize the needs and welfare of our residents</li>
                <li><strong>Community</strong> - We foster unity, cooperation, and inclusivity among all</li>
                <li><strong>Progress</strong> - We work towards sustainable development and improvement</li>
                <li><strong>Accountability</strong> - We are responsible to the people we serve</li>
            </ul>
        </div>

        <div class="about-section">
            <h2>What We Offer</h2>
            <p>Our barangay provides a wide range of services and programs designed to support residents:</p>
            <ul>
                <li>Barangay Document Issuance (Clearance, Indigency, Solo Parent Certification)</li>
                <li>Facility Booking Services (Basketball Court, Multipurpose Hall, Sound System, Barangay Vehicle)</li>
                <li>Community Programs and Livelihood Projects</li>
                <li>Peace and Order Maintenance</li>
                <li>Social Welfare and Assistance Programs</li>
                <li>Online Services for Convenient Access</li>
            </ul>
        </div>

        <div class="about-section team-section">
            <h2>Leadership</h2>
            <p style="margin-bottom: 25px;">Our barangay is led by dedicated public servants committed to community development and resident welfare.</p>
            <div class="team-grid">
                <div class="team-member">
                    <h4>Barangay Captain</h4>
                    <p>Chief Executive</p>
                    <p style="font-size: 0.85rem; opacity: 0.7;">Leading the barangay towards progress and development</p>
                </div>
                <div class="team-member">
                    <h4>Barangay Secretary</h4>
                    <p>Administrative Officer</p>
                    <p style="font-size: 0.85rem; opacity: 0.7;">Managing barangay records and documentation</p>
                </div>
                <div class="team-member">
                    <h4>Barangay Treasurer</h4>
                    <p>Financial Officer</p>
                    <p style="font-size: 0.85rem; opacity: 0.7;">Overseeing financial management and budgeting</p>
                </div>
                <div class="team-member">
                    <h4>Barangay Kagawads</h4>
                    <p>Council Members</p>
                    <p style="font-size: 0.85rem; opacity: 0.7;">Supporting community initiatives and programs</p>
                </div>
            </div>
        </div>

        <div class="about-section">
            <h2>Get Involved</h2>
            <p>We believe in community participation and welcome resident involvement in our programs and initiatives. Whether you want to volunteer, attend community events, or provide feedback, we encourage you to be part of our growing community.</p>
            <p>Contact us or visit our office to learn more about how you can contribute to Barangay San Juan's development.</p>
        </div>
    </div>

    <footer style="text-align: center; padding: 30px; background: rgba(0, 0, 0, 0.3); margin-top: 50px; color: #f8f8f8;">
        <p>&copy; 2026 Barangay San Juan. All rights reserved.</p>
        <p style="font-size: 0.9rem; opacity: 0.8;">Serving the community with excellence and dedication</p>
    </footer>
</body>
</html>
