<?php
session_start();  


if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $total = 0;  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Basket</title>
    <link rel="stylesheet" href="stylesheet.css">
    <script defer src="script.js"></script>
</head>
<header style="height: 10%;">
    <h1>Cart</h1>
    <a href="#" class="logo"><img src="logo.png" alt="Your Logo"></a>
    <nav>
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
                    <a href="profile.html">PROFILE</a>
                <?php endif; ?>
                <a href="checkout.php">BASKET</a>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <a href="admin_page.php">ADMIN</a>
                <?php endif; ?>
            </div>
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
                <style>
                    .navbar {
                        height: 75px; /* Set your desired navbar height */
                        display: flex;
                        align-items: center;
                        position: fixed;
                        width: 100%;
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
                        transform: translateX(-100px); /* Shift everything else left by 100px */
                    }
                    
                    .navbar-logo {
                        display: flex; /* Ensure image aligns in the center */
                        justify-content: center;
                        align-items: center;
                        position: relative; /* Position the container relative for absolute centering */
                        max-width: 200px; /* Ensure the container space remains the same */
                    }
                    
                    .navbar-logo img {
                        height: 95px; /* Increase the image size by 50px */
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
                        transform: translateX(0); /* Initial position */
                        transition: transform 0.3s ease-in-out; /* Smooth transition */
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
                        position: absolute;
                    }
                    
                    .image-container img {
                        width: 400px; /* Adjust the width to make the image smaller */
                        height: auto; /* Maintain the aspect ratio */
                        position: absolute;
                        left: 1000px; /* Move the image 320 pixels to the right */
                        top: 200px;
                    }
                    </style>
    </nav>
</header>

<body>
    <table id="buyItems">
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
          
            foreach ($_SESSION['cart'] as $item) {
                $item_total = $item['price'] * $item['quantity'];  
                $total += $item_total;  
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td>
                        <form action="update_quantity.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="99">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>£<?php echo number_format($item['price'], 2); ?></td>
                    <td>£<?php echo number_format($item_total, 2); ?></td>
                    <td>
                        <form action="remove_from_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td></td>
                <td></td>
                <td></td>
                <td>£<?php echo number_format($total, 2); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <form action="checkout.php" method="POST">
        <button type="submit" class="checkoutBtn">Proceed to Checkout</button>
    </form>
</body>
<footer>
 
</footer>
</html>
<?php
} else {
    echo "<p>Your cart is empty. <a href='index.php'>Continue shopping</a></p>";
}
?>
