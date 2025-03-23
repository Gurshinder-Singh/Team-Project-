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
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 600) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$_SESSION['last_activity'] = time();
?>

<script>
    setTimeout(function() {
        window.location.href = 'login.php';
    }, 600000); // 600000ms = 10 minutes
</script>

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

        .status-processing {
            color: blue;
        }

        .status-shipped {
            color: purple;
        }

        .status-completed {
            color: green;
        }

        .status-cancelled {
            color: red;
        }

        button {
            padding: 5px 10px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
            margin: 2px;
        }

        button:hover {
            background-color: #555;
        }

        .complete-button {
            background-color: #4CAF50; /* Green */
        }

        .complete-button:hover {
            background-color: #45a049; /* Darker green */
        }

        h1, h2 {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <div class="content">
    <!-- NAV BAR -->

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
							<td><?= htmlspecialchars($order['user_id'] ?? 'Guest'); ?></td>
                            <td>£<?= htmlspecialchars($order['total_price']); ?></td>
                            <td class="status-<?= strtolower($order['status']); ?>">
                                <?= htmlspecialchars($order['status']); ?>
                            </td>
                            <td><?= htmlspecialchars($order['created_at']); ?></td>
                            <td>
                               
                                <form action="order_management.php" method="post" style="display: inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                    <input type="hidden" name="status" value="Processing">
                                    <button type="submit" name="update-status">Processing</button>
                                </form>
                                <form action="order_management.php" method="post" style="display: inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                    <input type="hidden" name="status" value="Shipped">
                                    <button type="submit" name="update-status">Shipped</button>
                                </form>
                                <form action="order_management.php" method="post" style="display: inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                    <input type="hidden" name="status" value="Completed">
                                    <button type="submit" name="update-status" class="complete-button">Complete</button>
                                </form>
                                <form action="order_management.php" method="post" style="display: inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                    <input type="hidden" name="status" value="Cancelled">
                                    <button type="submit" name="update-status">Cancel</button>
                                </form>
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
    </div>
                <!-- FOOTER -->
<footer style="
            background-color: #2c2c2c;
            color: white;
            padding: 10px 15px;
            text-align: center;
            font-size: 13px;
            margin-top: 50px;
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


</body>
</html>