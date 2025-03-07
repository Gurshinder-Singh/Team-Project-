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
    <link rel="stylesheet" href="stylesheet.css"/>

    <style>
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

        /* Background color fix to match screenshot */
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #3B2314; /* Correct brown from the screenshot */
            color: white; /* Ensures all text is readable */
        }

        /* Product Image Container */
        .singleProductImage {
            position: relative;
            text-align: center;
        }

        .singleProductImage img {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 500px;
            transition: transform 0.3s ease-in-out;
        }

        /* Magnifying Glass Button */
        .zoom-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
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

        /* Magnifier Glass Effect */
        .img-magnifier-container {
            position: relative;
        }

        .img-magnifier-glass {
            position: absolute;
            border: 3px solid #000;
            border-radius: 50%;
            cursor: none;
            width: 100px;
            height: 100px;
            display: none;
            background-repeat: no-repeat;
            background-size: 200% 200%;
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
            color: white;
        }

        /* Ensure Product Name & Other Text is White */
        .singleProductName,
        .productBrand,
        .productDescription,
        .productColor {
            color: white; /* Change all text to white */
        }

        .singleProductPrice {
            color: #FFD700; /* Keep price in gold */
        }
    </style>   
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
</head>
<header>


    
</header>    

<body>

<div class="singleProduct">
    <div class="topHalf">
        <div class="firstCol">
            <div class="singleProductImage img-magnifier-container">
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
            <p class="productDescription">
                <strong>Description:</strong> <?= htmlspecialchars($product['description']); ?>
            </p>
            <p class="productColor">
                <strong>Color:</strong> <?= htmlspecialchars($product['color']); ?>
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
                <button class="saveToWishlist">Save to wishlist</button>
            </div>
        </div>
    </div>
    
   <div class="bottomHalf">
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
                zoomBtn.textContent = "❌"; // Change button to remove zoom
            } else {
                magnifierGlass.remove();
                magnifierGlass = null;
                zoomBtn.textContent = "🔍"; // Change button back to zoom
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
</script>

</body>
</html>





