<?php
// Start session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - LUXUS</title>
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="icon" type="image/favicon" href="/asset/LUXUS_logo.png"> 
    <style>
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

        .navbar a, .navbar-logo {
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
        }

        .dropdown-content a:hover {
            background-color: #ddd;
            color: black;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

    body {
    background-color: white;
    color: black;
    font-family: Arial, sans-serif;
    margin: 0;
    padding-top: 100px; 
}

.form-section {
    background-color: #f2f2f2;
    color: black;
    border: 2px solid gold;
    padding: 40px 20px;
    margin: 40px auto;
    max-width: 600px;
    border-radius: 12px;
    text-align: center;
}

.form-section h1 {
    color: #d4af37; 
    margin-bottom: 20px;
}

.form-section p {
    font-size: 16px;
    margin-bottom: 30px;
    color: #333;
}

.form-section form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.form-section label {
    color: #d4af37;
    margin-bottom: 5px;
    align-self: center;
    font-weight: bold;
}

.form-section input,
.form-section textarea {
    width: 80%;
    padding: 12px;
    margin: 10px 0 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    text-align: center;
    color: #000;
}

.form-section textarea {
    resize: none;
}

.form-section button {
    background-color: #d4af37;
    color: black;
    font-weight: bold;
    padding: 12px 25px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
}

.form-section button:hover {
    background-color: gold;
}

.success-message {
    color: green;
    font-weight: bold;
    margin-top: 15px;
    font-size: 16px;
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
</div>
    
</body>
</html>

<!-- Updated CSS -->
<style>
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

.image-container {
    display: flex;
    justify-content: center;
    position: absolute;
}

.image-container img {
    width: 400px;
    height: auto;
    position: absolute;
    left: 1000px;
    top: 200px;
}

/* Dark Mode Styles */
.dark-mode {
    background-color: #1e1e1e; /* Dark background for the entire body */
    color: white; /* Light text color */
}

/* Dark Mode Styles for the Form Section */
.dark-mode .form-section {
    background-color: #2d2d2d;
    color: white;
    border-color: #d4af37;
}

.dark-mode .form-section h1 {
    color: #d4af37;
}

.dark-mode .form-section p {
    color: #ccc;
}

.dark-mode .form-section label {
    color: #d4af37;
}

.dark-mode .form-section input,
.dark-mode .form-section textarea {
    color: black;
    border-color: #555;
}

.dark-mode .form-section button {
    background-color: #d4af37;
    color: black;
}

.dark-mode .form-section button:hover {
    background-color: gold;
}

</style>

  <!--  Contact Form Section -->
<section class="form-section">
    <h1>Contact Us</h1>
    <p>We’d love to hear from you! If you have questions, feedback, or need assistance, please use the form below. Our team will respond within 1-2 business days.</p>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p class="success-message"><?= $_SESSION['success_message']; ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <form action="contact_form_handler.php" method="POST">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="e.g., example@example.com" required>

        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" placeholder="What can we help you with?" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" placeholder="Write your message here..." rows="6" required></textarea>

        <button type="submit">Send Message</button>
    </form>
       
</section>

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


   
 <script>
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
