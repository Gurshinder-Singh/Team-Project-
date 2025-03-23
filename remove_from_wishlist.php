<?php
session_start();
require 'db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    
    try {
        $sql = "DELETE FROM wishlist WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();

        
        header("Location: wishlist.php");
        exit();
    } catch (PDOException $e) {
        die("Error removing from wishlist: " . $e->getMessage());
    }
} else {
    
    header("Location: wishlist.php");
    exit();
}
?>