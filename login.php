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
	<button id="darkModeToggle">Toggle Dark Mode</button>
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

/* Dark Mode Styles */
.dark-mode {
    background-color: #1e1e1e; /* Dark background for the entire body */
    color: white; /* Light text color */
}

/* Dark Mode Styles for Login Container */
.dark-mode .login-container {
    background-color: #333; /* Dark background for the login form */
    color: white; /* White text for login container */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); /* Add subtle shadow */
}

.dark-mode .login-container h1 {
    color: white; /* White text for the heading */
}

.dark-mode .login-container label {
    color: white; /* White labels for input fields */
}

.dark-mode .login-container input {
    background-color: #444; /* Dark background for input fields */
    border: 1px solid #888; /* Light border for input fields */
    color: white; /* White text inside input fields */
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 10px;
}

.dark-mode .login-container input:focus {
    border-color: goldenrod; /* Highlighted border on focus */
    outline: goldenrod; /* Outline when input is focused */
}

.dark-mode .login-container button {
    background-color: #444; /* Dark button background */
    color: white; /* White text on button */
    border: 1px solid goldenrod; /* Golden border for button */
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}

.dark-mode .login-container button:hover {
    background-color: #555; /* Lighter background on hover */
}

.dark-mode .signup-link a {
    color: gold; /* Gold color for the signup link */
}

.dark-mode .signup-link a:hover {
    text-decoration: underline; /* Underline on hover for links */
}

.dark-mode .error {
    color: #d9534f; /* Red error message color */
}

 

#darkModeToggle {
    background-color: transparent;
    color: white;
    font-size: 16px;
    font-weight: bold;
    border: none;
    padding: 10px 15px;
    text-decoration: none;
    cursor: pointer;
    transition: color 0.3s ease;
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
            <p><a href="forgot_password.php">Forgot Password?</a></p>

        </div>
    </div>
        <script>
    document.getElementById('darkModeToggle').addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
});
    </script>
    
</body>
</html>
   <!-- E N D -->
