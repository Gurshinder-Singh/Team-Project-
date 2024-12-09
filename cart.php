<?php
session_start();

// If the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Your Cart</title>
    <link rel="stylesheet" href="stylesheet.css">
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
        <a href="logout.php">LOGOUT</a>
    <?php elseif (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
        <a href="login.php">LOGIN</a>
    <?php endif; ?>
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="admin_page.php">ADMIN</a>
    <?php endif; ?>
</div>
    <style>
        h2 {
            color: rgb(0, 0, 0);
            text-decoration: underline;
            cursor: pointer;
            margin-top: 20px;
        }

        h2:hover {
            color: rgb(0, 0, 0);
        }

        section {
            padding: 10px 20px;
        }

        body, html {
            height: 100%;
            margin: 0;
        }

        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: calc(100% - 75px);
            padding-top: 75px;
        }

        main {
            text-align: center;
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

        .navbar a,
        .navbar-logo {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            flex: 1;
            text-align: center;
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
        }

        .dropdown-content a:hover {
            background-color: #ddd;
            color: black;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        table {
            position: fixed;
            margin: 80px auto;
            text-align: center;
            padding-top: 10px;
            width: 97%;
            box-shadow: 0 10px 10px 0 black;
            border-radius: 1px;
            border-collapse: collapse;
            font-weight: bold;
            background-color: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #363636;
        }

        tr:nth-child(even) {
            background-color: rgb(201, 200, 198);
        }

        tr:hover {
            background-color: grey;
        }

        th, tfoot {
            background-color: #363636;
            color: white;
            padding: 10px 5px;
            text-align: left;
        }

        .goToCheckoutBtn {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            border: none;
            padding: 32px;
            margin: 0;
            background-color: #363636;
            color: white;
            max-width: 1000px;
            margin-left: 230px;
        }

        .goToCheckoutBtn:hover {
            background-color: grey;
        }
		
    </style>

</head>
<body>
	
    
    <h1>Your Cart</h1>

    <table class="cartTable">
        <thead>
            <tr>
                <th>Product</th> <!-- Kept Product Column -->
                <th>Price</th>   <!-- Kept Price Column -->
                <th>Quantity</th> <!-- Kept Quantity Column -->
                <th>Total</th>    <!-- Kept Total Column -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']); ?></td> <!-- Product Name -->
                    <td><?= htmlspecialchars($item['price']); ?></td> <!-- Product Price -->
                    <td><?= $item['quantity']; ?></td> <!-- Product Quantity -->
                    <td><?= $item['price'] * $item['quantity']; ?></td> <!-- Total for Product -->
                </tr>
            <?php endforeach; ?>
        </tbody>
       <h1> <a href="checkout.php" class="checkoutButton">Proceed to Checkout</a></h1>
    </table>

    <div class="cartTotal">
        <p>Total: 
            <?php 
                $total = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                echo "£" . $total;
            ?>
        </p>
    </div>

    

</body>
</html>
