<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>FAQs Luxus</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css"/>
<style>
        
        body {
            background-color: #5c4033; 
            color: #f0c14b; 
            font-family: 'Century Gothic', sans-serif;
            font-weight: normal;
            margin: 0;
            padding: 0;
        }

        .squircle {
            background: #412920; 
            border-radius: 20px; 
            padding: 40px;
            margin: 50px auto;
            max-width: 900px; 
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5); 
        }

        h1, h2 {
            color: #f0c14b; 
        }

        h2 {
            cursor: pointer;
            text-decoration: underline;
        }

        h2:hover {
            color: white; 
        }

        ul {
            list-style-type: none; 
            padding: 0;
        }

        ul li {
            margin: 10px 0;
        }
/* Navbar */
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
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            transition: transform 0.3s ease-in-out;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
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
    <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
        <a href="profile.html">PROFILE</a>
    <?php endif; ?>
    <a href="checkout.php">BASKET</a>
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="admin_page.php">ADMIN</a>
    <?php endif; ?>
</div>
    <!-- FAQ Section -->
    <main>
        

        <div class="squircle">
        <h1 style="text-align: center;">Frequently Asked Questions</h1>
            <section>
                <h2 onclick="toggleVisibility('general')">General Questions</h2>
                <div id="general" style="display: none;">
                    <ul>
                        <li><strong>What kind of styles of watches does LUXUS offer?</strong> We offer luxury, sport, casual, smartwatch, and classic styles to suit every need.</li>
                        <li><strong>Are your watches genuine?</strong> Yes, all watches come with certificates of authenticity and serial numbers.</li>
                        <li><strong>Is warranty covered on all watches?</strong> Yes, every watch includes a manufacturer’s warranty.</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 onclick="toggleVisibility('payments')">Payments and Orders</h2>
                <div id="payments" style="display: none;">
                    <ul>
                        <li><strong>How many orders can I place?</strong> You can place as many orders as you like, but combining items in one order saves on shipping.</li>
                        <li><strong>What should I do if my order is not delivered?</strong> Wait 5 more days beyond the expected delivery date and contact us if it has not arrived.</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 onclick="toggleVisibility('shipping')">Delivery and Shipping</h2>
                <div id="shipping" style="display: none;">
                    <ul>
                        <li><strong>What are the fastest shipping options available?</strong> Express shipping is available for an additional fee and takes 1 business day.</li>
                        <li><strong>Do you provide international shipping?</strong> Yes, worldwide shipping is available.</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 onclick="toggleVisibility('products')">Product Details</h2>
                <div id="products" style="display: none;">
                    <ul>
                        <li><strong>Will the watch come with a size guide?</strong> Yes, size guides are included with every watch.</li>
                        <li><strong>Can I personalize my watch?</strong> Yes, engraving options are available for select models.</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 onclick="toggleVisibility('customer-service')">Customer Service</h2>
                <div id="customer-service" style="display: none;">
                    <ul>
                        <li><strong>How can I contact customer service?</strong> You can contact us via email or phone.</li>
                        <li><strong>When is customer service available?</strong> Customer service is available from 8 AM to 6:30 PM.</li>
                    </ul>
                </div>
            </section>
        </div>
    </main>

    <script>
        function toggleVisibility(sectionId) {
            const section = document.getElementById(sectionId);
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
