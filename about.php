<?php
// Start session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - LUXUS</title>
    <link rel="icon" type="image/favicon" href="asset/LUXUS_logo.png">
    <link rel="stylesheet" href="stylesheet.css">
    <style>
      
        body {
    background-color: white;
    color: black;
    font-family: 'Century Gothic', sans-serif;
    margin: 0;
    padding: 0;
    padding-top: 100px; /* for fixed navbar */
    line-height: 1.6;
}

/* About Section */
.about-section {
    text-align: center;
    background-color: #f2f2f2;
    border: 2px solid gold;
    border-radius: 12px;
    padding: 50px 30px;
    margin: 40px auto;
    max-width: 900px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.about-section h1,
.about-section h2 {
    color: #d4af37;
    margin-bottom: 15px;
}

.about-section p {
    font-size: 1.1em;
    color: #333;
    margin-bottom: 20px;
}

/* Team Section */
.team-heading {
    color: #d4af37;
    text-align: center;
    margin-top: 60px;
    margin-bottom: 30px;
    font-size: 2em;
}

.row {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 20px;
    padding: 0 20px 60px;
}

.column {
    flex: 1;
    max-width: 300px;
    min-width: 250px;
    background-color: #f2f2f2;
    border: 2px solid gold;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    text-align: center;
}

.card img {
    width: 100%;
    border-bottom: 2px solid gold;
}

.container {
    padding: 20px;
}

.container h2 {
    color: #d4af37;
    font-size: 1.5em;
    margin-bottom: 10px;
}

.container .title {
    font-size: 1.1em;
    font-weight: bold;
    color: #555;
    margin-bottom: 15px;
}

.container p {
    font-size: 1em;
    color: #333;
    margin-bottom: 10px;
}

.container p:last-child {
    margin-top: 15px;
    font-style: italic;
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
            <a href="returns.php"><i class="fas fa-undo-alt"></i> Returns</a>
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
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
    <?php elseif (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="admin_page.php"><i class="fas fa-user-shield"></i> ADMIN</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
    <?php else: ?>
        <a href="login.php"><i class="fas fa-sign-in-alt"></i> LOGIN</a>
    <?php endif; ?>
    <a href="cart.php"><i class="fas fa-shopping-basket"></i> BASKET</a>

    </div>

<!-- NAVIGATION BAR END! -->
    
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
            }, 100); 
        }
    </script>
    
    
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

.dark-mode {
    background-color: #1e1e1e; /* Dark background for the entire body */
}

/* Dark Mode Styles for the About Us Page */
.dark-mode .about-section {
    background-color: #2d2d2d;
    color: white;
    border-color: #d4af37;
}

.dark-mode .about-section h1,
.dark-mode .about-section h2 {
    color: #d4af37;
}

.dark-mode .about-section p {
    color: #ccc;
}

.dark-mode .team-heading {
    color: #d4af37;
}

.dark-mode .row {
    background-color: #1e1e1e;
}

.dark-mode .column {
    background-color: #2d2d2d;
    border-color: #d4af37;
}

.dark-mode .column .container h2 {
    color: #d4af37;
}

.dark-mode .column .container .title {
    color: #ccc;
}

.dark-mode .column .container p {
    color: #ccc;
}

.dark-mode .column .container a {
    color: gold;
}

.dark-mode .column .container a:hover {
    color: #d4af37;
}

</style>



    <!-- Section -->
    <section class="about-section">
        <h1>Welcome to LUXUS</h1>
        <h2>Your Luxurious Experience</h2>
        <p>At LUXUS we know that a watch's true value is not determined by how it is worn but how it is crafted. That is why we use natural materials for the best user experience.</p>
        <p>We started in the late 19th century when many of our members would traverse hard terrains to recover natural resources so they could be used by you. We are not just a watch company; we are comfort, luxury, style, and innovation.</p>
    </section>

    <!-- Meet the Team Section -->
    <h2 class="team-heading" style="text-align:center">Meet Our Team</h2>
    <div class="row">
        <!-- Team Member 1 -->
        <div class="column">
            <div class="card">
                
                <div class="container">
                    <h2>William Smith</h2>
                    <p class="title">CEO & Founder</p>
                    <p>"I started LUXUS to share my love for watches. Every watch we sell has a unique value and is like a memory that stays to remind you of the time you first bought it."</p>
                   <p>Email: <a href="mailto:williamDirk039@gmail.com" style="color: gold;">williamDirk039@gmail.com</a></p>
                </div>
            </div>
        </div>

        <!-- Team Member 2 -->
        <div class="column">
            <div class="card">
                
                <div class="container">
                    <h2>Lynn J Clara</h2>
                    <p class="title">Art Director</p>
                    <p>"Blending different colors into the watches creates a mesmerizing experience for the viewers and buyers. I create many styles to suit everyone."</p>
                    <p>Email: <a href="mailto:ClaraJLynette78@hotmail.com" style="color: gold;">ClaraJLynette78@hotmail.com</a></p>
                </div>
            </div>
        </div>

        <!-- Team Member 3 -->
        <div class="column">
            <div class="card">
               
                <div class="container">
                    <h2>Danielle Park</h2>
                    <p class="title">Lead Designer</p>
                    <p>"As a lead designer, classic watches are my favorite. I include them in my collection along with other styles to appeal to buyers worldwide. Buying a watch is an experience where you can appreciate the intricate details and craftsmanship."</p>
					<p>Email: <a href="mailto:Danielle1Park@outlook.com" style="color: gold;">Danielle1Park@outlook.com</a></p>
            </div>
        </div>
    </div>

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