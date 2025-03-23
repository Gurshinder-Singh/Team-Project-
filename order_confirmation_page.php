<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
    <head>
<meta charset="UTF-8">
<title>Order Confirmation</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="icon" type="image/favicon" href="/asset/LUXUS_logo.png"> 
<link rel="stylesheet" href="stylesheet.css">
</head>
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
body {
    background-color: #f4f4f4;
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
}

.mega-container {
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #e9ecef;
}

.inner-container {
    background-color: #ffffff;
    padding: 30px 40px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 80%;
    max-width: 600px;
}

h1 {
    font-size: 32px;
    color: #343a40;
    margin-bottom: 20px;
}

.container2 p {
    font-size: 18px;
    color: #6c757d;
    line-height: 1.5;
}

.container, .container2 {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
}

button {
    background-color: #d4af37;
    color: #ffffff;
    padding: 12px 20px;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Dark Mode Styles */
.dark-mode {
    background-color: #1e1e1e;
    color: white;
}

.dark-mode .mega-container {
    background-color: #1e1e1e;
}

.dark-mode .inner-container {
    background-color: #2d2d2d;
    color: white;
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
}

.dark-mode h1 {
    color: #d4af37;
}

.dark-mode .container2 p {
    color: #ccc;
}

.dark-mode button {
    background-color: #d4af37;
    color: black;
}


.dark-mode .dropbtn {
    background-color: #363636; 
}

.dark-mode .dropdown-content a:hover {
    background-color: #ddd;
    color: black;
}

</style>

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

<body>

<div class="mega-container">
  <div class="inner-container">
    <div class="container">
      <h1>Your order has been confirmed!</h1>
    </div>

    <div class="container2">
      <p>Thank you for shopping with LUXUS! <br> We hope the experience has been enjoyable for you.</p>
    </div>
    
    <button type="button" onclick="window.location.href='homepage.php'">Go back to Home Page</button>
  </div>
</div>
<!-- JS Script for light & dark mode button -->
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

