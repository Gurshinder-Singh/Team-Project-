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
    <link rel="stylesheet" href="stylesheet.css">
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
        <?php elseif (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
            <a href="login.php">LOGIN</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <a href="admin_page.php">ADMIN</a>
        <?php endif; ?>
        <a href="checkout.php">BASKET</a>
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

        .navbar {
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
    border: 1px solid #363636;
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
        .buttons-container {
            display: flex;
            justify-content: flex-start;
            margin: 20px;
        }

        .cartTotal {
            background-color: #363636;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
        }

        .checkoutButton {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            display: inline-block;
            margin-left: 10px;
            text-decoration: none;
        }

        .checkoutButton:hover {
            background-color: #45a049;
        }

        .clearCartButton {
            background-color: red;
            color: white;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }

        .clearCartButton:hover {
            background-color: darkred;
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

.backToProductsButton {
    background-color: #008CBA;
    color: white;
    padding: 10px;
    font-size: 16px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    display: inline-block;
    text-decoration: none;
    margin-left: 10px;
}

.backToProductsButton:hover {
    background-color: #007BB5;
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
    </style>
</head>
<body>

<h1>Your Cart</h1>

<div class="buttons-container">
    <div class="buttons-container">
        <a href="products_page.php" class="backToProductsButton">Back to Products</a>
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

</body>
</html>
