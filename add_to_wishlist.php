<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the product details from the form
    $product_id = $_POST['product_id'];
    $product_name = $_POST['name'];
    $user_id = $_SESSION['user_id'];

    // Check if the product is already in the user's wishlist
    try {
        $check_sql = "SELECT * FROM wishlist WHERE user_id = :user_id AND product_id = :product_id";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $check_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            // Product already exists in the wishlist
            header("Location: products_page.php?wishlist=duplicate");
            exit();
        }

        // Insert the product into the wishlist table
        $insert_sql = "INSERT INTO wishlist (user_id, product_id, product_name, added_at) 
                       VALUES (:user_id, :product_id, :product_name, NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insert_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $insert_stmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
        $insert_stmt->execute();

        // Redirect back to the products page with a success message
        header("Location: products_page.php?wishlist=success");
        exit();
    } catch (PDOException $e) {
        // Handle any errors
        die("Error adding to wishlist: " . $e->getMessage());
    }
} else {
    // If the form is not submitted, redirect back to the products page
    header("Location: products_page.php");
    exit();
}
?>