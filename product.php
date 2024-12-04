<?php

include('db.php');


try {
    $query = "SELECT * FROM products"; 
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Luxus Product Catalogue</title>
    <link rel="stylesheet" href="Stylesheet.css">
    <script defer src="script.js"></script>
</head>
<header>
    <H1>Product Catalogue</H1>
    <a href="#" class="logo">
        <img src="logo.png" alt="Your Logo">
    </a>
    <nav>
        <a href="login.html" class="active">Log In</a>
        <a href="signup.html">Sign Up</a>
    </nav>
</header>

<body>
    <div class="sidebar">
        <h2>Sort By</h2>
        <button class="clearAllButton">Clear All</button>
        <h4>Colour</h4>
        <div class="filterTags">
            <button class="tag active" dataFilter="all">All</button>
            <button class="tag" dataFilter="blue">Blue</button>
            <button class="tag" dataFilter="silver">Silver</button>
        </div>
        <h4>Price</h4>
        <div class="filterTags">
            <button class="tag active" dataFilter="all">All</button>
            <button class="tag" dataFilter="blue">High-to-Low</button>
            <button class="tag" dataFilter="silver">Low-to-High</button>
        </div>
        <h4>Brand</h4>
        <div class="filterTags">
            <button class="tag active" dataFilter="all">All</button>
            <button class="tag" dataFilter="blue">Tag Heur</button>
            <button class="tag" dataFilter="silver">Seiko</button>
        </div>
        <h4>Material</h4>
        <div class="filterTags">
            <button class="tag active" dataFilter="all">All</button>
            <button class="tag" dataFilter="blue">Stainless Steel</button>
            <button class="tag" dataFilter="silver">Gold</button>
        </div>
    </div>

    <div class="productGrid">
        <?php foreach ($products as $product): ?>
        <div class="productCard">
            <div class="productImage">
                <img src="<?= $product['image'] ?>" alt="Product Image">
                <a class="productLink" href="">
                    <h3 itemprop="productName"><?= $product['name'] ?></h3>
                </a>
            </div>
            <p class="productPrice">£<?= number_format($product['price'], 2) ?></p>
            <div>
                <a class="productCategoryLink" href="">
                    <h4 itemprop="productCategory"><?= $product['category'] ?></h4>
                </a>
            </div>
            <div class="filterTags">
                <button class="tag"><?= $product['color'] ?></button>
            </div>
            <div class="buttons">
                <button class="addToCart" data-product-id="<?= $product['id'] ?>">Add to cart</button>
                <button class="saveToWishlist">Save to wishlist</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</body>

<footer>

</footer>

</html>
