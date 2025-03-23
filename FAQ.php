<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>FAQs Luxus</title>
    <link rel="icon" type="image/favicon" href="asset/LUXUS_logo.png">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css"/>
<style>
        
        body {
    background-color: white;
    color: black;
    font-family: 'Century Gothic', sans-serif;
    margin: 0;
    padding: 0;
    padding-top: 100px; 
}

/* FAQ Container */
.squircle {
    background: #f2f2f2;
    border-radius: 12px;
    padding: 40px;
    margin: 50px auto;
    max-width: 900px;
    border: 2px solid gold;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #d4af37;
    font-size: 2.5em;
    margin-bottom: 30px;
    text-align: center;
}

h2 {
    color: #d4af37;
    cursor: pointer;
    text-decoration: underline;
    margin-top: 30px;
    font-size: 1.4em;
}

h2:hover {
    color: black;
}

ul {
    list-style-type: none;
    padding-left: 0;
    margin-top: 15px;
}

ul li {
    margin: 12px 0;
    color: #333;
    font-size: 1em;
    line-height: 1.6;
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
            z-index: 1000;
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

/* Dark Mode Styles */
.dark-mode {
    background-color: #1e1e1e; /* Dark background for the entire body */
}

/* Dark Mode Styles for the FAQ Page */
.dark-mode .squircle {
    background-color: #2d2d2d;
    color: white;
    border-color: #d4af37;
}

.dark-mode h1 {
    color: #d4af37;
}

.dark-mode h2 {
    color: #d4af37;
}

.dark-mode h2:hover {
    color: white;
}

.dark-mode ul li {
    color: #ccc;
}

.dark-mode .squircle {
    box-shadow: 0 8px 16px rgba(255, 255, 255, 0.1);
}

    </style>
</head>
<body>
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
                        <!-- FOOTER -->
<footer style="
            background-color: #2c2c2c;
            color: white;
            padding: 10px 15px;
            text-align: center;
            font-size: 13px;
            margin-top: 50px;
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



    </main>

    <script>
        function toggleVisibility(sectionId) {
            const section = document.getElementById(sectionId);
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }

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
