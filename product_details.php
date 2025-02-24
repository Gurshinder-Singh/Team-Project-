<?php
session_start();
require 'db.php'; // Include database connection

// Check if product ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product not found.");
}

$product_id = $_GET['id']; // Use the product ID directly from URL

try {
    // Fetch product details based on the selected product ID
    $sql = "SELECT * FROM products WHERE product_id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$product) {
        die("Product not found.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
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
            height: 100%; /* Ensure the body takes full height */
            margin: 0;
        }

        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center horizontally */
            justify-content: center; /* Center vertically */
            height: calc(100% - 75px); /* Subtract navbar height */
            padding-top: 75px; /* Push down content to be below navbar */
        }

        main {
            text-align: center; /* Center text within main */
        }

        .navbar {
            height: 75px; /* Set your desired navbar height */
            display: flex;
            align-items: center;
            position: fixed;
            top: 0;
            background-color: #363636;
            transition: top 0.3s ease-in-out;
            will-change: transform; /* Use hardware acceleration */
        }

        .navbar a, 
        .navbar-logo {
            color: white; /* Set text color to white for links */
            text-decoration: none;
            padding: 14px 20px;
            flex: 1; /* Ensure each item takes equal space */
            text-align: center; /* Center text within buttons */
        }

        .navbar-logo {
            display: flex; /* Ensure image aligns in the center */
            justify-content: center;
            align-items: center;
            position: relative; /* Position the container relative for absolute centering */
            max-width: 200px; /* Ensure the container space remains the same */
        }

        .navbar-logo img {
            height: 95px; /* Increase the image size */
            width: auto; /* Maintain aspect ratio */
            margin: 0 auto; /* Center the image within its container */
        }

        .dropdown {
            position: relative;
            display: inline-block;
            flex: 1;
        }

        .dropbtn {
            background-color: #363636; /* Match the navbar color */
            color: white;
            padding: 14px 20px;
            width: 70px; /* Set the container width */
            height: 70px; /* Set the container height */
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-icon {
            height: 50px; /* Adjust the height for the menu icon */
            width: auto; /* Maintain aspect ratio */
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #363636; /* Match the navbar color */
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            transition: transform 0.3s ease-in-out; /* Add transition for smooth movement */
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
    </style>
    .singleProductImage img{
    float: right;
    width: 100%;
    height: 100%;
    margin-right: 50px;
    bottom:0
}
.singleProduct{
    color:#333;
}
.singleProductName{
    margin: 100px 0 0 0;
    font-size: 40px;
}

.singleProductPrice{
    font-weight: bold;
    font-size: 20px;
}

.productDescription{
    font-size: 15px;
    color:#333;
}

.singleProduct .buttons{
    margin: 100px 0 0 0;
}
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
                    document.getElementById("navbar").style.top = "-50px";
                }
                prevScrollpos = currentScrollPos;
            }, 100); // Adjust the debounce delay as necessary
        }
    </script>

    </header>



<body>
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
        <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
            <a href="profile.php">PROFILE</a>
        <?php endif; ?>
        <a href="checkout.php">BASKET</a>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <a href="admin_page.php">ADMIN</a>
        <?php endif; ?>
    </div>

        
  <div class="singleProduct">
    <div class="singleProductImage">
        <img src="<?= htmlspecialchars($product['image']); ?>" 
             alt="<?= htmlspecialchars($product['name']); ?>">
    </div>
    <div class="singleProductDetails">
        <h3 class="singleProductName">
            <?= htmlspecialchars($product['name']); ?>
        </h3>
         <p class="productBrand">
            Brand: <?= (htmlspecialchars($product['brand'])); ?>
        </p>
        <p class="singleProductPrice">
            <?= '£' . number_format((float) str_replace('£', '', $product['price']), 2); ?>
        </p>
        <p class="productDescription">
            Description: <?= (htmlspecialchars($product['description'])); ?>
        </p>
         <p class="productColor">
           Color: <?= (htmlspecialchars($product['color'])); ?>
        </p>
        <div class="buttons">
             <form method="POST" action="add_to_cart.php">
   					<input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
    				<input type="hidden" name="name" value="<?= htmlspecialchars($product['name']); ?>">
    				<input type="hidden" name="price" value="<?= htmlspecialchars($product['price']); ?>">
    				<input type="hidden" name="description" value="<?= htmlspecialchars($product['description']); ?>">
    				<?php if ($product['stock'] > 0): ?>
        				<button class="addToCart" type="submit">Add to cart</button>
    				<?php else: ?>
        				<button class="addToCart" type="button" disabled style="background-color: gray;">Out of Stock</button>
    				<?php endif; ?>
				</form>
            <button class="saveToWishlist">Save to wishlist</button>
        </div>
    </div>
</div>

</section>
</body>



<footer>

</footer>

</html>
</DOCTYPE>