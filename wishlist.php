<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
try {
    $sql = "SELECT w.product_id, w.product_name, p.price, p.image 
            FROM wishlist w
            JOIN products p ON w.product_id = p.product_id
            WHERE w.user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $wishlist_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching wishlist items: " . $e->getMessage());
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
    <link rel="stylesheet" href="pp.css"/>
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
            font-family:'Poppins',sans-serif;
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
 
    </style>
</head>

    <header>
    <!-- NavBar Icons-->
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
            <a href="returns.php"><i class="fas fa-undo-alt"></i> Returns</a>
        </div>
    </div>
    <a href="homepage.php"><i class="fas fa-home"></i> HOME</a>
    <a href="products_page.php"><i class="fas fa-box-open"></i> PRODUCTS</a>
    <div class="navbar-logo">
        <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
    </div>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="profile.php"><i class="fas fa-user"></i> PROFILE</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
    <?php else: ?>
        <a href="login.php"><i class="fas fa-sign-in-alt"></i> LOGIN</a>
    <?php endif; ?>
    <a href="cart.php"><i class="fas fa-shopping-basket"></i> BASKET</a>
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="admin_page.php"><i class="fas fa-user-shield"></i> ADMIN</a>
    <?php endif; ?>
</div>
</header>
<body>
    <h1>Wishlist</h1>
    <div class="productGrid">
        <?php if (!empty($wishlist_items)): ?>
            <?php foreach ($wishlist_items as $item): ?>
                <div class="productCard">
                    <div class="productImage">
                        <img src="<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['product_name']); ?>">
                    </div>
                    <a class="productLink" href="product_details.php?id=<?= $item['product_id']; ?>">
                        <h3><?= htmlspecialchars($item['product_name']); ?></h3>
                    </a>
                    <p class="productPrice"><?= htmlspecialchars($item['price']); ?></p>
                   <div class="buttons">
                    <form action="add_to_cart.php" method="POST" style="display: inline;">
                     <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                     <input type="hidden" name="name" value="<?= htmlspecialchars($item['product_name']); ?>">
                     <input type="hidden" name="description" value="<?= htmlspecialchars($item['description']); ?>">
                     <input type="hidden" name="price" value="<?= $item['price']; ?>">
                    <button type="submit" class="addToCart">Add to cart</button>
                  </form>
                    <form action="remove_from_wishlist.php" method="POST" style="display: inline;">
                     <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                     <button type="submit" class="remove">Remove from wishlist</button>
                     </form>
                  </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center;">Your wishlist is empty.</p>
        <?php endif; ?>
    </div>
</body>
<a class="products_page" href="products_page.php">Back to products page</a>
</html>
