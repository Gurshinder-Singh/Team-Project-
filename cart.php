<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['user_id'])) {
}

if (isset($_POST['update_quantity'])) {
    $productName = $_POST['product_name'];
    $newQuantity = intval($_POST['new_quantity']);

    if ($newQuantity > 0) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['name'] === $productName) {
                $item['quantity'] = $newQuantity;
                break;
            }
        }
        unset($item);
    }

    header('Location: cart.php');
    exit;
}

if (isset($_POST['remove_product'])) {
    $productNameToRemove = $_POST['product_name'];

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['name'] === $productNameToRemove) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    $_SESSION['cart'] = array_values($_SESSION['cart']);
    
    header('Location: cart.php');
    exit;
}

if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    header('Location: cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Your Cart</title>
    <link rel="icon" type="image/favicon" href="asset/LUXUS_logo.png">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <!-- NavBar Icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="stylesheet.css">
<div class="navbar" id="navbar">
    <div class="dropdown">
        <button class="dropbtn">
            <img src="asset/menu_icon.png" alt="Menu Icon" class="menu-icon">
        </button>
        <div class="dropdown-content">
            <a href="about.php"><i class="fas fa-info-circle"></i> About Us</a>
            <a href="contact.php"><i class="fas fa-envelope"></i> Contact Us</a>
            <a href="FAQ.php"><i class="fas fa-question-circle"></i> FAQs</a>
    <a href="returns.php"><i class="fas fa-undo-alt"></i> Returns</a>
        </div>
    </div>
	<button id="darkModeToggle">Toggle Dark Mode</button>
    <a href="homepage.php"><i class="fas fa-home"></i> HOME</a>
    <a href="products_page.php"><i class="fas fa-box-open"></i> PRODUCTS</a>
    <div class="navbar-logo">
        <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
    </div>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="profile.php"><i class="fas fa-user"></i> PROFILE</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
    <?php elseif (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="admin_page.php"><i class="fas fa-user-shield"></i> ADMIN</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
    <?php else: ?>
        <a href="login.php"><i class="fas fa-sign-in-alt"></i> LOGIN</a>
    <?php endif; ?>
    <a href="cart.php"><i class="fas fa-shopping-basket"></i> BASKET</a>
</div>
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

        ..navbar {
            height: 75px;
            display: flex;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            background-color: #363636;
            transition: top 0.3s ease-in-out;
            will-change: transform;
            z-index: 1000;
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
    transform: translateX(0); 
    transition: transform 0.3s ease-in-out; 
}

.dropdown-content a:hover {
    background-color: #ddd;
    color: black;

}

.dropdown:hover .dropdown-content {
    display: block;
}


table.cartTable {
    position: relative;
    margin: 80px auto;
    text-align: center;
    padding-top: 10px;
    width: 100%;
    max-width: 1200px;
    box-shadow: 0 10px 10px 0 black;
    border-radius: 1px;
    border-collapse: collapse;
    font-weight: bold;
    background-color: white;
}

th, td {
    padding: 10px;
    
    text-align: center;
}

tr:nth-child(even) {
    background-color: rgb(201, 200, 198);
}

tr:hover {
    background-color: grey;
}

th, tfoot {
    background-color: #363636;
    color: white;
    padding: 10px 5px;
    text-align: left;
}

th:nth-child(1), td:nth-child(1) {
    width: 30%;
}

th:nth-child(2), td:nth-child(2) {
    width: 20%;
}

th:nth-child(3), td:nth-child(3) {
    width: 20%;
}

th:nth-child(4), td:nth-child(4) {
    width: 20%;
}

th:nth-child(5), td:nth-child(5) {
    width: 10%;
}


        .checkoutButton {
            background-color: #363636;
            color: white;
            text-align: center;
            padding: 30px;
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
            position:fixed;
            bottom:0;
            right:0;
            left:0;
            text-decoration:none;
        }

        .cartTotal{
            background-color:#d4af37;
            color: white;
            padding:10px;
            font-size: 20px;
            border: none;
            border-radius: 5px;
            text-decoration:none;
            position:fixed;
            bottom:75px;
            right:0;
            left:0;
            text-align:center;
        }

        .checkoutButton:hover {
            background-color: darkgreen;
        }

        .clearCartButton {
            background-color:darkred;
            color: white;
            padding: 10px 0 10px 0;
            font-size: 16px;
            cursor: pointer;
           position: relative;
    margin: 80px auto;
    text-align: center;
    padding-top: 10px;
    border-radius: 1px;
        }

        .clearCartButton:hover {
            background-color: darkred;
        }
.backToProductsButton {
    background-color: #363636;
    color: white;
    padding: 10px;
    font-size: 16px;
    cursor: pointer;
    position:relative;
    left:0;
    text-decoration:none;
    margin: 80px auto;
    text-align: center;
    padding-top: 10px;
    border-radius: 1px;
}

.backToProductsButton:hover {
    background-color: darkblue;
}
.updateButton {
    background-color: #4CAF50;
    color: white;
    padding: 8px 12px;
    font-size: 14px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    display: inline-block;
    text-decoration: none;
}

.updateButton:hover {
    background-color: #45a049;
}

input[type="number"] {
    width: 50px;
    padding: 5px;
    border: 2px solid #ccc;
    border-radius: 5px;
    text-align: center;
    font-size: 16px;
    background-color: #f9f9f9;
}

input[type="number"]:focus {
    outline: none;
    border-color: #4CAF50;
}



.removeButton {
    background-color: red;
    color: white;
    padding: 8px 12px;
    font-size: 14px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    display: inline-block;
    text-decoration: none;
}

.removeButton:hover {
    background-color: darkred;
}

/* Dark Mode Styles */
.dark-mode {
    background-color: #1e1e1e; /* Dark background for the entire body */
    color: white; /* Light text color */
}

.dark-mode .cartTable {
    background-color: #333;
    color: white; 
    border: 1px solid #888; 
}

.dark-mode .cartTable th,
.dark-mode .cartTable tfoot {
    background-color: #444; 
    color: white; 
}

.dark-mode .cartTable tr:nth-child(even) {
    background-color: #555;
}

.dark-mode .cartTable tr:hover {
    background-color: #666;
}

.dark-mode .cartTable td, 
.dark-mode .cartTable th {
    padding: 10px;
    text-align: center;
}

.dark-mode .checkoutButton, 
.dark-mode .backToProductsButton {
    background-color: #444; /* Dark button background */
    color: white; /* White text on buttons */
    border: 1px solid #888; 
    padding: 15px;
    font-size: 18px;
    text-align: center;
    border-radius: 4px;
    cursor: pointer;
}

.dark-mode .checkoutButton:hover, 
.dark-mode .backToProductsButton:hover {
    background-color: #555; /* Lighter button background on hover */
}

.dark-mode .cartTotal {
    background-color: #d4af37; /* Keep golden background for cart total */
    color: white;
    padding: 10px;
    font-size: 20px;
    text-align: center;
}

.dark-mode .removeButton {
    background-color: red;
    color: white;
    padding: 8px 12px;
    font-size: 14px;
    cursor: pointer;
    border-radius: 5px;
}

.dark-mode .updateButton {
    background-color: #4CAF50;
    color: white;
    padding: 8px 12px;
    font-size: 14px;
    cursor: pointer;
    border-radius: 5px;
}

.dark-mode .updateButton:hover {
    background-color: #45a049;
}

.dark-mode input[type="number"] {
    background-color: #555;
    color: white;
    border: 2px solid #888; 
}

.dark-mode input[type="number"]:focus {
    border-color: #4CAF50;
    outline: none;
}

.dark-mode .error {
    color: #d9534f; 
}

#darkModeToggle {
    background-color: transparent;
    color: white;
    font-size: 16px;
    font-weight: bold;
    border: none;
    padding: 10px 15px;
    text-decoration: none;
    cursor: pointer;
    transition: color 0.3s ease;
}



    </style>
</head>
<body>

<h1>Your Cart</h1>

<div class="buttons-container">
    <div class="buttons-container">
        <a href="products_page.php" class="backToProductsButton">←Back to Products</a>
    </div>
    <form method="POST" action="cart.php">
        <button type="submit" name="clear_cart" class="clearCartButton">Clear Cart</button>
    </form>
	<a href="checkout.php" class="checkoutButton">Proceed to Checkout</a>

</div>
 <table class="cartTable">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($_SESSION['cart'])): ?>
            <tr>
                <td colspan="5" style="text-align: center;">Your cart is empty.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr id="row-<?= htmlspecialchars($item['name']); ?>">
                    <td><?= htmlspecialchars($item['name']); ?></td>
                    <td><?= htmlspecialchars($item['price']); ?></td>
                    <td>
                        <form method="POST" action="cart.php" style="display: inline;">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($item['name']); ?>">
                            <input type="number" name="new_quantity" value="<?= $item['quantity']; ?>" min="1" max="50" style="width: 50px;">
                            <button type="submit" name="update_quantity">Update</button>
                        </form>
                    </td>
                    <td>£<?= number_format(floatval(str_replace('£', '', $item['price'])) * $item['quantity'], 2); ?></td>
                    <td>
                        <form method="POST" action="cart.php" style="display: inline;">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($item['name']); ?>">
                            <button type="submit" name="remove_product" class="removeButton">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div class="cartTotal">
    <p>Total: 
        <?php 
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $price = floatval(str_replace('£', '', $item['price']));
                $total += $price * $item['quantity'];
            }
            echo "£" . number_format($total, 2);
        ?>
    </p>
</div>
<script>
    document.getElementById('darkModeToggle').addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
});
    </script>
</body>
</html>
