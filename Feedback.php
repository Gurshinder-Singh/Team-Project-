<?php
session_start();
require_once 'db.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $stmt = $conn->prepare("SELECT p.product_id, p.name 
                            FROM order_items oi
                            JOIN products p ON oi.product_id = p.product_id
                            WHERE oi.order_id = :order_id");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    $products_in_order = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['submit_review'])) {
    $fullName = $_POST['full_name'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];
    $order_id = $_GET['order_id'];
    $product_id = $_POST['product_id'];

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        echo "User not logged in.";
        exit;
    }

    $order_id = intval($order_id);

    try {
        $stmt = $conn->prepare("INSERT INTO CustomerFeedback (user_id, order_id, product_id, Rating, Review, fullname)
                                    VALUES (:user_id, :order_id, :product_id, :rating, :feedback, :fullname)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':feedback', $feedback, PDO::PARAM_STR);
        $stmt->bindParam(':fullname', $fullName, PDO::PARAM_STR);

        $stmt->execute();

        echo "<script>
                alert('Thank you for your feedback!');
                window.location.href = 'profile.php';
              </script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . " - SQL: " . $stmt->queryString;
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback | LUXUS</title>
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
    padding: 40px 20px;
    padding-top: 120px; /* Adjust for fixed navbar */
    max-width: 700px;
    height: calc(100vh - 120px); /* Fill remaining height neatly */
    margin: 0 auto;
    background-color: white;
    color: #D4AF37; /* Gold text color */
    border-radius: 12px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
}



        }
        h1, h2 {
            text-align: center;
            color: #F0C987;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #F0C987;
            font-size: 16px;
            background-color: white;
            color: black;
        }
        textarea {
            resize: none;
            height: 120px;
        }
        
        button:hover {
            background-color: #E0B76A;
        }
		
/* Dark Mode Styles */
.dark-mode {
    background-color: #1e1e1e;
    color: white;
}

.dark-mode .container {
    background-color: #2d2d2d;
    color: white;
    box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
}

.dark-mode h1,
.dark-mode h2 {
    color: #d4af37;
}

.dark-mode label {
    color: white; /* Labels turn white in dark mode */
}

.dark-mode input,
.dark-mode select,
.dark-mode textarea {
    background-color: #363636;
    color: white;
    border-color: #555;
}

.dark-mode button[type="submit"] {
    background-color: #d4af37;
    color: black;
}

.dark-mode button[type="submit"]:hover {
    background-color: gold;
}

.dark-mode footer {
    background-color: #1e1e1e;
    color: white;
}

.dark-mode footer a {
    color: white;
}

.dark-mode footer a:hover {
    color: #d4af37;
}

.dark-mode .dropdown-content a:hover {
    background-color: #ddd;
    color: black;
}

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

   <header>
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

</header>
    <div class="container">
        <h1>Customer Feedback</h1>
        <p>We value your opinion! Let us know what you think about your new watch.</p>

        <form id="feedbackForm" action="<?php echo $_SERVER['PHP_SELF']; ?>?order_id=<?php echo $_GET['order_id']; ?>" method="post">

            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required placeholder="Enter your name">

            <label for="product_id">Select Product:</label>
            <select id="product_id" name="product_id" required>
                <?php foreach ($products_in_order as $product): ?>
                    <option value="<?= $product['product_id'] ?>"><?= $product['name'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="rating">Rate Your Experience:</label>
            <select id="rating" name="rating" required>
                <option value="5">⭐⭐⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="2">⭐⭐</option>
                <option value="1">⭐</option>
            </select>

            <label for="feedback">Your Review:</label>
            <textarea id="feedback" name="feedback" required placeholder="Write your review here..."></textarea>

            <button type="submit" name="submit_review">Submit Feedback</button>
        </form>
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