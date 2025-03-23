<?php
session_start();
require 'db.php';

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
    <link rel="icon" type="image/favicon" href="asset/LUXUS_logo.png">
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
    background-color: white;
    color: black;
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding-top: 100px; 
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
}

/* Navbar */
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

/* Return Form Section */
.return-section {
    background-color: #f2f2f2;
    color: black;
    border: 2px solid gold;
    border-radius: 12px;
    padding: 40px 30px;
    margin: 40px auto;
    max-width: 600px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.return-section h1 {
    color: #d4af37;
    font-size: 2em;
    margin-bottom: 15px;
}

.return-section p {
    font-size: 1.1em;
    margin-bottom: 25px;
    color: #333;
}

/* Return Form */
.return-form {
    max-width: 500px;
    margin: 0 auto;
    text-align: left;
}

.return-form label {
    font-weight: bold;
    color: #d4af37;
    margin: 10px 0 5px;
    display: block;
}

.return-form select,
.return-form textarea,
.return-form button {
    width: 100%;
    padding: 12px;
    margin: 10px 0 20px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 1rem;
    color: black;
}

.return-form textarea {
    resize: none;
    height: 100px;
}

.return-form button {
    background-color: #d4af37;
    color: black;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s ease;
    text-transform: uppercase;
}

.return-form button:hover {
    background-color: gold;
}

      .login-container {
    background-color: #f2f2f2;
    border: 2px solid gold;
    padding: 40px 30px;
    border-radius: 12px;
    max-width: 400px;
    margin: 60px auto;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
}

.login-container h1 {
    color: #d4af37;
    margin-bottom: 20px;
    text-align: center;
    width: 100%;
}

.login-container form {
    width: 100%;
    display: flex;
    flex-direction: column;
}

.login-container label {
    margin-bottom: 5px;
    color: #d4af37;
    font-weight: bold;
    text-align: left;
}

.login-container input {
    width: 90%; 
    padding: 10px;
    margin: 10px auto 20px; 
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align: center;
    display: block;
}

.login-container button {
    background-color: #d4af37;
    color: black;
    font-weight: bold;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-transform: uppercase;
}

.login-container button:hover {
    background-color: gold;
}

.signup-link {
    margin-top: 10px;
    text-align: center;
}

.signup-link a {
    color: #d4af37;
    text-decoration: none;
}

.signup-link a:hover {
    text-decoration: underline;
}


        .menu-icon {
            height: 24px;
            width: 24px;
        }

        .dropdown {
            position: relative;
            display: inline-block;
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
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #575757;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

		/* Dark Mode Styles */
		.dark-mode {
    	background-color: #1e1e1e; /* Dark background for the entire body */
		}

		.dark-mode .return-section {
    	background-color: #2d2d2d;
    	color: white;
    	border-color: #d4af37;
    	box-shadow: 0 4px 10px rgba(255, 255, 255, 0.1);
		}

		.dark-mode .return-section h1 {
    	color: #d4af37;
		}

		.dark-mode .return-section p {
    	color: #ccc;
		}

		.dark-mode .return-form label {
    	color: #d4af37;
		}

		.dark-mode .return-form select,
		.dark-mode .return-form textarea {
    	background-color: #363636;
    	color: white;
    	border-color: #555;
		}

		.dark-mode .return-form button {
    	background-color: #d4af37;
    	color: black;
		}

		.dark-mode .return-form button:hover {
    	background-color: gold;
		}

		.dark-mode .login-container {
    	background-color: #2d2d2d;
    	color: white;
    	border-color: #d4af37;
    	box-shadow: 0 4px 10px rgba(255, 255, 255, 0.1);
		}

		.dark-mode .login-container h1 {
    	color: #d4af37;
		}

		.dark-mode .login-container label {
	    color: #d4af37;
		}

		.dark-mode .login-container input {
    	background-color: #363636;
    	color: white;
    	border-color: #555;
		}

		.dark-mode .login-container button {
    	background-color: #d4af37;
    	color: black;
		}

		.dark-mode .login-container button:hover {
    	background-color: gold;
		}

		.dark-mode .signup-link a {
    	color: #d4af37;
		}

		.dark-mode .signup-link a:hover {
    	color: gold;
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

    </style>
</head>
<body>

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