<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); 

require 'db.php'; 

try {
    $sql = "SELECT * FROM orders ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching orders: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-status'])) {
    $order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    if ($order_id && $status) {
        try {
            $sql = "UPDATE orders SET status = :status WHERE order_id = :order_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':status' => $status,
                ':order_id' => $order_id
            ]);
            header("Location: order_management.php");
            exit;
        } catch (PDOException $e) {
            die("Error updating order status: " . $e->getMessage());
        }
    } else {
        die("Invalid input data.");
    }
}

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

try {
    $sql = "SELECT * FROM orders";
    $params = [];

    if (!empty($status_filter)) {
        $sql .= " WHERE status = :status";
        $params[':status'] = $status_filter;
    }

    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching orders: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
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

        .inventory-table {
            width: 90%;
            margin: 100px auto 20px auto;
            border-collapse: collapse;
            text-align: center;
        }

        .inventory-table th, .inventory-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .inventory-table th {
            background-color: #f2f2f2;
        }

        .status-pending {
            color: orange;
        }

        .status-completed {
            color: green;
        }

        button {
            padding: 5px 10px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }

        h1, h2 {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="content">
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

            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="homepage.php">HOME</a>
                <a href="loyalty_manager.php">LOYALTY MANAGER</a>
                <a href="feedback_manager.php">FEEDBACK MANAGER</a>
                <a href="inventorymanagement.php">INVENTORY MANAGER</a>
           		<a href="order_management.php">ORDER MANAGER</a>
            <?php else: ?>
                <a href="homepage.php">HOME</a>
                <a href="products_page.php">PRODUCTS</a>
            <?php endif; ?>

            <div class="navbar-logo">
                <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
            </div>

           <?php if (isset($_SESSION['user_id'])): ?>
    <a href="profile.php">PROFILE</a>
    <a href="previous_orders.php">PREVIOUS ORDERS</a> 
    <a href="current_orders.php">CURRENT ORDERS</a> 
    <a href="logout.php">LOGOUT</a>
<?php else: ?>
    <a href="login.php">LOGOUT</a>
<?php endif; ?>

        </div>

<h1 style="margin-top:100px;">Order Management</h1>

<form method="GET" action="order_management.php">
    <label for="status">Filter by Status:</label>
    <select name="status" id="status">
        <option value="">All</option>
        <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : ''; ?>>Pending</option>
        <option value="Processing" <?= $status_filter == 'Processing' ? 'selected' : ''; ?>>Processing</option>
        <option value="Shipped" <?= $status_filter == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
        <option value="Completed" <?= $status_filter == 'Completed' ? 'selected' : ''; ?>>Completed</option>
        <option value="Cancelled" <?= $status_filter == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
    </select>
    <button type="submit">Apply</button>
</form>

<table class="inventory-table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['order_id']; ?></td>
                    <td><?= htmlspecialchars($order['user_id']); ?></td>
                    <td>£<?= htmlspecialchars($order['total_price']); ?></td>
                    <td class="status-<?= strtolower($order['status']); ?>">
                        <?= htmlspecialchars($order['status']); ?>
                    </td>
                    <td><?= htmlspecialchars($order['created_at']); ?></td>
                    <td>
                        <?php if ($order['status'] === 'Pending'): ?>
                            <form action="order_management.php" method="post" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                <input type="hidden" name="status" value="Processing">
                                <button type="submit" name="update-status">Mark as Processing</button>
                            </form>
                            <form action="order_management.php" method="post" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                <input type="hidden" name="status" value="Shipped">
                                <button type="submit" name="update-status">Mark as Shipped</button>
                            </form>
                            <form action="order_management.php" method="post" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                <input type="hidden" name="status" value="Completed">
                                <button type="submit" name="update-status">Mark as Completed</button>
                            </form>
                            <form action="order_management.php" method="post" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                <input type="hidden" name="status" value="Cancelled">
                                <button type="submit" name="update-status">Mark as Cancelled</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No orders found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
