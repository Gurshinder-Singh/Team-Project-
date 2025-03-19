<?php
session_start();
require 'db.php';

// Check if user is logged in
$loggedIn = isset($_SESSION['user_id']);

if ($loggedIn) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT order_id FROM orders WHERE user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns - LUXUS</title>
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        body {
            background-color: #5c4033;
            color: white;
            font-family: 'Century Gothic', sans-serif;
            margin: 0;
            padding-top: 75px; /* Moves everything directly under the nav bar */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
        }

        .navbar {
            height: 75px;
            display: flex;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            background-color: #363636;
            z-index: 1000;
        }

        .navbar a, .navbar-logo {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            flex: 1;
            text-align: center;
            font-weight: bold;
        }

        .navbar-logo img {
            height: 95px;
            width: auto;
            margin: 0 auto;
        }

        .return-section {
            text-align: center;
            padding: 30px 20px;
            background-color: #3b2418;
            border-radius: 10px;
            margin: 10px auto; /* Reduced margin to move it up */
            width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .return-section h1 {
            color: #f0c14b;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .return-section p {
            font-size: 1.1em;
            margin-bottom: 15px;
            color: white;
        }

        .return-form {
            max-width: 500px;
            margin: 0 auto;
            text-align: left;
        }

        .return-form label {
            display: block;
            font-weight: bold;
            color: #f0c14b;
            margin: 10px 0 5px;
        }

        .return-form select,
        .return-form textarea,
        .return-form button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 5px;
            border: none;
            font-size: 1rem;
        }

        .return-form textarea {
            resize: none;
            height: 100px;
        }

        .return-form button {
            background-color: #f0c14b;
            color: black;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            text-transform: uppercase;
        }

        .return-form button:hover {
            background-color: #d9a441;
        }
    </style>
</head>
<body>

<div class="navbar" id="navbar">
    <a href="homepage.php">HOME</a>
    <a href="products_page.php">PRODUCTS</a>
    <div class="navbar-logo">
        <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
    </div>
    <?php if (!$loggedIn): ?>
        <a href="login.php">LOGIN</a>
    <?php else: ?>
        <a href="profile.php">PROFILE</a>
        <a href="logout.php">LOGOUT</a>
    <?php endif; ?>
    <a href="checkout.php">BASKET</a>
</div>

<<!-- Show Login Form if not logged in -->
<?php if (!$loggedIn): ?>
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
            <p>Don't have an account? <a href="sign_up.php">Sign Up</a></p>
            <p><a href="forgot_password.php">Forgot Password?</a></p>
        </div>
    </div>

<?php else: ?>
    <!-- Show Return Form if logged in -->
    <section class="return-section">
        <h1>Request a Return</h1>
        <p>Please select the order you want to return and provide additional details.</p>
        <form class="return-form" action="returns_handler.php" method="POST">
            <label for="order_id">Order ID:</label>
            <select name="order_id" required>
                <?php foreach ($orders as $order): ?>
                    <option value="<?= htmlspecialchars($order['order_id']); ?>"><?= htmlspecialchars($order['order_id']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="reason">Reason for Return:</label>
            <select name="reason" required>
                <option value="Damaged Item">Damaged Item</option>
                <option value="Incorrect Item">Incorrect Item</option>
                <option value="Other">Other</option>
            </select>

            <label for="details">Additional Details:</label>
            <textarea name="details" placeholder="Explain the issue..." rows="4" required></textarea>

            <button type="submit">Submit Return Request</button>
        </form>
    </section>
<?php endif; ?>

</body>
</html>
