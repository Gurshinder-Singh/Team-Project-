<?php
session_start();

$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

if (isset($_SESSION['cart'][$product_id])) {
    if ($quantity > 0) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        unset($_SESSION['cart'][$product_id]);
    }
}

header("Location: basket.php");
?>
