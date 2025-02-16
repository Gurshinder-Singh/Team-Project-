<?php
session_start();
require 'db.php';

$search = isset($_POST['search']) ? $_POST['search'] : '';

// SQL injection prevention
$search = htmlspecialchars($search);

try {
    // SQL query search filter
    $sql = "SELECT DISTINCT product_id, name, description, price, image FROM products";

    if (!empty($search)) {
        $sql .= " WHERE name LIKE :search OR description LIKE :search";
    }
    $stmt = $conn->prepare($sql);

    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }

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
		.search-bar {
        display: flex;
        justify-content: flex-end; /* Align to the right */
        margin: 20px 0;
        padding: 10px;
    }

    .search-bar input[type="text"] {
        width: 200px; /* Smaller width */
        padding: 5px; /* Smaller padding */
        border: 1px solid #ccc;
        border-radius: 5px 0 0 5px;
    }

    .search-bar button {
        padding: 5px 10px; /* Smaller padding */
        border: 1px solid #ccc;
        border-left: none; /* Remove border between input and button */
        border-radius: 0 5px 5px 0;
        background-color: #333;
        color: #fff;
        cursor: pointer;
    }

    .search-bar button:hover {
        background-color: #555;
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

    <h1>Product Catalogue</h1>
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
                         <form method="POST" action="" class="search-bar">
    <input type="text" name="search" placeholder="Search for a product..." 
           value="<?= htmlspecialchars($search); ?>" />
    <button type="submit">Search</button>
</form>

    </form>
        <div class="sortBy">
            <button class="dropbutton">Sort By: &#8595</button>
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
                <a class="productLink" href="product_details.php?id=<?= $product['product_id']; ?>">
                    <h3><?= htmlspecialchars($product['name']); ?></h3>
                </a>
                <p class="productPrice"><?= htmlspecialchars($product['price']); ?></p>
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
                <div class="buttons">
                    <button class="saveToWishlist">Save to wishlist</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center;">No products found.</p>
    <?php endif; ?>
</div>
</div>


    <footer>
        <!-- Add footer content here -->
    </footer>
    
</body>

</html>


