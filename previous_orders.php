<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Please log in to view your orders.");
}

$user_id = $_SESSION['user_id'];

$orderQuery = "
    SELECT o.order_id, o.total_price, o.created_at, p.name AS product_name
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    WHERE o.user_id = :user_id
    ORDER BY o.created_at DESC
";
$orderStmt = $conn->prepare($orderQuery);
$orderStmt->bindParam(':user_id', $user_id);
$orderStmt->execute();
$orders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);


$returnQuery = "SELECT order_id, status FROM returns WHERE user_id = :user_id";
$returnStmt = $conn->prepare($returnQuery);
$returnStmt->bindParam(':user_id', $user_id);
$returnStmt->execute();
$returns = $returnStmt->fetchAll(PDO::FETCH_ASSOC);

$returnStatuses = [];
foreach ($returns as $return) {
    $returnStatuses[$return['order_id']] = $return['status'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previous Orders - LUXUS</title>
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        body {
          
            font-family: 'Century Gothic', sans-serif;
            margin: 0;
            padding: 0;
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

        .container {
            padding: 40px;
            max-width: 700px;
            margin: 100px auto;
            background-color:white;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
      

        table {
            width: 100%;
            border-collapse: collapse;
            background: #e6e6e6;
            color: black;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: goldenrod;
            color: #5c4033;
        }

        .return-status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .approved {
            color: green;
        }

        .rejected {
            color: red;
        }

        .pending {
            color: orange;
        }

        .no-return {
            color: grey;
        }
		
		
		.dark-mode {
    	background-color: #1e1e1e; 
		}

.dark-mode .container {
    background-color: #2d2d2d;
    color: white;
    box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
}

.dark-mode table {
    background-color: #363636;
    color: white;
}

.dark-mode th {
    background-color: #d4af37; 
    color: #1e1e1e; 
}

.dark-mode td {
    border-color: #555;
}

.dark-mode .return-status.approved {
    color: #4caf50; 
}

.dark-mode .return-status.rejected {
    color: #ff4d4d; 
}

.dark-mode .return-status.pending {
    color: #ffa500; 
}

.dark-mode .return-status.no-return {
    color: #ccc; 
}

.dark-mode .feedback-link {
    color: #d4af37; 
}

.dark-mode .feedback-link:hover {
    color: #ffd700; 
}
	
    </style>
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
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="returns.php"><i class="fas fa-undo-alt"></i> Returns</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
                    <?php endif; ?>
                    <a href="javascript:void(0);" id="darkModeToggle">
                        <i class="fas fa-moon"></i> <span>Dark Mode</span>
                    </a>
                </div>
            </div>
            <a href="homepage.php"><i class="fas fa-home"></i> HOME</a>
            <a href="products_page.php"><i class="fas fa-box-open"></i> PRODUCTS</a>
            <div class="navbar-logo">
                <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
            </div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php"><i class="fas fa-user"></i> PROFILE</a>
            <?php elseif (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="admin_page.php"><i class="fas fa-user-shield"></i> ADMIN</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
            <?php else: ?>
                <a href="login.php"><i class="fas fa-sign-in-alt"></i> LOGIN</a>
            <?php endif; ?>
            <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
                <a href="cart.php"><i class="fas fa-shopping-basket"></i> BASKET</a>
            <?php endif; ?>
        </div>

<div class="container">
    <h2>Your Previous Orders</h2>

    <table>
        <thead>
            <tr>
            <th>Order ID</th>
            <th>Product Name</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Return Status</th>
            <th>Review</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                       <td><?= htmlspecialchars($order['order_id']) ?></td>
                       <td><?= htmlspecialchars($order['product_name']) ?></td>
                       <td>£<?= htmlspecialchars($order['total_price']) ?></td>
                       <td><?= htmlspecialchars($order['created_at']) ?></td>
                       <td>
                            <?php if (isset($returnStatuses[$order['order_id']])): ?>
                                <?php if ($returnStatuses[$order['order_id']] === 'Approved'): ?>
                                    <span class="return-status approved">Approved</span>
                                <?php elseif ($returnStatuses[$order['order_id']] === 'Rejected'): ?>
                                    <span class="return-status rejected">Rejected</span>
                                <?php else: ?>
                                    <span class="return-status pending">Pending</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="return-status no-return">No Return</span>
                            <?php endif; ?>
                        </td>
                        <td> <a href="Feedback.php?order_id=<?= htmlspecialchars($order['order_id']) ?>" class="feedback-link">Leave Feedback</a></td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
            
</div>
<!-- FOOTER -->
<footer style="
            background-color: #2c2c2c;
            color: white;
            padding: 10px 15px;
            text-align: center;
            font-size: 13px;
            margin-top: 50px;
            position: relative;
            width: 100%;
            z-index: 2;
        ">
            <div style="margin-bottom: 10px; font-size: 18px;">
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-facebook-f"></i></a>
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-twitter"></i></a>
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-instagram"></i></a>
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <p style="margin: 0;">&copy; <?= date("Y") ?> LUXUS. All rights reserved.</p>
        </footer>




<!-- JS Script for light & dark mode button -->
    <script>
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;
    const darkModeIcon = document.querySelector('#darkModeToggle i');
    const darkModeText = darkModeToggle.querySelector('span'); 
    const savedTheme = localStorage.getItem('theme');

    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        darkModeIcon.classList.remove('fa-moon');
        darkModeIcon.classList.add('fa-sun');
        darkModeText.textContent = 'Light Mode'; 
    }

    darkModeToggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');

        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark');
            darkModeIcon.classList.remove('fa-moon');
            darkModeIcon.classList.add('fa-sun');
            darkModeText.textContent = 'Light Mode'; 
        } else {
            localStorage.setItem('theme', 'light');
            darkModeIcon.classList.remove('fa-sun');
            darkModeIcon.classList.add('fa-moon');
            darkModeText.textContent = 'Dark Mode'; 
        }
    });
</script>

</body>
</html>