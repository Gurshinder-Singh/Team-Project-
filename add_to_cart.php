<?php
session_start();  


if (isset($_POST['product_id'], $_POST['name'], $_POST['description'], $_POST['price'])) {

    
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    
    $cart_item = array(
        'product_id' => $product_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => 1  
    );

    
    if (isset($_SESSION['cart'])) {
        $found = false;

        
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                
                $item['quantity'] += 1;
                $found = true;
                break;
            }
        }

       
        if (!$found) {
            $_SESSION['cart'][] = $cart_item;
        }

    } else {
        
        $_SESSION['cart'] = array($cart_item);
    }

 
    header("Location: cart.php");
    exit();

} else {
    echo "No product data sent via POST.";
}
?>