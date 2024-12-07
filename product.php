<?php session_start(); // Start the session ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Luxus Product Catalogue</title>
    <link rel="stylesheet" href="productsPage.css"/>
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
        <a href="search.php">SEARCH</a>
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

    <header>
        <h1>Product Catalogue</h1>
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
        </nav>

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
            <form method="post" action="your_filter_processing_script.php"> <!-- Set your action script for processing the filter -->
                <div class="dropdownFilter">
                    <button class="dropbtn">Colour &#8595</button>
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
                    <button class="dropbtn">Gender &#8595</button>
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
                    <button class="dropbtn">Price &#8595</button>
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
    </header>

    <div class="productGrid"> <!-- connect to database, product details should be filled out on the product card -->
       <div class="productCard">
                <div class="productImage"><img
                        src="https://www.watchshop.com/images/products/86581802_h.jpg"
                        alt="Product Image">
                    <a class="productLink" href="productDetails.hmtl">
                        <h3 itemprop="productName">Seiko Presage Watch</h3>
                    </a>
                </div>
                <p class="productPrice">£960</p>
                <div class="buttons">
                    <button class="addToCart">Add to cart</button>
                    <button class="saveToWishlist">Save to wishlist</button>
                </div>
            </div>
            <div class="productCard">
                <div class="productImage"><img
                        src="https://www.watchshop.com/images/products/75408472_l.jpg"
                        alt="Product Image">
                    <a class="productLink" href="productDetails.html">
                        <h3 itemprop="productName">Tissot Gentleman 40mm Watch</h3>
                    </a>
                </div>
                <p class="productPrice">£1200</p>
                <div class="buttons">
                    <button class="addToCart">Add to cart</button>
                    <button class="saveToWishlist">Save to wishlist</button>
                </div>
            </div>
    </div>

    <footer>
        <!-- Add footer content here -->
    </footer>
</body>

</html>

<style>
    .navbar {
    background-color: #333;
    color: white;
    padding: 10px;
    position: fixed;
    width: 100%;
    top: 0;
    display: flex;
    justify-content: space-around;
    transition: top 0.3s;
    z-index: 1000; /* Ensure navbar stays above background shapes */
}

.navbar a {
    color: white;
    text-decoration: none;
    padding: 10px;
    font-family: 'Century Gothic', sans-serif; /* Set font for navbar links */
    font-weight: bold; /* Ensure navbar links are bold */
    text-transform: uppercase; /* Make navbar links uppercase */
}

.navbar a.luxus-link {
    font-size: calc(100% + 5px); /* Increase font size by 5px */
}

.navbar {
    height: 50px; /* Set your desired navbar height */
    display: flex;
    align-items: center;
}

.navbar img {
    height: 170%; /* Adjust this percentage to make the image bigger */
    max-height: 170%;
}



/*Abida*/
.productGrid{
    display:flex;
    flex-wrap:wrap;
    gap:50px;
    justify-content: center;
    align-items: stretch;
    margin-top: 200px;
    resize: both;
}
/*Product Card*/
.productCard{
    width:50%;
    max-width: 300px;
    max-height: 600px;
    border-radius:20px;
    box-shadow: 5px 5px 5px 5px #15140f;
    margin: auto;
    background-color:none;
    text-align:center;
    align-items: stretch;
}


.productImage img{
    width: 40%;
    height: 20%;
    object-fit:contain;
    border-radius: 50px;
    align-content: center;
}

.productImage:hover img{
    transform:scale(1.2);
}

.productLink:hover{
    text-decoration: underline;
    color:black;
}

.productLink:visited{
    color:black;
}


.productPrice{
    font-size:small;
    font-weight:bold;
    text-align:center;
    margin-bottom:none;
}

.productLink{
    text-decoration: none;
    font-size:relative;
    font-weight:bold;
    margin-top:30px;
    margin-bottom:none;
    font-size: relative;
    align-items: left;
    padding: 5px 0 5px 0;
}

.productCard button,.singleProduct button {
    padding:10px 20px;
    margin:6px;
    background-color:none;
    border:none;
    margin-bottom: none;
    font-weight: bold;
    text-align: center;
    text-decoration: none;
    transition-duration:0.3s;
  }

.productCard button:hover, .singleProduct button:hover{
    background-color: #d4af37;
    color:#fff;
}

.dropdownFilter,.sortBy{
    position: relative;
    display: inline-block;
}

.dropbtn {
    border:none;
    padding:16px;
    margin:0;
}

.filterOptions,.sort{
    display: none;
    position: absolute;
}

.filterOptions a,.sort a{
    display: none;
    padding:16px;
}

.dropdownFilter:hover .filterOptions{
    display: block;
}

.sortBy:hover .sort{
    display: block;
}

.search{
    float: right;
    background-color:none ;
    border:none;
    padding: 1em;
    margin-left: 0;
}

.sortBy{
    float:right;
    border: radius 0.2em;
}

}
</style>
