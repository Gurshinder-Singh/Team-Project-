<?php
session_start(); 
require 'db.php'; 

try {
    $sql = "SELECT product_id, name, description, price, image, brand, color FROM products";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Luxus Product Catalogue</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }

        /* Navbar */
        .navbar {
            height: 75px;
            display: flex;
            align-items: center;
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #363636;
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

        .navbar-logo img {
            height: 60px;
            margin: 0 auto;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            background-color: #363636;
            color: white;
            padding: 14px 20px;
            border: none;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #363636;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
            color: black;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Page Title */
        h1 {
            margin-top: 100px;
            text-align: center;
        }

        /* Product Grid */
        .productGrid {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: center;
            align-items: flex-start;
            margin: 20px auto;
            padding: 20px;
        }

        /* Product Card */
        .productCard {
            width: calc(33.33% - 30px);
            max-width: 300px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background-color: white;
            text-align: center;
            padding: 15px;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .productCard:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .productImage img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            transition: transform 0.3s ease-in-out;
        }

        .productImage:hover img {
            transform: scale(1.05);
        }

        .productLink {
            text-decoration: none;
            color: #363636;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            display: block;
            transition: color 0.2s ease-in-out;
        }

        .productLink:hover {
            color: #d4af37;
        }

        .productPrice {
            font-size: 16px;
            color: #5c4033;
            margin: 10px 0;
            font-weight: bold;
        }

        /* Buttons */
        .buttons {
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
        }

        .buttons button {
            padding: 8px 15px;
            border: none;
            background-color: #d4af37;
            color: white;
            font-weight: bold;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
        }

        .buttons button:hover {
            background-color: #5c4033;
        }

        .buttons button:active {
            transform: translateY(1px);
        }
    </style>
</head>

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

    <h1>Product Catalogue</h1>
        <!-- search bar -->
        <div class="search">
            <input type="text" placeholder="Search for a product..." />
        </div>
        <div class="sortBy">
            <button class="dropbtn">Sort By: &#8595</button>
            <div class="sort">
                <div>
                    <input type="radio" id="Low-to-High" name="sortBy" value="£400-£1000">
                    <label for="Low-to-High">Low-to-High</label>
                </div>
                <div>
                    <input type="radio" id="High-to-Low" name="sortBy" value="£1000-£2000">
                    <label for="High-to-Low">High-to-Low</label>
                </div>
                <div>
                    <input type="radio" id="Latest" name="sortBy" value="£2000-£4000">
                    <label for="Latest">Latest</label>
                </div>
            </div>
        </div>

        <div id="filterSortBar">
            <form method="post" action="products_page.php"> <!-- Set your action script for processing the filter -->
                <div class="dropdownFilter">
                    <button class="dropbutton">Colour &#8595</button>
                    <div class="filterOptions">
                        <div>
                            <input type="checkbox" id="gold" name="color[]" value="Gold">
                            <label for="gold">Gold</label>
                        </div>
                        <div>
                            <input type="checkbox" id="silver" name="color[]" value="Silver">
                            <label for="silver">Silver</label>
                        </div>
                        <div>
                            <input type="checkbox" id="black" name="color[]" value="Black">
                            <label for="black">Black</label>
                        </div>
                        <div>
                            <input type="checkbox" id="white" name="color[]" value="White">
                            <label for="white">White</label>
                        </div>
                    </div>
                </div>
                <div class="dropdownFilter">
                    <button class="dropbutton">Gender &#8595</button>
                    <div class="filterOptions">
                        <div>
                            <input type="checkbox" id="women" name="gender[]" value="Women">
                            <label for="women">Women</label>
                        </div>
                        <div>
                            <input type="checkbox" id="men" name="gender[]" value="Men">
                            <label for="men">Men</label>
                        </div>
                        <div>
                            <input type="checkbox" id="unisex" name="gender[]" value="Unisex">
                            <label for="unisex">Unisex</label>
                        </div>
                    </div>
                </div>
                <div class="dropdownFilter">
                    <button class="dropbutton">Price &#8595</button>
                    <div class="filterOptions">
                        <div>
                            <input type="checkbox" id="price1" name="priceRange[]" value="£0-£1000">
                            <label for="price1">£0-£1000</label>
                        </div>
                        <div>
                            <input type="checkbox" id="price2" name="priceRange[]" value="£1000-£2000">
                            <label for="price2">£1000-£2000</label>
                        </div>
                        <div>
                            <input type="checkbox" id="price3" name="priceRange[]" value="£2000-£4000">
                            <label for="price3">£2000-£4000</label>
                        </div>
                        <div>
                            <input type="checkbox" id="price4" name="priceRange[]" value="£4000-£5000">
                            <label for="price4">£4000-£5000</label>
                        </div>
                    </div>
                </div>
                <input type="submit" value="Filter">
            </form>
        </div>

    <!-- Product Grid -->
    <div class="productGrid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="productCard">
                    <div class="productImage">
                        <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                    </div>
                    <a class="productLink" href="productDetails.php?id=<?= $product['product_id']; ?>">
                        <h3><?= htmlspecialchars($product['name']); ?></h3>
                    </a>
                    <p class="productPrice"><?= htmlspecialchars($product['price']); ?></p>
                    <div class="buttons">
                        <button class="addToCart">Add to cart</button>
                        <button class="saveToWishlist">Save to wishlist</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center;">No products found.</p>
        <?php endif; ?>
    </div>

    <footer>
        <!-- Add footer content here -->
    </footer>
</body>

</html>
