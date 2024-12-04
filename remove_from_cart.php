<?php
session_start();


if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

   
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]); 
            break;
        }
    }

  
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    
    header("Location: cart.php");
    exit();
} else {
    echo "No product_id sent via POST.";
}
?>
