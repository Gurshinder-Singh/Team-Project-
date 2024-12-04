<?php
session_start();  


if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $total = 0;  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Basket</title>
    <link rel="stylesheet" href="Stylesheet.css">
    <script defer src="script.js"></script>
</head>
<header style="height: 10%;">
    <h1>Basket</h1>
    <a href="#" class="logo"><img src="logo.png" alt="Your Logo"></a>
    <nav>
        <a href="login.html" class="active">Log In</a>
        <a href="signup.html">Sign Up</a>
    </nav>
</header>

<body>
    <table id="buyItems">
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
          
            foreach ($_SESSION['cart'] as $item) {
                $item_total = $item['price'] * $item['quantity'];  
                $total += $item_total;  
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td>
                        <form action="update_quantity.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="99">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>£<?php echo number_format($item['price'], 2); ?></td>
                    <td>£<?php echo number_format($item_total, 2); ?></td>
                    <td>
                        <form action="remove_from_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td></td>
                <td></td>
                <td></td>
                <td>£<?php echo number_format($total, 2); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <form action="checkout.php" method="POST">
        <button type="submit">Proceed to Checkout</button>
    </form>
</body>
<footer>
 
</footer>
</html>
<?php
} else {
    echo "<p>Your cart is empty. <a href='index.php'>Continue shopping</a></p>";
}
?>
