<?php
session_start();
require 'db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product not found.");
}

$product_id = $_GET['id'];

try {
    $sql = "SELECT * FROM products WHERE product_id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found.");
    }

    $sql = "SELECT fullname, Rating, Review, reply FROM CustomerFeedback WHERE product_id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="stylesheet.css"/>
    NAVBAR TO USE 
<!-- NAVIGATION BAR -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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
        background-color: #e6e6e6;
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
    		z-index 1000;
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

    .image-container {
        display: flex;
        justify-content: center;
        position: relative;
    }

    .image-container img {
        width: auto; 
        height: auto; 
        max-width: 100%;
        max-height: 500px;
        transition: transform 0.3s ease-in-out;
        display: block;
        margin: 0 auto;
    }

    body, html {
        height: 100%;
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background-color: #e6e6e6;
        color: black; 
    }
    
    .singleProduct {
        position: relative;
        top: 100px;
    }

    .singleProductImage {
        position: relative;
        text-align: center;
        margin-right: 10px;
    }

    .singleProductImage img {
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 500px;
        transition: transform 0.3s ease-in-out;
        display: block;
        margin: 0 auto;
    }

    .zoom-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        border: none;
        border-radius: 0;
        width: 30px;
        height: 30px;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.3s ease;
        z-index: 10;
    }

    .zoom-btn:hover {
        background-color: rgba(0, 0, 0, 0.9);
    }

    .img-magnifier-container {
        position: relative;
        display: inline-block;
    }

    .img-magnifier-glass {
        position: absolute;
        border: 3px solid #fff; 
        border-radius: 0;
        cursor: none;
        width: 200px;
        height: 200px;
        display: none;
        background-repeat: no-repeat;
        background-size: 200% 200%;
        pointer-events: none; 
        transform: translate(-50%, -50%); 
    }

    img {
        display: block;
        width: 400px; 
    }

    
    .singleProductName {
        color: black; 
    }
    
    .productDescription,
    .productBrand,
    .productColor {
        font-weight: normal;
        color: #363636;
    }

    .singleProductPrice {
        color: #D4AF37; 
        outline: 5px #363636;
    }

    .saveToWishlist {
        background-color: white;
        border-radius: 8px;
        width: 90%;
        max-width: 300px;
        font-size: 14px;
        font-weight: bold;
        display: inline-block;
    }

    .addToCart {
        display: inline-block;
        background-color: white;
    }

    .bottomHalf {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 16px;
        margin: 16px 0;
    }

    .customerReview, .adminReply {
        background-color: white;
        color: #363636; 
        box-shadow: 1px grey;
        padding: 5px;
        margin: 0;
    }

    .review::after {
        content: "";
        clear: both;
        display: table;
    }

    .review span {
        font-size: 20px;
        margin-right: 15px;
    }

    .review .customerName {
        font-size: 10px;
    }

    .rating {
        color: #D4AF37;
    }

    @media (max-width: 500px) {
        .container {
            text-align: center;
        }
    }

    .dark-mode {
        background-color: #1e1e1e;
        color: white; 
    }

    .dark-mode .footer {
        background-color: #111;
        color: gold;
    }

 
    .dark-mode .singleProductName {
        color: white; 
    }

    .dark-mode .productDescription,
    .dark-mode .productBrand,
    .dark-mode .productColor {
        color: white;
    }

    .dark-mode .singleProductPrice {
        color: #D4AF37; 
    }

    .dark-mode .saveToWishlist,
    .dark-mode .addToCart {
        background-color: #444; 
        color: white; 
    }

    .dark-mode .customerReview,
    .dark-mode .adminReply {
        background-color: #000; 
        color: white; 
    }

</style>


    
    <!-- NavBar Icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<header>
<!-- NAVIGATION BAR -->
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
<!-- PRODUCT DISPLAY-->

