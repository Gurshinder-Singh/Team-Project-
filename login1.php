<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        // Query the admin_log table
        $stmt = $conn->prepare("SELECT * FROM admin_log WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Directly compare the password with the plain-text password in the database
            if ($password === $user['password']) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['email'];
                $_SESSION['is_admin'] = true; // Set admin session flag

                // Redirect to the homepage
                header("Location: homepage.php");
                exit();
            } else {
                $error = "Invalid password. Please try again.";
            }
        } else {
            $error = "No account found with that email. Please sign up.";
        }
    } catch (PDOException $e) {
        $error = "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar" id="navbar">
        <a href="#menu">HOME</a>
        <a href="#search">SEARCH</a>
        <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
        <a href="#wishlist">PROFILE</a>
        <a href="#cart">BASKET</a>
    </div>

    <!-- Login Container -->
    <div class="login-container">
        <h1>Login</h1>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>
        <div class="signup-link">
            <p>Don't have an account? <a href="sign_up.html">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
