<?php
session_start();
require ('db.php');
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Basket</title>
    <link rel="stylesheet" href="stylesheet.css">
    <script defer src="script.js"></script>
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

        table {
            position: fixed;
            margin: 80px auto;
            text-align: center;
            padding-top: 10px;
            width: 97%;
            box-shadow: 0 10px 10px 0 black;
            border-radius: 1px;
            border-collapse: collapse;
            font-weight: bold;
            background-color: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #363636;
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

        .goToCheckoutBtn {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            border: none;
            padding: 32px;
            margin: 0;
            background-color: #363636;
            color: white;
            max-width: 1000px;
            margin-left: 230px;
        }

        .goToCheckoutBtn:hover {
            background-color: grey;
        }
    </style>
</head>
<body>
<header>
    <h1>Cart</h1>
    <nav>
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
            <a href="search.php">SEARCH</a>
            <div class="navbar-logo">
                <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
            </div>
            <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
                <a href="profile.html">PROFILE</a>
            <?php endif; ?>
            <a href="checkout.php">BASKET</a>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="admin_page.php">ADMIN</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<script>
    let prevScrollpos = window.pageYOffset;
    let debounce;

    window.onscroll = function() {
        clearTimeout(debounce);

        debounce = setTimeout(function() {
            let currentScrollPos = window.pageYOffset;
            if (prevScrollpos > currentScrollPos) {
                document.getElementById("navbar").style.top = "0";
            } else {
                document.getElementById("navbar").style.top = "-75px";
            }
            prevScrollpos = currentScrollPos;
        }, 100);
    }
</script>

<main>
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
        <button type="submit" class="goToCheckoutBtn">Proceed to Checkout</button>
    </form>
</main>

<footer>
</footer>

</body>
</html>

<?php
} else {
    echo "<p>Your cart is empty. <a href='index.php'>Continue shopping</a></p>";
}
?>
