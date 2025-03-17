<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_order'])) {
    $order_id = $_POST['order_id'];

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = :order_id");
        $stmt->execute([':order_id' => $order_id]);

        $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = :order_id AND user_id = :user_id");
        $stmt->execute([
            ':order_id' => $order_id,
            ':user_id' => $user_id
        ]);

        $conn->commit();

        header("Location: current_orders.php");
        exit;
    } catch (PDOException $e) {
        $conn->rollBack();
        die("Error removing order: " . $e->getMessage());
    }
}

$stmt = $conn->prepare("
    SELECT order_id, total_price, status, created_at 
    FROM orders 
    WHERE user_id = :user_id AND status = 'Pending' 
    ORDER BY created_at DESC
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$current_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Orders | LUXUS</title>
    <link rel="stylesheet" href="stylesheet.css">
    
    <style>
        /* NAVIGATION BAR */
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
            font-weight: bold;
        }

        .navbar-logo img {
            height: 95px;
            width: auto;
            margin: 0 auto;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            background-color: #363636;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .menu-icon {
            height: 18px; /* SIGNIFICANTLY SMALLER */
            width: 18px;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #363636;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
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

        body {
            background-color: #5C4033;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding-top: 110px;
        }

        .container {
            padding: 40px;
            max-width: 700px;
            margin: auto;
            background: #835C44;
            color: white;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h1 {
            text-align: center;
            color: #F0C987;
        }

        .order-box {
            background: white;
            color: black;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .order-box strong {
            color: #6B4A37;
        }

        .remove-button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
            width: 90px;
        }

        .remove-button:hover {
            background-color: #cc0000;
        }

        .no-orders {
            color: white;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- NAVIGATION BAR -->
<div class="navbar" id="navbar">
    <div class="dropdown">
        <button class="dropbtn">
            <img src="asset/menu_icon.png" alt="Menu Icon" class="menu-icon">
        </button>
        <div class="dropdown-content">
            <a href="about.php">ABOUT US</a>
            <a href="contact.php">CONTACT US</a>
            <a href="FAQ.php">FAQs</a>
            <a href="returns.php">RETURNS</a>
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
</div>

<!-- Orders Section -->
<div class="container">
    <h1>Current Orders</h1>

    <?php if (!empty($current_orders)): ?>
        <?php foreach ($current_orders as $order): ?>
            <div class="order-box">
                <form action="current_orders.php" method="post" style="display: inline;">
                    <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                    <button type="submit" name="remove_order" class="remove-button">Remove</button>
                </form>
                <p><strong>Order #<?= htmlspecialchars($order['order_id']) ?></strong></p>
                <p>Total: £<?= htmlspecialchars($order['total_price']) ?></p>
                <p>Status: <?= htmlspecialchars($order['status']) ?></p>
                <p>Date: <?= htmlspecialchars($order['created_at']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-orders">No current orders found.</p>
    <?php endif; ?>
</div>

</body>
</html>
