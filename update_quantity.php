<?php
session_start();


if (isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    
    if (is_numeric($quantity) && $quantity > 0) {
        
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                
                $item['quantity'] = $quantity;
                break;
            }
        }
    }


    header("Location: cart.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
