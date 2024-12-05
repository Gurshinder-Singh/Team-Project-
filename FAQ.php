<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - LUXUS</title>
    <link rel="stylesheet" href="stylesheet.css">
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
    </style>
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
    
    
</body>
</html>

<!-- Updated CSS -->
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
    <div class="main-container">
        <!-- FAQ Section -->
        <main>
            <h1>Frequently Asked Questions</h1>

            <!-- General Questions -->
            <section>
                <h2 onclick="toggleVisibility('general')">General Questions</h2>
                <div id="general" style="display: none;">
                    <ul>
                        <li><strong>What kind of styles of watches does LUXUS offer?</strong> We offer luxury, sport, casual, smartwatch, and classic styles to suit every need.</li>
                        <li><strong>Are your watches genuine?</strong> Yes, all watches come with certificates of authenticity and serial numbers.</li>
                        <li><strong>Is warranty covered on all watches?</strong> Yes, every watch includes a manufacturer’s warranty.</li>
                        <li><strong>Are your watches limited?</strong> Yes, our watches are limited editions due to the craftsmanship involved.</li>
                        <li><strong>Are there any new watch collections?</strong> Not at the moment. We focus on perfecting our current range.</li>
                    </ul>
                </div>
            </section>

            <!-- Payments and Orders -->
            <section>
                <h2 onclick="toggleVisibility('payments')">Payments and Orders</h2>
                <div id="payments" style="display: none;">
                    <ul>
                        <li><strong>How many orders can I place?</strong> You can place as many orders as you like, but combining items in one order saves on shipping.</li>
                        <li><strong>What should I do if my order is not delivered?</strong> Wait 5 more days beyond the expected delivery date and contact us if it has not arrived.</li>
                        <li><strong>Can I change or cancel my order?</strong> Yes, as long as it hasn’t been shipped. Contact us for assistance.</li>
                        <li><strong>Is my payment information secure?</strong> Yes, all payment data is encrypted for your safety.</li>
                        <li><strong>How do I return my order?</strong> Contact us to initiate a return. Refunds are processed within 10-15 days.</li>
                    </ul>
                </div>
            </section>

            <!-- Delivery and Shipping -->
            <section>
                <h2 onclick="toggleVisibility('shipping')">Delivery and Shipping</h2>
                <div id="shipping" style="display: none;">
                    <ul>
                        <li><strong>What are the fastest shipping options available?</strong> Express shipping is available for an additional fee and takes 1 business day.</li>
                        <li><strong>Do you provide international shipping?</strong> Yes, worldwide shipping is available, but delivery times and fees vary by location.</li>
                        <li><strong>How long does shipping take?</strong> Standard shipping takes 3-7 days, depending on your location.</li>
                        <li><strong>Can I track my order?</strong> Yes, tracking details will be emailed to you once the order is dispatched.</li>
                        <li><strong>What if my watch arrives damaged?</strong> Contact us immediately to arrange a replacement or refund.</li>
                    </ul>
                </div>
            </section>

            <!-- Product Details -->
            <section>
                <h2 onclick="toggleVisibility('products')">Product Details</h2>
                <div id="products" style="display: none;">
                    <ul>
                        <li><strong>Will the watch come with a size guide?</strong> Yes, size guides are included with every watch.</li>
                        <li><strong>Can I personalize my watch?</strong> Yes, engraving options are available for select models.</li>
                        <li><strong>Are the watches waterproof?</strong> Watches are waterproof to varying degrees; check the product details for specifics.</li>
                        <li><strong>Do you sell any replacement straps?</strong> Yes, we offer replacement straps and buckles for select models.</li>
                        <li><strong>How do I maintain my watch?</strong> Regular servicing every 4-5 years ensures optimal performance.</li>
                    </ul>
                </div>
            </section>

            <!-- Customer Service -->
            <section>
                <h2 onclick="toggleVisibility('customer-service')">Customer Service</h2>
                <div id="customer-service" style="display: none;">
                    <ul>
                        <li><strong>How can I contact customer service?</strong> You can contact us via email or phone.</li>
                        <li><strong>When is customer service available?</strong> Customer service is available from 8 AM to 6:30 PM.</li>
                        <li><strong>Do you offer any gift cards?</strong> Yes, we offer both physical and digital gift cards.</li>
                        <li><strong>Can I subscribe to updates?</strong> Yes, subscribe using your email to receive updates and discounts.</li>
                        <li><strong>Do you have a loyalty program?</strong> Yes, our loyalty program offers discounts and special offers for regular customers.</li>
                    </ul>
                </div>
            </section>
        </main>
    </div>

    <!-- Toggle Script -->
    <script>
        function toggleVisibility(sectionId) {
            const section = document.getElementById(sectionId);
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
