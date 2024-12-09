<?php
require_once 'db.php'; //DATABASE CONNECTION
session_start();
$is_admin_page = false; // IS ADMIN ?
$error = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

 if (empty($email) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM admin_log WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && $password === $admin['password']) { 
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['username'] = $admin['email'];
                $_SESSION['is_admin'] = true;
                header("Location: homepage.php");
                exit();
            }
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name'];
                unset($_SESSION['is_admin']); 
                header("Location: homepage.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $error = "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

   <!-- HTML START -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/favicon" href="/asset/LUXUS_logo.png"> 
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
 
<!-- NAVIGATION BAR -->

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
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="profile.php">PROFILE</a>
        <a href="logout.php">LOGOUT</a>
    <?php else: ?>
        <a href="login.php">LOGIN</a>
    <?php endif; ?>
    <a href="checkout.php">BASKET</a>
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="admin_page.php">ADMIN</a>
    <?php endif; ?>
</div>
    <!-- NAVIGATION BAR END! -->
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
.error {
    color: red;
    font-weight: bold;
    margin: 10px 0;
}
</style>

    <!-- LOGIN FORM -->
    <div class="login-container">
        <h1>Login</h1>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>
        <div class="signup-link">
            <p>Don't have an account? <a href="sign_up.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
   <!-- E N D -->
