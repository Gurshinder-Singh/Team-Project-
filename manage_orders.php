<?php
session_start();
include 'db.php'; 

// Fetch orders
$sql = "SELECT * FROM orders ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
    
<div class="navbar" id="navbar">
         
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="homepage.php">HOME</a>
        <a href="loyalty_manager.php">LOYALTY MANAGER</a>
    <?php else: ?>
        <a href="homepage.php">HOME</a>
        <a href="products_page.php">PRODUCTS</a>
    <?php endif; ?>
    <div class="navbar-logo">
        <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
    </div>
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="feedback_manager.php">FEEDBACK MANAGER</a>
        <a href="inventorymanagement.php">INVENTORY MANAGER</a>
    <?php endif; ?>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="profile.php">PROFILE</a>
        <a href="logout.php">LOGOUT</a>
    <?php elseif (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
        <a href="login.php">LOGIN</a>
    <?php endif; ?>
	<a href="logout.php">LOGOUT</a>

</div>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>
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
</style>
<body>
    <h2>Order Management</h2>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Change Status</th>
        </tr>
        <?php foreach ($orders as $row) { ?>
        <tr>
            <td><?php echo $row['order_id']; ?></td>
            <td><?php echo $row['user_id']; ?></td>
            <td>£<?php echo number_format($row['total_price'], 2); ?></td>
            <td id="status-<?php echo $row['order_id']; ?>"><?php echo $row['status']; ?></td>
            <td>
                <select class="order-status" data-order-id="<?php echo $row['order_id']; ?>">
                    <option value="Pending" <?php if ($row['status'] == "Pending") echo "selected"; ?>>Pending</option>
                    <option value="Processing" <?php if ($row['status'] == "Processing") echo "selected"; ?>>Processing</option>
                    <option value="Shipped" <?php if ($row['status'] == "Shipped") echo "selected"; ?>>Shipped</option>
                    <option value="Completed" <?php if ($row['status'] == "Completed") echo "selected"; ?>>Completed</option>
                    <option value="Cancelled" <?php if ($row['status'] == "Cancelled") echo "selected"; ?>>Cancelled</option>
                    <option value="Refunded" <?php if ($row['status'] == "Refunded") echo "selected"; ?>>Refunded</option>
                </select>
            </td>
        </tr>
        <?php } ?>
    </table>

    <script>
        $(document).ready(function () {
            $(".order-status").change(function () {
                var orderId = $(this).data("order-id");
                var newStatus = $(this).val();

                $.ajax({
                    url: "update_order_status.php",
                    type: "POST",
                    data: { order_id: orderId, status: newStatus },
                    success: function (response) {
                        $("#status-" + orderId).text(newStatus);
                        alert("Order status updated!");
                    },
                    error: function () {
                        alert("Error updating order status.");
                    }
                });
            });
        });
    </script>
</body>
</html>