<div class="singleProduct">
    <div class="topHalf">
        <div class="firstCol">
            <div class="SingleProductImage img-magnifier-container">
                <img id="product-image" src="<?= htmlspecialchars($product['image']); ?>" 
                     alt="<?= htmlspecialchars($product['name']); ?>">
                <button id="zoom-btn" class="zoom-btn">🔍</button>
            </div>
        </div>
        <div class="secondCol">
            <h3 class="singleProductName">
                <?= htmlspecialchars($product['name']); ?>
            </h3>
            <p class="singleProductPrice">
                <?= '£' . number_format((float) str_replace('£', '', $product['price']), 2); ?>
            </p>
            <p class="productBrand">
                <strong>Brand:</strong> <?= htmlspecialchars($product['brand']); ?>
            </p>
     
            <p class="productColor">
                <strong>Color:</strong> <?= htmlspecialchars($product['color']); ?>
            </p>       
             <p class="productDescription">
                <strong>Description:</strong> <?= htmlspecialchars($product['description']); ?>
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
                        <button class="addToCart" type="button" disabled>Out of Stock</button>
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
    </div>
    
    <div class="bottomHalf">
        <h1>Reviews</h1>
        <div class="review">
            <?php foreach ($feedbacks as $feedback): ?>
                <p class="customerName"><?= $feedback['fullname'] ?> <span class="rating">
                <?php
                    $rating = $feedback['Rating'];
                    for ($i = 0; $i < $rating; $i++) {
                        echo "★";
                    }
                    for ($i = $rating; $i < 5; $i++) {
                        echo "☆";
                    }
                ?>
                </span></p>
                <p class="customerReview"><?= $feedback['Review'] ?></p>

                <?php if (!empty($feedback['reply'])): ?>
                    <p class="adminReply">
                        <strong> Reply:</strong> <?= $feedback['reply'] ?>
                    </p>
                <?php endif; ?>

            <?php endforeach; ?>
        </div>
    </div>
            <footer style="
            background-color: #2c2c2c;
            color: white;
            padding: 10px 15px;
            text-align: center;
            font-size: 13px;
            margin-top: auto;
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



</div>
            

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const image = document.getElementById("product-image");
        const zoomBtn = document.getElementById("zoom-btn");
        let magnifierGlass;

        zoomBtn.addEventListener("click", function () {
            if (!magnifierGlass) {
                magnifierGlass = document.createElement("div");
                magnifierGlass.setAttribute("class", "img-magnifier-glass");
                image.parentElement.appendChild(magnifierGlass);
                magnify(image, magnifierGlass);
                zoomBtn.textContent = "❌"; 
            } else {
                magnifierGlass.remove();
                magnifierGlass = null;
                zoomBtn.textContent = "🔍"; 
            }
        });

        function magnify(img, glass) {
            let bw = 3; // Border width
            let w = glass.offsetWidth / 2;
            let h = glass.offsetHeight / 2;

            glass.style.display = "block";
            glass.style.backgroundImage = `url('${img.src}')`;
            glass.style.backgroundSize = img.width * 2 + "px " + img.height * 2 + "px";

            img.parentElement.addEventListener("mousemove", moveMagnifier);
            glass.addEventListener("mousemove", moveMagnifier);
            img.parentElement.addEventListener("touchmove", moveMagnifier);

            function moveMagnifier(e) {
                let pos = getCursorPos(e);
                let x = pos.x;
                let y = pos.y;

                if (x > img.width - w / 3) { x = img.width - w / 3; }
                if (x < w / 3) { x = w / 3; }
                if (y > img.height - h / 3) { y = img.height - h / 3; }
                if (y < h / 3) { y = h / 3; }

                glass.style.left = x - w + "px";
                glass.style.top = y - h + "px";
                glass.style.backgroundPosition = `-${x * 2 - w + bw}px -${y * 2 - h + bw}px`;
            }

            function getCursorPos(e) {
                let a = img.getBoundingClientRect();
                let x = e.pageX - a.left - window.scrollX;
                let y = e.pageY - a.top - window.scrollY;
                return { x: x, y: y };
            }
        }
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
