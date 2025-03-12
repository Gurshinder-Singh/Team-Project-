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

        .footer {
            background-color: #363636;
            color: gold;
            text-align: center;
            padding: 20px;
            width: 100%;
            position: absolute;
            bottom: 0;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .social-icons {
            margin-top: 10px;
        }

        .social-icon {
            color: gold;
            margin: 0 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="gold-shape"></div>
    <div class="luxus-text">LUXUS</div>
    <div class="catchphrase-text">OF THE ESSENCE</div>
    <div class="catchphrase2-text">TIME WAITS FOR NO ONE</div>
    <div class="catchphrase3-text">LET OUR EXPERTS FIND YOUR FIT</div>

    <div class="content">
      
<!-- NAVIGATION BAR -->
<div class="navbar" id="navbar">
    <div class="dropdown">
        <button class="dropbtn">
            <img src="asset/menu_icon.png" alt="Menu Icon" class="menu-icon">
        </button>
        <div class="dropdown-content">
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact Us</a>
            <a href="FAQ.php">FAQs</a>
        </div>
    </div>

    <a href="homepage.php">HOME</a>
    <a href="products_page.php">PRODUCTS</a>

    <div class="navbar-logo">
        <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
    <a href="profile.php">PROFILE</a>
    <a href="previous_orders.php">PREVIOUS ORDERS</a> <!-- NEW LINK -->
    <a href="current_orders.php">CURRENT ORDERS</a> <!-- NEW LINK -->
    <a href="logout.php">LOGOUT</a>
<?php else: ?>
    <a href="login.php">LOGIN</a>
<?php endif; ?>


    <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="admin_page.php">ADMIN</a>
    <?php endif; ?>
</div>
<!-- NAVIGATION BAR END! -->

        </div>

        <script>
            let prevScrollpos = window.pageYOffset;
            let debounce;

            window.onscroll = function() {
                clearTimeout(debounce);

                debounce = setTimeout(function() {
                    let currentScrollPos = window.pageYOffset;
                    if (prevScrollpos > currentScrollPos) {
                        document.getElementById("navbar").style.top = "0";
                    } else {
                        document.getElementById("navbar").style.top = "-50px";
                    }
                    prevScrollpos = currentScrollPos;
                }, 100);
            }
        </script>

        <div id="notification-container" style="position: fixed; bottom: 20px; right: 20px; display: none;">
    <div id="notification" style="background-color: white; border: 1px solid #ccc; padding: 15px; width: 300px; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; align-items: center;">
            <div>
                <h3 id="notification-title" style="margin: 0;"></h3>
                <ul id="notification-message" style="margin: 5px 0 0; padding: 0; list-style: none;">
                    </ul>
            </div>
        </div>
        <button id="close-notification" style="margin-top: 10px;">Close</button>
    </div>
</div>
       <script>
    function showNotification(title, message) {
        document.getElementById('notification-title').textContent = title;
        document.getElementById('notification-message').innerHTML = message;
        document.getElementById('notification-container').style.display = 'block';
    }

    document.getElementById('close-notification').addEventListener('click', function() {
        document.getElementById('notification-container').style.display = 'none';
    });

    <?php if (!empty($lowStockItems)): ?>
        showNotification(
            'Low Stock Alert',
            '<?= implode("<br>", $lowStockItems); ?> - Low Stock! Check Inventory'
        );
    <?php endif; ?>
</script>

    </div> <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2024 LUXUS. All rights reserved.</p>
            <p>Follow us on social media!</p>
            <div class="social-icons">
                <a href="#" class="social-icon">Facebook</a>
                <a href="#" class="social-icon">Instagram</a>
                <a href="#" class="social-icon">Twitter</a>
            </div>
        </div>
    </footer>

</body>
</html>
