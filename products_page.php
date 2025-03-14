<?php
session_start();
require 'db.php';

$search = isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '';

$filters = [];
$params = [];

if (!empty($search)) {
    $filters[] = "(name LIKE :search OR description LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

// brand filtering
if (!empty($_POST['brand'])) {
    $colorFilters = [];
    foreach ($_POST['brand'] as $index => $brand) {
        $key = ":brand$index";
        $brandFilters[] = $key;
        $params[$key] = $brand;
    }
    $filters[] = "brand IN (" . implode(',', $brandFilters) . ")";
}

// color filtering
if (!empty($_POST['color'])) {
    $colorFilters = [];
    foreach ($_POST['color'] as $index => $color) {
        $key = ":color$index";
        $colorFilters[] = $key;
        $params[$key] = $color;
    }
    $filters[] = "color IN (" . implode(',', $colorFilters) . ")";
}

if (!empty($_POST['gender'])) {
    $genderFilters = [];
    foreach ($_POST['gender'] as $index => $gender) {
        $key = ":gender$index";
        $genderFilters[] = $key;
        $params[$key] = $gender;
    }
    $filters[] = "gender IN (" . implode(',', $genderFilters) . ")";
}

// price filtering
if (!empty($_POST['priceRange'])) {
    $priceFilters = [];
    foreach ($_POST['priceRange'] as $index => $range) {
        // removes '£' and extract min & max prices
        $range = str_replace('£', '', $range);
        list($min, $max) = explode('-', $range);

        // convert to integers
<<<<<<< HEAD
        $min = (int)trim($min);
        $max = (int)trim($max);
        $keyMin = ":priceMin$index";
        $keyMax = ":priceMax$index";
        $priceFilters[] = "(CAST(REPLACE(price, '£', '') AS UNSIGNED) BETWEEN $keyMin AND $keyMax)";   
=======
        $min = (int) trim($min);
        $max = (int) trim($max);
        $keyMin = ":priceMin$index";
        $keyMax = ":priceMax$index";
        $priceFilters[] = "(CAST(REPLACE(price, '£', '') AS UNSIGNED) BETWEEN $keyMin AND $keyMax)";
>>>>>>> ff46d2dd73de8bdc26ac93e0c5a2053b67499047
        $params[$keyMin] = $min;
        $params[$keyMax] = $max;
    }
    $filters[] = "(" . implode(' OR ', $priceFilters) . ")";
}


// SQL query
$sql = "SELECT DISTINCT product_id, name, description, price, image, stock, color FROM products";

if (!empty($filters)) {
    $sql .= " WHERE " . implode(' AND ', $filters);
}

try {
    $stmt = $conn->prepare($sql);
    foreach ($params as $key => $value) {
<<<<<<< HEAD
    $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
=======
        $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
>>>>>>> ff46d2dd73de8bdc26ac93e0c5a2053b67499047

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
    <link rel="stylesheet" href="stylesheet.css" />
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

        body,
        html {
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

        .footer {
            background-color: #363636;
            color: gold;
            text-align: center;
            height: auto;
            padding: 20px;
            width: 100%;
            position: relative;
            bottom: 0;
        }

        .footer-content {
            max-width: 1200px;
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

        .wishlist {
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 0 5px 5px 0;
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }

        .filter {
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 0 5px 5px 0;
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }

        .filter button:hover {
            background-color: #ccc;
            color: #fff;
        }

        .dropdownFilter {
            position: relative;
            display: inline-block;
            margin-right: 15px;
            box-shadow: 100px;
        }

        .dropbutton {
            background-color: #363636;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        .dropbutton:hover {
            background-color: #555;
        }

        .filterOptions {
            display: none;
            position: absolute;
            background-color: #363636;
            color: white;
            min-width: 150px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 2;
            padding: 10px;
        }

        .filterOptions div {
            display: flex;
            align-items: center;
            justify-content: left;
            padding: 5px 10px;
        }

        .filterOptions input[type="checkbox"] {
            margin-right: 8px;
        }

        .filterOptions label {
            color: white;
            cursor: pointer;
            text-align: left;
            white-space: nowrap;
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
            box-shadow: 0px 8px 200px 0px rgba(0, 0, 0, 0.2);
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
            color: white;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

		.productGrid {
     margin: 20px; /* Add margin around the grid */
}



        .search-bar {
            display: flex;
            justify-content: flex-end;
            margin: 20px 0;
            padding: 10px;
        }

        .search-bar input[type="text"] {
            width: 200px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
        }

        .search-bar button {
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-left: none;
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

        window.onscroll = function () {
            clearTimeout(debounce);

            debounce = setTimeout(function () {
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

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile.php">PROFILE</a>
            <a href="logout.php">LOGOUT</a>
        <?php elseif (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
            <a href="login.php">LOGIN</a>
        <?php endif; ?>
        <a href="checkout.php">BASKET</a>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <a href="admin_page.php">ADMIN</a>
        <?php endif; ?>
    </div>

    <h1>Product Catalogue</h1>
<<<<<<< HEAD
                   <div id="filterSortBar">
            <form method="post" action="products_page.php"> <!-- Set your action script for processing the filter -->
            <div class="dropdownFilter">
                    <button class="dropbutton">Brand &#8595</button>
                    <div class="filterOptions">
                        <div>
                            <input type="checkbox" id="omega" name="brand[]" value="Omega">
                            <label for="omega">Omega</label>
                        </div>
                        <div>
                            <input type="checkbox" id="tudor" name="brand[]" value="Tudor">
                            <label for="tudor">Tudor</label>
                        </div>
                        <div>
                            <input type="checkbox" id="bvlagri" name="brand[]" value="Bvlagri">
                            <label for="bvlagri">Bvlagri</label>
                        </div>
                        <div>
                            <input type="checkbox" id="tag Heuer" name="brand[]" value="Tag Heuer">
                            <label for="tag Heuer">Tag Heuer</label>
                        </div>
                    </div>
                </div>
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
                        <div>
                            <input type="checkbox" id="steel" name="color[]" value="Steel">
                            <label for="Steel">Steel</label>
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
                            <label for="price1">£0-£1000</label></div>
                        <div>
                            <input type="checkbox" id="price2" name="priceRange[]" value="£1000-£2000">
                            <label for="price2">£1000-£2000</label>
                        </div>
                        <div>
                            <input type="checkbox" id="price3" name="priceRange[]" value="£2000-£4000">
                            <label for="price3">£2000-£4000</label>
                        </div>
                        <div>
                            <input type="checkbox" id="price4" name="priceRange[]" value="£4000-£6000">
                            <label for="price4">£4000-£6000</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="filter">FILTER</button>
                    
                            
                            
                            
                         <form method="POST" action="" class="search-bar">
    <input type="text" name="search" placeholder="Search for a product..." 
           value="<?= htmlspecialchars($search); ?>" />
    <button type="submit">Search</button>
</form>

                

                            
   <a class="wishlist" href="wishlist.php">WISHLIST</a> <!-- Add this line -->



<?php if (isset($_GET['wishlist'])): ?>
    <?php if ($_GET['wishlist'] == 'success'): ?>
        <p style="color: green; text-align: center;">Product added to wishlist successfully!</p>
    <?php elseif ($_GET['wishlist'] == 'duplicate'): ?>
        <p style="color: red; text-align: center;">This product is already in your wishlist!</p>
    <?php endif; ?>
<?php endif; ?>
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
                  <form action="add_to_wishlist.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($product['name']); ?>">
                    <input type="hidden" name="price" value="<?= htmlspecialchars($product['price']); ?>">
                    <input type="hidden" name="description" value="<?= htmlspecialchars($product['description']); ?>">
                    <input type="hidden" name="image" value="<?= htmlspecialchars($product['image']); ?>">
                    <button type="submit">Add to Wishlist</button>
                 </form>


=======
    <div id="filterSortBar">
        <form method="post" action="products_page.php"> <!-- Set your action script for processing the filter -->
            <div class="dropdownFilter">
                <button class="dropbutton">Brand &#8595</button>
                <div class="filterOptions">
                    <div>
                        <input type="checkbox" id="omega" name="brand[]" value="Omega">
                        <label for="omega">Omega</label>
                    </div>
                    <div>
                        <input type="checkbox" id="tudor" name="brand[]" value="Tudor">
                        <label for="tudor">Tudor</label>
                    </div>
                    <div>
                        <input type="checkbox" id="bvlagri" name="brand[]" value="Bvlagri">
                        <label for="bvlagri">Bvlagri</label>
                    </div>
                    <div>
                        <input type="checkbox" id="tag Heuer" name="brand[]" value="Tag Heuer">
                        <label for="tag Heuer">Tag Heuer</label>
                    </div>
                </div>
>>>>>>> ff46d2dd73de8bdc26ac93e0c5a2053b67499047
            </div>
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
                    <div>
                        <input type="checkbox" id="steel" name="color[]" value="Steel">
                        <label for="Steel">Steel</label>
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
                        <input type="checkbox" id="price4" name="priceRange[]" value="£4000-£6000">
                        <label for="price4">£4000-£6000</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="filter">FILTER</button>

            <form method="POST" action="" class="search-bar">
                <input type="text" name="search" placeholder="Search for a product..."
                    value="<?= htmlspecialchars($search); ?>" />
                <button type="submit">Search</button>
            </form>

            <a class="wishlist" href="wishlist.php">WISHLIST</a>

            <?php if (isset($_GET['wishlist'])): ?>
                <?php if ($_GET['wishlist'] == 'success'): ?>
                    <p style="color: green; text-align: center;">Product added to wishlist successfully!</p>
                <?php elseif ($_GET['wishlist'] == 'duplicate'): ?>
                    <p style="color: red; text-align: center;">This product is already in your wishlist!</p>
                <?php endif; ?>
            <?php endif; ?>
            <!-- Product Grid -->
            <div class="productGrid">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="productCard">
                            <div class="productImage">
                                <img src="<?= htmlspecialchars($product['image']); ?>"
                                    alt="<?= htmlspecialchars($product['name']); ?>">
                            </div>
                            <a class="productLink" href="product_details.php?id=<?= $product['product_id']; ?>">
                                <h3><?= htmlspecialchars($product['name']); ?></h3>
                            </a>
                            <p class="productPrice"><?= htmlspecialchars($product['price']); ?></p>
                            <form method="POST" action="add_to_cart.php">
                                <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($product['name']); ?>">
                                <input type="hidden" name="price" value="<?= htmlspecialchars($product['price']); ?>">
                                <input type="hidden" name="description"
                                    value="<?= htmlspecialchars($product['description']); ?>">
                                <?php if ($product['stock'] > 0): ?>
                                    <button class="addToCart" type="submit">Add to cart</button>
                                <?php else: ?>
                                    <button class="addToCart" type="button" disabled style="background-color: gray;">Out of
                                        Stock</button>
                                <?php endif; ?>
                            </form>
                            <form action="add_to_wishlist.php" method="POST">
                                <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($product['name']); ?>">
                                <input type="hidden" name="price" value="<?= htmlspecialchars($product['price']); ?>">
                                <input type="hidden" name="description"
                                    value="<?= htmlspecialchars($product['description']); ?>">
                                <input type="hidden" name="image" value="<?= htmlspecialchars($product['image']); ?>">
                                <button type="submit">Add to Wishlist</button>
                            </form>


                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center;">No products found.</p>
                <?php endif; ?>
            </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Select all dropdown buttons
            const filterButtons = document.querySelectorAll(".dropbutton");

            filterButtons.forEach(button => {
                button.addEventListener("click", function (event) {
                    event.preventDefault(); // Prevent form submission

                    let filterOptions = this.nextElementSibling;

                    document.querySelectorAll(".filterOptions").forEach(option => {
                        if (option !== filterOptions) {
                            option.style.display = "none";
                        }
                    });

                    // Toggle cur	rent dropdown
                    filterOptions.style.display = (filterOptions.style.display === "block") ? "none" : "block";
                });
            });

            // Close filters when outside
            document.addEventListener("click", function (event) {
                if (!event.target.closest(".dropdownFilter")) {
                    document.querySelectorAll(".filterOptions").forEach(option => {
                        option.style.display = "none";
                    });
                }
            });
        });
    </script>


    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2024 LUXUS. All rights reserved.</p>
            <p>Follow us on social media!</p>
            <div class="social-icons">
                <a href="#" class="social-icon">Facebook</a>
                <a href="#" class="social-icon">Instagram</a>
                <a href="#" class="social-icon">Twitter</a>
            </div>
        </div>
    </footer>

</body>

<<<<<<< HEAD
</html>



=======
</html>
>>>>>>> ff46d2dd73de8bdc26ac93e0c5a2053b67499047
