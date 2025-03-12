<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT order_id, total_price, status, created_at 
    FROM orders 
    WHERE user_id = :user_id AND status = 'Completed' 
    ORDER BY created_at DESC
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$previous_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previous Orders | LUXUS</title>
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #5C4033;
            color: white;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 40px;
            max-width: 700px;
            margin: auto;
            background: #835C44;
            color: white;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            color: #F0C987;
        }
        .order-box {
            background: white;
            color: black;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .order-box strong {
            color: #6B4A37;
        }
        .feedback-link {
            color: blue;
            text-decoration: underline;
            font-size: 14px;
            font-weight: bold;
        }
        .feedback-link:hover {
            color: gold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Previous Orders</h1>

        <?php if (!empty($previous_orders)): ?>
            <?php foreach ($previous_orders as $order): ?>
                <div class="order-box">
                    <p><strong>Order #<?= htmlspecialchars($order['order_id']) ?></strong></p>
                    <p>Total: £<?= htmlspecialchars($order['total_price']) ?></p>
                    <p>Date: <?= htmlspecialchars($order['created_at']) ?></p>
                    <a href="Feedback.php" class="feedback-link">Leave Feedback</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center;">No previous orders found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
