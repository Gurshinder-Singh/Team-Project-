<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUXUS - Admin Panel</title>
    <link rel="icon" type="image/png" href="/asset/LUXUS_logo.png"> 
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css"> 
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #333;
            padding: 10px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            transition: background 0.3s;
        }
        .navbar a:hover {
            background: #555;
            border-radius: 5px;
        }
        .navbar-logo img {
            height: 40px;
        }
        .navbar-links {
            display: flex;
            gap: 15px;
        }
    </style>
</head>
<body>
    <div class="navbar" id="navbar">
        <div class="navbar-links">
            <a href="homepage.php">HOME</a>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="loyalty_manager.php">LOYALTY MANAGER</a>
                <a href="feedback_manager.php">FEEDBACK MANAGER</a>
                <a href="inventorymanagement.php">INVENTORY MANAGER</a>
                <a href="order_management.php">ORDER MANAGER</a>
                <a href="contactUs_manager.php">CONTACT US MANAGER</a>
                <a href="return_manager.php">RETURN MANAGER</a>
            <?php else: ?>
                <a href="products_page.php">PRODUCTS</a>
            <?php endif; ?>
        </div>
        <div class="navbar-logo">
            <img src="asset/LUXUS_logo.png" alt="LUXUS Logo">
       
                <a href="logout.php">LOGOUT</a>
            
        </div>
    </div>
    
    <script>
        let prevScrollpos = window.pageYOffset;
        window.onscroll = function() {
            let currentScrollPos = window.pageYOffset;
            document.getElementById("navbar").style.top = prevScrollpos > currentScrollPos ? "0" : "-50px";
            prevScrollpos = currentScrollPos;
        }
    </script>
</body>
</html>
