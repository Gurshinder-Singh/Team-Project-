<?php
session_start();
require 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please log in to view your orders.");
}

$user_id = $_SESSION['user_id'];

// Fetch user's orders
$orderQuery = "SELECT order_id, total_price, created_at FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
$orderStmt = $conn->prepare($orderQuery);
$orderStmt->bindParam(':user_id', $user_id);
$orderStmt->execute();
$orders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch return statuses for user orders
$returnQuery = "SELECT order_id, status FROM returns WHERE user_id = :user_id";
$returnStmt = $conn->prepare($returnQuery);
$returnStmt->bindParam(':user_id', $user_id);
$returnStmt->execute();
$returns = $returnStmt->fetchAll(PDO::FETCH_ASSOC);

// Map return status to orders
$returnStatuses = [];
foreach ($returns as $return) {
    $returnStatuses[$return['order_id']] = $return['status'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previous Orders - LUXUS</title>
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        body {
            background-color: #5c4033;
            color: white;
            font-family: 'Century Gothic', sans-serif;
            margin: 0;
            padding: 0;
        }

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
            padding: 10px; /* Adjusted padding */
        }

        .menu-icon {
            height: 18px; /* SIGNIFICANTLY SMALLER */
            width: 18px;  /* Ensuring uniform size */
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

        .container {
            max-width: 800px;
            margin: 100px auto;
            background: #412920;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: #f0c14b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            color: black;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f0c14b;
            color: black;
        }

        .return-status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .approved {
            color: green;
        }

        .rejected {
            color: red;
        }

        .pending {
            color: orange;
        }

        .no-return {
            color: grey;
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

<div class="container">
    <h2>Your Previous Orders</h2>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Return Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td>£<?= htmlspecialchars($order['total_price']) ?></td>
                        <td><?= htmlspecialchars($order['created_at']) ?></td>
                        <td>
                            <?php if (isset($returnStatuses[$order['order_id']])): ?>
                                <?php if ($returnStatuses[$order['order_id']] === 'Approved'): ?>
                                    <span class="return-status approved">Approved</span>
                                <?php elseif ($returnStatuses[$order['order_id']] === 'Rejected'): ?>
                                    <span class="return-status rejected">Rejected</span>
                                <?php else: ?>
                                    <span class="return-status pending">Pending</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="return-status no-return">No Return</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
