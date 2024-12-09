<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Luxus Product Catalogue</title>
    <link rel="stylesheet" href="stylesheet.css">
    <script defer src="script.js"></script>
</head>
<header>
    <H1>Product Catalogue</H1>
    <nav>
        <div class="navbar" id="navbar">
            <a href="#menu">HOME</a>
            <a href="#search">SEARCH</a>
            <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
            <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
            <a href="#wishlist">PROFILE</a>
            <?php endif; ?>
            <a href="#cart">BASKET</a>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <a href="admin_page.php">ADMIN</a>
            <?php endif; ?>
        </div>

        <!-- CSS -->
        <style>
            .navbar {
                height: 50px;
                display: flex;
                align-items: center;
            }

            .navbar img {
                height: 170%;
                
                max-height: 170%;
            }
        </style>

    </nav>
</header>


<body>
   <div class="productGrid">
        <div class="productCard">
            <div class="productImage">
                
                <img src="https://www.tagheuer.com/on/demandware.static/-/Sites-tagheuer-master/default/dw282920b9/TAG_Heuer_Connected_/SBR8010.BC6608/SBR8010.BC6608_0913.png?impolicy=producttile&width=500&height=650" alt="Tag Heuer Connected Calibre E4">
                <a class="productLink" href="">
                    <h3>Tag Heuer Connected Calibre E4</h3>
                </a>
            </div>
            <p class="productPrice">£1,200</p>
            <div class="buttons">
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="1">
                    <input type="hidden" name="name" value="Tag Heuer Connected Calibre E4">
                    <input type="hidden" name="description" value="Luxury men's smart watch with advanced features.">
                    <input type="hidden" name="price" value="1200.00">
                    <button type="submit">Add to Cart</button>
                </form>
                <form action="add_to_wishlist.php" method="POST">
                    <input type="hidden" name="product_id" value="1">
                    <input type="hidden" name="name" value="Tag Heuer Connected Calibre E4">
                    <input type="hidden" name="description" value="Luxury men's smart watch with advanced features.">
                    <input type="hidden" name="price" value="1200.00">
                    <button type="submit">Add to Wishlist</button>
                </form>
            </div>
        </div>

        <div class="productCard">
            <div class="productImage">
               
                <img src="https://www.tagheuer.com/on/demandware.static/-/Sites-tagheuer-master/default/dw0b4db143/TAG_Heuer_Carrera/WBN2351.BD0000/WBN2351.BD0000_0913.png?impolicy=producttile&width=500&height=650" alt="Tag Heuer Carrera Date">
                <a class="productLink" href="">
                    <h3>Tag Heuer Carrera Date</h3>
                </a>
            </div>
            <p class="productPrice">£6,200</p>
            <div class="buttons">
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="2">
                    <input type="hidden" name="name" value="Tag Heuer Carrera Date">
                    <input type="hidden" name="description" value="Elegant and timeless design for men.">
                    <input type="hidden" name="price" value="6200.00">
                    <button type="submit">Add to Cart</button>
                </form>
                <form action="add_to_wishlist.php" method="POST">
                    <input type="hidden" name="product_id" value="2">
                    <input type="hidden" name="name" value="Tag Heuer Carrera Date">
                    <input type="hidden" name="description" value="Elegant and timeless design for men.">
                    <input type="hidden" name="price" value="6200.00">
                    <button type="submit">Add to Wishlist</button>
                </form>
            </div>
        </div>
    </div>

</body>



<footer>

</footer>

</html>
</DOCTYPE>