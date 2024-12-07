<?php
session_start();
require_once 'db.php'; //  database connection

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

//DB Query user details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// DB query previous orders (Completed)
$stmt = $conn->prepare("
    SELECT order_id, total_price, status, created_at 
    FROM orders 
    WHERE user_id = :user_id AND status = 'Completed' 
    ORDER BY created_at DESC
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$previous_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DB Query current orders (Pending)
$stmt = $conn->prepare("
    SELECT order_id, total_price, status, created_at 
    FROM orders 
    WHERE user_id = :user_id AND status = 'Pending' 
    ORDER BY created_at DESC
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$current_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DB Query loyalty points
$stmt = $conn->prepare("SELECT points FROM loyalty_program WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$loyalty_points = $stmt->fetchColumn() ?: 0;

//DB Query wishlist items
$stmt = $conn->prepare("
    SELECT product_name, added_at 
    FROM wishlist 
    WHERE user_id = :user_id 
    ORDER BY added_at DESC
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$wishlist_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

//  password change function
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        //  Current password
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($current_password, $user_data['password'])) {
            $message = "Current password is incorrect.";
        } else {
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $message = "Password updated successfully!";
            } else {
                $message = "Error updating password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/favicon" href="/asset/LUXUS_logo.png"> 
    <title>Profile</title>
</head>
<body>
    <div class="container">
        <h1>My Profile</h1>
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>

        <!-- Change Password -->
        <h2>Change Password</h2>
        <?php if ($message): ?>
            <p style="color: <?= strpos($message, 'success') !== false ? 'green' : 'red' ?>;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Update Password</button>
        </form>

        <!-- Previous Orders -->
        <h2>Previous Orders</h2>
        <ul>
            <?php if (!empty($previous_orders)): ?>
                <?php foreach ($previous_orders as $order): ?>
                    <li>
                        Order #<?= htmlspecialchars($order['order_id']) ?> - 
                        Total: £<?= htmlspecialchars($order['total_price']) ?> - 
                        Date: <?= htmlspecialchars($order['created_at']) ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No previous orders found.</p>
            <?php endif; ?>
        </ul>

        <!-- Current Orders -->
        <h2>Current Orders</h2>
        <ul>
            <?php if (!empty($current_orders)): ?>
                <?php foreach ($current_orders as $order): ?>
                    <li>
                        Order #<?= htmlspecialchars($order['order_id']) ?> - 
                        Total: £<?= htmlspecialchars($order['total_price']) ?> - 
                        Status: <?= htmlspecialchars($order['status']) ?> - 
                        Date: <?= htmlspecialchars($order['created_at']) ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No current orders found.</p>
            <?php endif; ?>
        </ul>

        <!-- Wishlist -->
        <h2>Wishlist</h2>
        <ul>
            <?php if (!empty($wishlist_items)): ?>
                <?php foreach ($wishlist_items as $item): ?>
                    <li>
                        <?= htmlspecialchars($item['product_name']) ?> - Added on <?= htmlspecialchars($item['added_at']) ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Your wishlist is empty.</p>
            <?php endif; ?>
        </ul>

        <!-- Loyalty Points -->
        <h2>LUXUS Points</h2>
        <p>You have <?= htmlspecialchars($loyalty_points) ?> LUXUS points.</p>
    </div>
</body>
</html>

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
<!-- Navigation bar END -->




    
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

body {
    margin: 0;
    padding: 0;
    height: 130vh;
    overflow-y: scroll;
    overflow-x: hidden;
    background-color: #5c4033; 
    font-family: 'Century Gothic', sans-serif;
    font-weight: bold;
    position: relative;
}

.container {
    margin-top: 100px; 
    padding: 20px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.container h1 {
    text-align: center;
    font-size: 24px;
    color: #222;
    margin-bottom: 20px;
}

.container p {
    font-size: 16px;
    color: #333;
    margin-bottom: 10px;
    text-align: left;
}

.container h2 {
    font-size: 20px;
    color: #222;
    margin-bottom: 15px;
    text-align: left;
}

form label {
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
    display: block;
}

form input {
    width: 90%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

form input:focus {
    border-color: #d4af37;
    box-shadow: 0 0 8px rgba(212, 175, 55, 0.4);
    outline: none;
}

form button {
    width: 100%;
    padding: 12px;
    background-color: #333;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

form button:hover {
    background-color: #555;
    transform: scale(1.02);
}

.message {
    text-align: center;
    font-size: 14px;
    color: #d9534f; 
    margin-bottom: 20px;
}

.message.success {
    color: #5cb85c;
}

@media (max-width: 768px) {
    .container {
        padding: 15px;
    }

    form input,
    form button {
        font-size: 14px;
    }
}
</style>

</body>
</html>
