<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns - LUXUS</title>
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        body {
            background-color: #5c4033;
            color: white;
            font-family: 'Century Gothic', sans-serif;
            margin: 0;
            padding: 0;
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
            z-index: 1000;
        }

        .navbar a, .navbar-logo {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            flex: 1;
            text-align: center;
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
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            transition: transform 0.3s ease-in-out;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
            transition: transform 0.3s ease-in-out;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
            color: black;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .return-section {
            text-align: center;
            padding: 50px 20px;
            background-color: #412920;
            border-radius: 10px;
            margin: 80px auto;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .return-section h1 {
            color: #f0c14b;
        }

        .return-section p {
            font-size: 1.1em;
            margin-bottom: 15px;
        }

        .return-form {
            max-width: 500px;
            margin: 0 auto;
        }

        .return-form input,
        .return-form select,
        .return-form textarea,
        .return-form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
        }

        .return-form button {
            background-color: #f0c14b;
            color: black;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="navbar" id="navbar">
        <div class="dropdown">
            <button class="dropbtn">
                <img src="asset/menu_icon.png" alt="Menu Icon" class="menu-icon">
            </button>
            <div class="dropdown-content">
                <a href="about.php">About Us</a>
                <a href="contact.php">Contact Us</a>
                <a href="FAQ.php">FAQs</a>
                <a href="returns.php">Returns</a>
            </div>
        </div>
        <a href="homepage.php">HOME</a>
        <a href="products_page.php">PRODUCTS</a>
        <div class="navbar-logo">
            <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile.php">PROFILE</a>
            <a href="logout.php">LOGOUT</a>
        <?php else: ?>
            <a href="login.php">LOGIN</a>
        <?php endif; ?>
        <a href="checkout.php">BASKET</a>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <a href="admin_page.php">ADMIN</a>
        <?php endif; ?>
    </div>

    <section class="return-section">
        <h1>Return Policy</h1>
        <p>If you're not satisfied with your order, you may return it in its original condition within **30 days** of purchase.</p>
        <p>Customers are responsible for return shipping costs unless the item is defective.</p>

        <h2>Request a Return</h2>
        <form class="return-form">
            <label for="order_id">Order ID:</label>
            <input type="text" id="order_id" name="order_id" required placeholder="Enter your order ID here">

            <label for="reason">Reason for Return:</label>
            <select id="reason" name="reason" required>
                <option value="Damaged Item">Damaged Item</option>
                <option value="Incorrect Item">Incorrect Item</option>
                <option value="Other">Other</option>
            </select>

            <label for="details">Additional Details:</label>
            <textarea id="details" name="details" rows="4" required placeholder="Provide any additional information about your return"></textarea>

            <button type="submit">Submit Return Request</button>
        </form>
    </section>
</body>
</html>
