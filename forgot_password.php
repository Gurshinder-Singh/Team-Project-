<?php
require_once 'db.php';
$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = "Email is required.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $token = bin2hex(random_bytes(50));
                $stmt = $conn->prepare("UPDATE users SET reset_token = :token, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email");
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->execute();

                $resetLink = "http://cs2team30.cs2410-web01pvm.aston.ac.uk/reset_password.php?token=" . $token;
                $subject = "Password Reset Request";
                $message = "Click the following link to reset your password: " . $resetLink;
                $headers = "From: no-reply@LUXUS.com";

                if (mail($email, $subject, $message, $headers)) {
                    $message = "A password reset link has been sent to your email.";
                } else {
                    $error = "Failed to send email.";
                }
            } else {
                $error = "No user found with this email.";
            }
        } catch (PDOException $e) {
            $error = "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="stylesheet.css">
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

<!-- Forgot Password Form -->
<div class="login-container">
    <h1>Forgot Password</h1>
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php elseif (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="POST" action="forgot_password.php">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Submit</button>
    </form>
    <div class="signup-link">
        <p>Remember your password? <a href="login.php">Login</a></p>
    </div>
</div>

</body>
</html>
<!-- CSS -->
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
