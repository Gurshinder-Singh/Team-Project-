<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_checkout'])) {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        die("Your cart is empty. Cannot proceed with checkout.");
    }

    try {
        $conn->beginTransaction();

        foreach ($_SESSION['cart'] as $item) {
            if (!isset($item['product_id']) || !isset($item['quantity'])) {
                die("Error: Cart item data is missing.");
            }

            $product_id = intval($item['product_id']);
            $quantity = intval($item['quantity']);

            $stmt = $conn->prepare("SELECT stock FROM products WHERE product_id = :product_id FOR UPDATE");
            $stmt->execute([':product_id' => $product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                $conn->rollBack();
                die("Error: Product not found.");
            }

            if ($product['stock'] < $quantity) {
                $conn->rollBack();
                die("Error: Not enough stock available for " . htmlspecialchars($item['name']) . ".");
            }

            $stmt = $conn->prepare("UPDATE products SET stock = stock - :quantity WHERE product_id = :product_id");
            $stmt->execute([
                ':quantity' => $quantity,
                ':product_id' => $product_id
            ]);

            if ($stmt->rowCount() === 0) {
                $conn->rollBack();
                die("Error: Stock update failed.");
            }
        }

        unset($_SESSION['cart']);

        $conn->commit();

        header("Location: order_confirmation_page.html");
        exit;

    } catch (PDOException $e) {
        $conn->rollBack();
        die("Error processing order: " . $e->getMessage());
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Luxus Product Catalogue</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css"/>
    <style>
        h2 {
            color: rgb(0, 0, 0);
            text-decoration: underline;
            cursor: pointer;
            margin-top: 20px;
        }

        h2:hover {
            color: rgb(0, 0, 0);
        }

        section {
            padding: 10px 20px;
        }

        body, html {
            height: 100%;
            margin: 0;
        }

        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: calc(100% - 75px);
            padding-top: 75px;
        }

        main {
            text-align: center;
        }

        .navbar {
            height: 75px;
            display: flex;
            align-items: center;
            position: fixed;
            top: 0;
            background-color: #363636;
            transition: top 0.3s ease-in-out;
            will-change: transform;
        }

        .navbar a, 
        .navbar-logo {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            flex: 1;
            text-align: center;
        }

        .navbar-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            max-width: 200px;
        }

        .navbar-logo img {
            height: 95px;
            width: auto;
            margin: 0 auto;
        }

        .dropdown {
            position: relative;
            display: inline-block;
            flex: 1;
        }

        .dropbtn {
            background-color: #363636;
            color: white;
            padding: 14px 20px;
            width: 70px;
            height: 70px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-icon {
            height: 50px;
            width: auto;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #363636;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            transition: transform 0.3s ease-in-out;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
            color: black;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .checkout-container {
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start; 
            max-width: 1200px; 
            margin: 40px auto; 
            padding: 20px;
            border-radius: 10px;
        }

        .checkout-form, .cart-section {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #363636;
            color:white;
        }

        .checkout-form {
            flex: 2; 
            margin-right: 20px; 
        }

        .cart-section {
            flex: 1; color:white;
        }

        h1, h2 {
            font-size: 20px;
            margin-bottom: 20px;
            color:white;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color:white;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align:center;
            align-items:center;
        }

        .form-group select {
            max-width: 300px;  
            margin: 10px auto; 
            box-sizing: border-box;
            display: block;
        }

        .submit-btn {
            background-color: white;
            color: #363636;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit-btn:hover {
            background-color: gold;
        }

        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item-name {
            font-size: 14px;
            font-weight: bold;
        }

        .cart-item-price, .cart-item-quantity {
            font-size: 14px;
            color: white;
        }

        .cart-total {
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            color:white;
        }

        @media (max-width: 768px) {
            .checkout-container {
                flex-direction: column; 
            }

            .checkout-form {
                margin-right: 0;
                margin-bottom: 20px;
            }
        }   
    </style>
</head>

<header>
    <!-- Navigation bar -->
    <div class="navbar" id="navbar">
        <div class="dropdown">
            <button class="dropbtn">
                <img src="asset/menu_icon.png" alt="Menu Icon" class="menu-icon">
            </button>
            <div class="dropdown-content">
                <a href="about.php">About Us</a>
                <a href="contact.php">Contact Us</a>
                <a href="FAQ.php">FAQs</a>
            </div>
        </div>
        <a href="homepage.php">HOME</a>
        <a href="products_page.php">PRODUCTS</a>
        <div class="navbar-logo">
            <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile.php">PROFILE</a>
            <a href="logout.php">LOGOUT</a>
        <?php else: ?>
            <a href="login.php">LOGIN</a>
        <?php endif; ?>
        <a href="cart.php">BASKET</a>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <a href="admin_page.php">ADMIN</a>
        <?php endif; ?>
    </div>
</header>

<body>

<div class="checkout-container">
    <div class="checkout-form">
        <h1>Payment Method</h1>
        <form id="checkout" action="checkout.php" method="post">
            
            <section class="checkout-section">
                <div class="form-group">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" placeholder="First Name" required>
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" placeholder="Last Name" required>
                </div>

                <div class="form-group">
                    <label for="street">Billing Address:</label>
                    <input type="text" id="street" name="street" placeholder="1234 Elm St" required>
                </div>

                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" placeholder="New York" required>
                </div>

                <div class="form-group">
                    <label for="postcode">Zip or Postal Code:</label>
                    <input type="text" id="postcode" name="postcode" placeholder="10001" required>
                </div>

                <div class="form-group">
                    <label for="country">Country:</label>
                    <select id="country" name="country" required>
                        <option value="AF">Afghanistan</option>
                        <option value="AL">Albania</option>
                        <option value="DZ">Algeria</option>
                        <option value="AD">Andorra</option>
                        <option value="AO">Angola</option>
                        <option value="AR">Argentina</option>
                        <option value="AU">Australia</option>
                        <option value="AT">Austria</option>
                        <option value="BD">Bangladesh</option>
                        <option value="BE">Belgium</option>
                        <option value="BR">Brazil</option>
                        <option value="CA">Canada</option>
                        <option value="CN">China</option>
                        <option value="FR">France</option>
                        <option value="DE">Germany</option>
                        <option value="IN">India</option>
                        <option value="IT">Italy</option>
                        <option value="JP">Japan</option>
                        <option value="MX">Mexico</option>
                        <option value="RU">Russia</option>
                        <option value="SA">Saudi Arabia</option>
                        <option value="ZA">South Africa</option>
                        <option value="ES">Spain</option>
                        <option value="SE">Sweden</option>
                        <option value="CH">Switzerland</option>
                        <option value="US">United States</option>
                        <option value="GB">United Kingdom</option>
                    </select>
                </div>
            </section>

            <section class="checkout-section">
                <div class="form-group">
                    <label for="payment-method">Payment Method:</label>
                    <select id="payment-method" name="payment-method" required>
                        <option value="visa">Visa</option>
                        <option value="mastercard">MasterCard</option>
                        <option value="paypal">PayPal</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cardnumber">Card Number:</label>
                    <input type="text" id="cardnumber" name="cardnumber" placeholder="1111-2222-3333-4444" required>
                </div>

                <div class="form-group">
                    <label for="expdate">Expiration Date:</label>
                    <input type="text" id="expdate" name="expdate" placeholder="MM/YY" required>
                </div>

                <div class="form-group">
                    <label for="securitycode">Security Code:</label>
                    <input type="text" id="securitycode" name="securitycode" placeholder="CVV" required>
                </div>
            </section>

            <button type="submit" name="confirm_checkout" class="submit-btn">Confirm Purchase</button>
        </form>
    </div>

    <div class="cart-section">
        <h2>Your Cart</h2>
        
        <?php
        if (!empty($_SESSION['cart'])) {
            $total = 0;

            foreach ($_SESSION['cart'] as $item) {
                $price = floatval(str_replace('£', '', $item['price']));

                echo "<div class='cart-item'>
                        <div class='cart-item-details'>
                            <p class='cart-item-name'>{$item['name']}</p>
                            <p class='cart-item-quantity'>Quantity: {$item['quantity']}</p>
                            <p class='cart-item-price'>£" . number_format($price, 2) . "</p>
                        </div>
                    </div>";
                
                $total += $price * $item['quantity'];
            }

            echo "<div class='cart-total'>Total: £" . number_format($total, 2) . "</div>";
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>
    </div>
</div>

<script>
document.getElementById("checkout").addEventListener("submit", function(event) {
    setTimeout(function() {
        window.location.href = "order_confirmation_page.html";  
    }, 3000); 
});
</script>

</body>
</html>
