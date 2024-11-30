<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit;
}

// Get user information from the session
$name = $_SESSION['name'];  // Get the user's name from the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
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

    <!-- Welcome Message -->
    <div class="welcome-container">
        <h1>Welcome!</h1>
        <p>Hello, <strong><?php echo htmlspecialchars($name); ?></strong>. You are successfully logged in.</p>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>
