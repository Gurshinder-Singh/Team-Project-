<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $order_id = htmlspecialchars($_POST['order_id']);
    $reason = htmlspecialchars($_POST['reason']);
    $details = htmlspecialchars($_POST['details']);

    $query = "INSERT INTO returns (user_id, order_id, reason, details, status) 
              VALUES (:user_id, :order_id, :reason, :details, 'Pending')";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->bindParam(':reason', $reason);
    $stmt->bindParam(':details', $details);

    if ($stmt->execute()) {
        header("Location: previous_orders.php?success=Return request submitted.");
        exit();
    } else {
        header("Location: returns.php?error=Something went wrong.");
        exit();
    }
}
?>
