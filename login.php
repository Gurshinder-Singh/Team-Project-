<?php
// Include database connection
require_once 'db.php'; // This file should contain your database connection code.

// Start session
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        echo "Both fields are required.";
        exit;
    }

    try {
        // Prepare and execute query to fetch user
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Check if user exists
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array
        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name']; // Store the name in the session

                // Redirect to a protected page
                header("Location: dashboard.php");
                exit;
            } else {
                echo "Invalid email or password.";
            }
        } else {
            echo "No account found with this email.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
<!-- LOGIN PAGE HTML-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
     <!-- Navigation bar -->
<div class="navbar" id="navbar">
    <a href="#menu">HOME</a>
    <a href="#search">SEARCH</a>
    <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
    <a href="#wishlist">PROFILE</a>
    <a href="#cart">BASKET</a>
</div>
<style>
    .navbar {
        height: 50px; /* Set your desired navbar height */
        display: flex;
        align-items: center;
    }

    .navbar img {
        height: 170%; /* Adjust this percentage to make the image bigger */
        max-height: 170%;
    }
</style>

    <!-- Login Container -->
    <div class="login-container">
        <h1>Login</h1>
        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>
        <div class="signup-link">
            <p>Don't have an account? <a href="sign_up.html">Sign Up</a></p>
            <a href="change_password.html">Change Password</a>

        </div>
    </div>
</body>
</html>
