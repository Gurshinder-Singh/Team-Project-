
<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM admin_log WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id']; 
                $_SESSION['username'] = $user['email']; 
                header("Location: welcome.php"); 
                exit();
            } else {
                echo "<div class='error'>Invalid password. Please try again.</div>";
            }
        } else {
            echo "<div class='error'>No account found with that email. Please sign up.</div>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="stylesheet.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
 
</head>
   <!-- Navigation bar -->
   <div class="navbar" id="navbar">
    <a href="#menu">MENU</a>
    <a href="#search">SEARCH</a>
    <a href="#LUXUS" class="luxus-link">LUXUS</a>
    <a href="#wishlist">WISHLIST</a>
    <a href="#cart">CART</a>
</div>
<body>
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
        </div>
    </div>
</body>
</html>
