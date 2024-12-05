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
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        /* General Text Styling */
        body {
            background-color: #5c4033; /* Dark background for better contrast */
            color: white; /* Set all text to white */
            font-family: 'Century Gothic', sans-serif; /* Set default font */
            font-weight: normal; /* Apply normal text globally */
            position: relative;
            line-height: 1.6; /* Improve text spacing for readability */
            margin: 0;
            padding: 0;
        }

        /* About Section */
        .about-section {
            text-align: center;
            padding: 50px 20px;
            background-color: #412920; /* Slightly lighter background for contrast */
            border-radius: 10px; /* Rounded corners for a modern look */
            margin: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow effect */
        }

        .about-section h1, .about-section h2 {
            font-weight: bold;
            margin-bottom: 20px;
            color: #f0c14b; /* Highlight headers with a golden accent */
        }

        .about-section p {
            font-size: 1.1em;
            margin-bottom: 15px;
        }

        /* Team Section */
        .team-heading {
            color: #f0c14b; /* Golden accent for section heading */
            margin-top: 50px;
            margin-bottom: 30px;
            font-size: 2em;
        }

        .row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .column {
            flex: 1;
            max-width: 300px;
            min-width: 250px;
            background-color: #1c1c1c;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            text-align: center;
            margin-bottom: 20px;
        }

        .card img {
            width: 100%;
            border-bottom: 2px solid #f0c14b;
        }

        .container {
            padding: 20px;
        }

        .container h2 {
            color: #f0c14b;
            margin-bottom: 10px;
            font-size: 1.5em;
        }

        .container .title {
            font-size: 1.2em;
            color: #d9d9d9; /* Light gray for secondary text */
            margin-bottom: 15px;
        }

        .container p {
            margin-bottom: 10px;
            font-size: 1em;
            color: #e6e6e6; /* Softer white for better contrast */
        }

        .container p:last-child {
            margin-top: 15px;
            font-style: italic;
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


    <!-- About Section -->
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
                    <h2>William Dirk</h2>
                    <p class="title">CEO & Founder</p>
                    <p>"I started LUXUS to share my love for watches. Every watch we sell has a unique value and is like a memory that stays to remind you of the time you first bought it."</p>
                    <p>Email: williamDirk039@gmail.com</p>
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
                    <p>Email: ClaraJLynette78@hotmail.com</p>
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
                    <p>Email: Danielle1Park@outlook.com</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
