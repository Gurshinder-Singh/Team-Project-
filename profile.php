<?php
session_start();
require_once 'db.php'; //  database connection


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    echo "User ID from session: " . $user_id; // Debugging
} else {
    echo "User not logged in.";
    exit;
}

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
$stmt = $conn->prepare("SELECT points FROM loyalty_points WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute(); // Execute the query
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

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['current_name'], $_POST['new_name'], $_POST['confirm_name'])) {
        $current_name = $_POST['current_name'];
        $new_name = $_POST['new_name'];
        $confirm_name = $_POST['confirm_name'];
    
    

        if (empty($current_name) || empty($new_name) || empty($confirm_name)) {
            $message = "All name fields are required.";
        } elseif ($new_name !== $confirm_name) {
            $message = "Names do not match.";
        } else {
            // Check current name
            $stmt = $conn->prepare("SELECT name FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user_data || $user_data['name'] !== $current_name) {
                $message = "Current name is incorrect.";
            } else {
                // Update name
                $stmt = $conn->prepare("UPDATE users SET name = :name WHERE user_id = :user_id");
                $stmt->bindParam(':name', $new_name, PDO::PARAM_STR);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $message = "Name updated successfully!";
                } else {
                    $message = "Error updating name.";
                }
            }
        }
    }
}



    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $message = "All password fields are required.";
        } elseif ($new_password !== $confirm_password) {
            $message = "Passwords do not match.";
        } else {
            // Check current password
            $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user_data || !password_verify($current_password, $user_data['password'])) {
                $message = "Current password is incorrect.";
            } else {
                // Hash new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password
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


    if (isset($_POST['current_email'], $_POST['new_email'], $_POST['confirm_email'])) {
        $current_email = $_POST['current_email'];
        $new_email = $_POST['new_email'];
        $confirm_email = $_POST['confirm_email'];

        if (empty($current_email) || empty($new_email) || empty($confirm_email)) {
            $message = "All email fields are required.";
        } elseif ($new_email !== $confirm_email) {
            $message = "Emails do not match.";
        } else {
            // Check current email
            $stmt = $conn->prepare("SELECT email FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($current_email !== $user_data['email']) {
                $message = "Current email is incorrect.";
            } else {
                // Update email
                $stmt = $conn->prepare("UPDATE users SET email = :email WHERE user_id = :user_id");
                $stmt->bindParam(':email', $new_email, PDO::PARAM_STR);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $message = "Email updated successfully!";
                } else {
                    $message = "Error updating email.";
                }
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
    <link rel="icon" type="image/favicon" href="asset/LUXUS_logo.png">
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <title>Profile</title>
    
</head>
<body>
    <div class="container">
        <h1>My Profile</h1>
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>

        <!-- Change Information -->
        <a href="profile.php">
        <button type="submit" class="refreshBtn">Refresh details</button>
        </a>
        <h2>Change Username</h2>
        <?php if ($message): ?>
            <p style="color: <?= strpos($message, 'success') !== false ? 'green' : 'red' ?>;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST">
    <label for="current_name">Current Name</label>
    <input type="text" id="current_name" name="current_name" required>

    <label for="new_name">New Name</label>
    <input type="text" id="new_name" name="new_name" required>

    <label for="confirm_name">Confirm New Name</label>
    <input type="text" id="confirm_name" name="confirm_name" required>

    <a href="profile.php">
    <button type="submit">Update Name</button>
    </a>

</form>
    
<h3>Change Password</h3>
        <form method="POST">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Update Password</button>
        </form>
            
        <h3>Change Email</h3>
            <form method="POST">
    <label for="current_email">Current Email</label>
    <input type="email" id="current_email" name="current_email" required>

    <label for="new_email">New Email</label>
    <input type="email" id="new_email" name="new_email" required>

    <label for="confirm_email">Confirm New Email</label>
    <input type="email" id="confirm_email" name="confirm_email" required>

    <button type="submit">Update Email</button>
</form>
  
            <!-- Previous Orders -->
         <h2 style="display: flex; justify-content: space-between; align-items: center;">
    Previous Orders
    <a href="previous_orders.php" style="font-size: 14px; text-decoration: none; color: blue;">View All</a>
</h2>
    <ul>
        <?php if (!empty($previous_orders)): ?>
    <?php $last_order = $previous_orders[0]; // Get the most recent order ?>
    <li class="order-item">
        <span>
            Order #<?= htmlspecialchars($last_order['order_id']) ?> - 
            Total: £<?= htmlspecialchars($last_order['total_price']) ?> - 
            Date: <?= htmlspecialchars($last_order['created_at']) ?>
        </span>
        <a href="Feedback.php?order_id=<?= htmlspecialchars($last_order['order_id']) ?>" class="feedback-link">Leave Feedback</a>
    </li>
<?php else: ?>
    <p>No previous orders found.</p>
<?php endif; ?>

    </ul>   
        <!-- Current Orders -->
        <h2 style="display: flex; justify-content: space-between; align-items: center;">
    Current Orders
    <a href="current_orders.php" style="font-size: 14px; text-decoration: none; color: blue;">View All</a>
</h2>
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
        <p><a href="redeem_points.php"> Redeem Your Loyalty Points</a></p>

</form>
    </div>
</body>
</html>
<!-- NAVIGATION BAR -->
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
    <?php else: ?>
        <a href="login.php"><i class="fas fa-sign-in-alt"></i> LOGIN</a>
    <?php endif; ?>
    <a href="cart.php"><i class="fas fa-shopping-basket"></i> BASKET</a>
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="admin_page.php"><i class="fas fa-user-shield"></i> ADMIN</a>
    <?php endif; ?>
</div>
<!-- NAVIGATION BAR END! -->
    
</body>
</html>

<!-- Updated CSS -->
<style>
    h2 {
        color: rgb(0, 0, 0); 
        text-decoration: underline;
        cursor: pointer;
        margin-top: 20px;
    }
    h2:hover {
        color: rgb(0, 0, 0); 
    }
    section {
        padding: 10px 20px; 
    }
    body, html {
        height: 100%; 
        margin: 0;
    }
    .main-container {
        display: flex;
        flex-direction: column;
        align-items: center; 
        justify-content: center; 
        height: calc(100% - 75px); 
        padding-top: 75px;             
        background-color: #e6e6e6;
    }
    main {
        text-align: center;
    }

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
        position: relative;
    }

    .image-container img {
        width: auto; 
        height: auto; 
        max-width: 100%;
        max-height: 500px;
        transition: transform 0.3s ease-in-out;
        display: block;
        margin: 0 auto;
    }

        body {
            margin: 0;
            padding: 0;
            height: 130vh;
            overflow-y: scroll;
            overflow-x: hidden;
            background-color: #e6e6e6; 
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

        /* Feedback Link Styling */
        .feedback-link {
            color: blue;
            text-decoration: underline;
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        .feedback-link:hover {
            color: gold;
            text-decoration: underline;
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

        form input:focus ,.refreshBtn{
            border-color: goldenrod;
         
            outline: goldenrod;
        }

        form button,.refreshBtn {
            width: 100%;
            padding: 12px;
            background-color: white;
            color: #5c4033;
            border: 1px solid goldenrod;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-decoration:none;
        }

        form button:hover, .refreshBtn:hover {
            background-color: #e6e6e6;
            color:#5c4033;
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

/* Dark Mode Styles */
.dark-mode {
    background-color: #1e1e1e; /* Dark background for the entire body */
    color: white; /* Light text color */
}

.dark-mode .container {
    background-color: #333; /* Dark background for the content container */
    color: white; /* Ensure text is white in the container */
}

.dark-mode .container h1, 
.dark-mode .container p, 
.dark-mode .container h2, 
.dark-mode form label, 
.dark-mode .feedback-link {
    color: white; /* Ensure all text inside the container is white */
}

.dark-mode form input {
    background-color: #444; /* Dark background for inputs */
    border: 1px solid #888; /* Light border for inputs */
    color: white; /* Text in the input field should be white */
}

.dark-mode form input:focus, 
.dark-mode .refreshBtn {
    border-color: goldenrod; /* Highlighted border on focus */
    outline: goldenrod; /* Outline when input is focused */
}

.dark-mode form button, 
.dark-mode .refreshBtn {
    background-color: #444; /* Dark button background */
    color: white; /* Light text on buttons */
    border: 1px solid goldenrod; /* Golden border */
}

.dark-mode .navbar {
    background-color: #111; /* Dark background for the navbar */
}

.dark-mode .navbar a, 
.dark-mode .navbar-logo {
    color: white; /* White text in navbar */
}

.dark-mode .dropdown-content {
    background-color: #222; /* Dark dropdown background */
}

.dark-mode .dropdown-content a {
    color: white; /* Light text for dropdown items */
}

.dark-mode .dropdown-content a:hover {
    background-color: #444; /* Lighter hover background */
    color: gold; /* Gold text on hover */
}

.dark-mode .message {
    color: #d9534f; /* Keep red message color in dark mode */
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
    <script>
    document.getElementById('darkModeToggle').addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
});
    </script>
</head>
<body>
