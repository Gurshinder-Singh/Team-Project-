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
        $range = str_replace('£', '', $range);
        list($min, $max) = explode('-', $range);

        $min = (int) trim($min);
        $max = (int) trim($max);
        $keyMin = ":priceMin$index";
        $keyMax = ":priceMax$index";
        $priceFilters[] = "(CAST(REPLACE(price, '£', '') AS UNSIGNED) BETWEEN $keyMin AND $keyMax)";
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
        $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>

<?php
$darkMode = isset($_COOKIE['darkmode']) && $_COOKIE['darkmode'] == 'true';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Luxus Product Catalogue</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
 	<link rel="icon" type="image/favicon" href="/asset/LUXUS_logo.png"> 
    <link rel="stylesheet" href="pp.css" />

	<link rel="stylesheet" href="stylesheet.css">
    <?php if (!$darkMode): ?>
        <link rel="stylesheet" href="pp.css">
    <?php endif; ?>
    <style>
.content {
            flex: 1;
            position: relative;
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
            z-index: 100;
        }

        .navbar a,
        .navbar-logo {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            flex: 1;
            text-align: center;
            transform: translateX(-100px);
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

        .filter,.wishlist{
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            cursor: pointer;
            display:inline-block;

        }
        .filter{
         width:75px;
        }

        .filter button:hover {
            background-color: #ccc;
            color: #fff;
        }

        .dropdownFilter { 
            display: inline-block;
            box-shadow: 100px;  
        margin-left:5px;        
        position:relative;
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

         .wishlist:hover {
    	 background-color: goldenrod;
    	 color: black;
		}

		.wishlist{
        position:absolute;
        right:20px;
        top:100px;
        border-radius:0;
        text-decoration:none;
		}

		.search {  

        float:none;
        
        margin-left:0;
        }

        .searchBar{
            max-width:400px;
            width: 600px;
            padding: 5px;
            border: 2px solid #363636;
            border-radius: 5px;
            text-align:center;
            margin-right:0;
            margin-left:600px;
            display:inline-block;vertical-align:middle;
        }

        .searchBtn {

            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            background-color:#363636;
            color:white;
            margin:0;
            max-width:50px;display:inline-block;vertical-align:middle;
        }
        .searchBtn:hover{
            background-color:goldenrod;
        }

        .productLink{
            color:#363636;
            padding:0;
            margin-top:10px;
         overflow: hidden;
        text-overflow: ellipsis;
        display:-webkit-box;
        -webkit-line-clamp:2;
        line-clamp:2;
        -webkit-box-orient:vertical;
        height:15%
        }
        .productPrice{
            color:goldenrod;
            margin-top:5px;
            height:5%;
            
        }
        .productCard{
            height:550px;
            border:2px solid goldenrod;
            box-shadow:5px 0 5px 5px lightgrey;
            background-color:white;
        }
        .productImage{
            width:auto;
            height:auto;
            max-width:300px;
            max-height:50%;
            object-fit:cover;
        }
        .productImage img{
           height:300px;
           object-fit:cover;
        }
        .productCard button{
           background-color:white;
           width:auto;
           display:initial;
           margin-bottom:10px;
        }
        .pcBtns{
           height:25%;
           
        }
        .productGrid{
           margin-top:100px;
        }

/* Dark Mode Styles */
.dark-mode {
    background-color: #1e1e1e;
    color: white;
    margin-top:0;
}

.dark-mode .productCard {
    background-color: #1e1e1e;
    color: white;
    border-color: #555;
}

.dark-mode .footer {
    background-color: #111;
    color: gold;
}

.dark-mode .productPrice {
    color: #FFD700;
}

.dark-mode .productLink {
    color: #FFD700;
}

.dark-mode .filterOptions {
    background-color: #1e1e1e;
    color: white;
}

.dark-mode .productCard button, .singleProduct button {
    background-color:#1e1e1e;
    color: #FFD700;
}

.dark-mode .footer {
    background-color: #111;
}

    </style>
    <!-- NavBar Icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

 
    
<header>
<!-- NAVIGATION BAR -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<div class="navbar" id="navbar">
            <div class="dropdown">
                <button class="dropbtn">
                    <img src="asset/menu_icon.png" alt="Menu Icon" class="menu-icon">
                </button>
                <div class="dropdown-content">
                    <a href="about.php"><i class="fas fa-info-circle"></i> About Us</a>
                    <a href="contact.php"><i class="fas fa-envelope"></i> Contact Us</a>
                    <a href="FAQ.php"><i class="fas fa-question-circle"></i> FAQs</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="returns.php"><i class="fas fa-undo-alt"></i> Returns</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
                    <?php endif; ?>
                    <a href="javascript:void(0);" id="darkModeToggle">
                        <i class="fas fa-moon"></i> <span>Dark Mode</span>
                    </a>
                </div>
            </div>
            <a href="homepage.php"><i class="fas fa-home"></i> HOME</a>
            <a href="products_page.php"><i class="fas fa-box-open"></i> PRODUCTS</a>
            <div class="navbar-logo">
                <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
            </div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php"><i class="fas fa-user"></i> PROFILE</a>
            <?php elseif (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="admin_page.php"><i class="fas fa-user-shield"></i> ADMIN</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
            <?php else: ?>
                <a href="login.php"><i class="fas fa-sign-in-alt"></i> LOGIN</a>
            <?php endif; ?>
            <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
                <a href="cart.php"><i class="fas fa-shopping-basket"></i> BASKET</a>
            <?php endif; ?>
        </div>

</header>

    
    
    
<body>
    
<!--SEARCH AND FILTER-->

    <h1>Product Catalogue</h1>
    <div id="filterSortBar">
        <form method="post" action="products_page.php"> 
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
                     <div>
                        <input type="checkbox" id="Tissot" name="brand[]" value="Tissot">
                        <label for="tissot">Tissot</label>
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
                        <input type="checkbox" id="price2" name="priceRange[]" value="£1000-£2000">
                        <label for="price1">£1000-£2000</label>
                    </div>
                    <div>
                        <input type="checkbox" id="price3" name="priceRange[]" value="£2000-£4000">
                        <label for="price2">£2000-£4000</label>
                    </div>
                    <div>
                        <input type="checkbox" id="price4" name="priceRange[]" value="£4000-£6000">
                        <label for="price3">£4000-£6000</label>
                    </div>
                    <div>
                        <input type="checkbox" id="price4" name="priceRange[]" value="£6000-£8000">
                        <label for="price4">£6000-£8000</label>
                    </div>
                    <div>
                        <input type="checkbox" id="price4" name="priceRange[]" value="£8000-£10000">
                        <label for="price5">£8000-£10000</label>
                    </div>    
                    <div>
                        <input type="checkbox" id="price4" name="priceRange[]" value="£10000-£12000">
                        <label for="price6">£10000-£12000</label>
                    </div>    
                </div>
                
            </div>
            <button type="submit" class="filter">FILTER</button>
            <div class="search">
            <form method="POST" action="" class="search-bar">
                <input type="text" name="search" class="searchBar" placeholder="Search for a product..."
                    value="<?= htmlspecialchars($search); ?>" />
                <button type="submit" class="searchBtn">⌕</button>
            </form>
            </div>
            <a class="wishlist" href="wishlist.php">✩WISHLIST✩</a>

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
                      <div class="pcBtns">      
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

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center;">No products found.</p>
                <?php endif; ?>
            </div>
    </div>
<footer style="
            background-color: #2c2c2c;
            color: white;
            padding: 10px 15px;
            margin-top: 50px;
            text-align: center;
            font-size: 13px;
            position: relative;
            width: 100%;
            z-index: 2;
        ">
            <div style="margin-bottom: 10px; font-size: 18px;">
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-facebook-f"></i></a>
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-twitter"></i></a>
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-instagram"></i></a>
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <p style="margin: 0;">&copy; <?= date("Y") ?> LUXUS. All rights reserved.</p>
        </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            
            const filterButtons = document.querySelectorAll(".dropbutton");

            filterButtons.forEach(button => {
                button.addEventListener("click", function (event) {
                    event.preventDefault(); 

                    let filterOptions = this.nextElementSibling;

                    document.querySelectorAll(".filterOptions").forEach(option => {
                        if (option !== filterOptions) {
                            option.style.display = "none";
                        }
                    });

                  
                    filterOptions.style.display = (filterOptions.style.display === "block") ? "none" : "block";
                });
            });

            
            document.addEventListener("click", function (event) {
                if (!event.target.closest(".dropdownFilter")) {
                    document.querySelectorAll(".filterOptions").forEach(option => {
                        option.style.display = "none";
                    });
                }
            });
        });

const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;
    const darkModeIcon = document.querySelector('#darkModeToggle i');
    const darkModeText = darkModeToggle.querySelector('span'); 
    const savedTheme = localStorage.getItem('theme');

    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        darkModeIcon.classList.remove('fa-moon');
        darkModeIcon.classList.add('fa-sun');
        darkModeText.textContent = 'Light Mode'; 
    }

    darkModeToggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');

        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark');
            darkModeIcon.classList.remove('fa-moon');
            darkModeIcon.classList.add('fa-sun');
            darkModeText.textContent = 'Light Mode'; 
        } else {
            localStorage.setItem('theme', 'light');
            darkModeIcon.classList.remove('fa-sun');
            darkModeIcon.classList.add('fa-moon');
            darkModeText.textContent = 'Dark Mode'; 
        }
    });

    </script>

</body>

</html>
