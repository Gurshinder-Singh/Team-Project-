<?php
session_start();
require 'db.php';

try {
    $sql = "SELECT product_id, name, stock FROM products";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}

$lowStockItems = [];
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    foreach ($products as $product) {
        if ($product['stock'] < 5) {
            $lowStockItems[] = htmlspecialchars($product['name']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LUXUS</title>
    <link rel="icon" type="image/favicon" href="asset/LUXUS_logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            position: relative;
        }

        .content {
            flex: 1;
            position: relative;
        }

        .navbar {
            height: 75px;
            display: flex;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            background-color: #363636;
            transition: top 0.3s ease-in-out;
            will-change: transform;
            z-index: 100;
        }

        .navbar a,
        .navbar-logo {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            flex: 1;
            text-align: center;
            transform: translateX(-100px);
        }

        .navbar-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            max-width: 200px;
        }

        .navbar-logo img {
            height: 95px;
            width: auto;
            margin: 0 auto;
        }

        .dropdown {
            position: relative;
            display: inline-block;
            flex: 1;
        }

        .dropbtn {
            background-color: #363636;
            color: white;
            padding: 14px 20px;
            width: 70px;
            height: 70px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-icon {
            height: 50px;
            width: auto;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #363636;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            transition: transform 0.3s ease-in-out;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
            transform: translateX(0);
            transition: transform 0.3s ease-in-out;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
            color: black;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .image-container {
            display: flex;
            justify-content: center;
            position: absolute;
        }

        .image-container img {
            width: 400px;
            height: auto;
            position: absolute;
            left: 1000px;
            top: 200px;
        }

        .video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            z-index: -1;
        }

        .video-container video {
            position: absolute;
            top: 50%;
            left: 50%;
            width: auto;
            min-width: 100%;
            min-height: 100%;
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        .page-container {
            position: relative;
            min-height: 100vh;
        }

        .content1 {
            position: relative;
            min-height: 100vh;
            z-index: 1;
            padding-bottom: 200px;
        }

        #notification-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            z-index: 200;
        }

        #notification-container.show {
            display: block;
            opacity: 1;
        }
    </style>
</head>
<body>
<div class="page-container">
    <div class="content1">
        <div class="luxus-text">LUXUS</div>
        <div class="catchphrase-text">OF THE ESSENCE</div>
        <div class="catchphrase2-text">TIME WAITS FOR NO ONE</div>
        <div class="catchphrase3-text">LET OUR EXPERTS FIND YOUR FIT</div>
        <div class="video-container">
            <video autoplay loop muted>
                <source src="asset/hero.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

    <div class="content">
    <!-- NAVIGATION BAR -->

        <div class="navbar" id="navbar">
            <div class="dropdown">
                <button class="dropbtn">
                    <img src="asset/menu_icon.png" alt="Menu Icon" class="menu-icon">
                </button>
                <div class="dropdown-content">
                    <a href="about.php"><i class="fas fa-info-circle"></i> About Us</a>
                    <a href="contact.php"><i class="fas fa-envelope"></i> Contact Us</a>
                    <a href="FAQ.php"><i class="fas fa-question-circle"></i> FAQs</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="returns.php"><i class="fas fa-undo-alt"></i> Returns</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
                    <?php endif; ?>
                    <a href="javascript:void(0);" id="darkModeToggle">
                        <i class="fas fa-moon"></i> <span>Dark Mode</span>
                    </a>
                </div>
            </div>
            <a href="homepage.php"><i class="fas fa-home"></i> HOME</a>
            <a href="products_page.php"><i class="fas fa-box-open"></i> PRODUCTS</a>
            <div class="navbar-logo">
                <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
            </div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php"><i class="fas fa-user"></i> PROFILE</a>
            <?php elseif (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="admin_page.php"><i class="fas fa-user-shield"></i> ADMIN</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
            <?php else: ?>
                <a href="login.php"><i class="fas fa-sign-in-alt"></i> LOGIN</a>
            <?php endif; ?>
            <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
                <a href="cart.php"><i class="fas fa-shopping-basket"></i> BASKET</a>
            <?php endif; ?>
        </div>
            
    <!-- NOTIFICATION -->
        <div id="notification-container">
            <div id="notification" style="background-color: white; border: 1px solid #ccc; padding: 15px; width: 300px; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);">
                <div style="display: flex; align-items: center;">
                    <div>
                        <h3 id="notification-title" style="margin: 0; color: red"></h3>
                        <ul id="notification-message" style="margin: 5px 0 0; padding: 0; list-style: none; color: black"></ul>
                    </div>
                </div>
                <button id="close-notification" style="margin-top: 10px;">Close</button>
            </div>
        </div>

        <script>
            function showNotification(title, message) {
                const container = document.getElementById('notification-container');
                document.getElementById('notification-title').textContent = title;
                document.getElementById('notification-message').innerHTML = '<li>' + message.split("<br>").join('</li><li>') + '</li>';
                container.classList.add('show');
            }

            document.getElementById('close-notification').addEventListener('click', function () {
                document.getElementById('notification-container').classList.remove('show');
            });

            window.addEventListener('DOMContentLoaded', function () {
                <?php if (!empty($lowStockItems)): ?>
                showNotification(
                    'Low Stock Alert',
                    '<?= implode("<br>", $lowStockItems); ?> - Low Stock! Check Inventory'
                );
                <?php endif; ?>
            });
        </script>
        
    <!-- DARKMODE -->
        <script>
            const darkModeToggle = document.getElementById('darkModeToggle');
            const body = document.body;
            const darkModeIcon = document.querySelector('#darkModeToggle i');
            const darkModeText = darkModeToggle.querySelector('span');
            const savedTheme = localStorage.getItem('theme');

            if (savedTheme === 'dark') {
                body.classList.add('dark-mode');
                darkModeIcon.classList.remove('fa-moon');
                darkModeIcon.classList.add('fa-sun');
                darkModeText.textContent = 'Light Mode';
            }

            darkModeToggle.addEventListener('click', () => {
                body.classList.toggle('dark-mode');

                if (body.classList.contains('dark-mode')) {
                    localStorage.setItem('theme', 'dark');
                    darkModeIcon.classList.remove('fa-moon');
                    darkModeIcon.classList.add('fa-sun');
                    darkModeText.textContent = 'Light Mode';
                } else {
                    localStorage.setItem('theme', 'light');
                    darkModeIcon.classList.remove('fa-sun');
                    darkModeIcon.classList.add('fa-moon');
                    darkModeText.textContent = 'Dark Mode';
                }
            });
        </script>
        
    <!-- FOOTER -->
        <footer style="
            background-color: #2c2c2c;
            color: white;
            padding: 10px 15px;
            text-align: center;
            font-size: 13px;
            margin-top: auto;
            position: relative;
            width: 100%;
            z-index: 2;
        ">
            <div style="margin-bottom: 10px; font-size: 18px;">
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-facebook-f"></i></a>
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-twitter"></i></a>
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-instagram"></i></a>
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <p style="margin: 0;">&copy; <?= date("Y") ?> LUXUS. All rights reserved.</p>
        </footer>
    </div>
</div>
</body>
</html>
