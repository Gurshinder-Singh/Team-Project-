<?php
session_start();
include 'db.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    
    $valid_statuses = ["Pending", "Processing", "Shipped", "Completed", "Cancelled"]; // enum values
    if (!in_array($status, $valid_statuses)) {
        echo "Invalid status";
        exit;
    }

    try {
       
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->execute([$status, $order_id]);

        if ($stmt->rowCount() > 0) {
            echo "Success";
        } else {
            echo "No rows updated. Order ID might be incorrect.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request";
}
?>
