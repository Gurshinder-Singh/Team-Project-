<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Feedback Manager</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->

 <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            position: relative;
        }

        .content {
            flex: 1;
            position: relative;
        	margin-top: 75px
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
        	z-index: 1000
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

        .footer {
            background-color: #363636;
            color: gold;
            text-align: center;
            padding: 20px;
            width: 100%;
            position: absolute;
            bottom: 0;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .social-icons {
            margin-top: 10px;
        }

        .social-icon {
            color: gold;
            margin: 0 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>

    
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
    <a href="loyalty_manager.php">LOYALTY MANAGER</a>
    <div class="navbar-logo">
        <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
    </div>
    <a href="feedback_manager.php">FEEDBACK MANAGER</a>
    <a href="inventorymanagement.php">INVENTORY MANAGER</a>
    <a href="logout.php">LOGOUT</a>
</div>



<div class="content">
    <h1>Feedback Manager</h1>
    <p>View customer feedback, respond where needed, and remove inappropriate comments:</p>

    <h2>Customer Feedback</h2>
    <table border="1" style="width: 100%; text-align: left;">
        <thead>
            <tr>			
                <th>Feedback ID</th>
                <th>Customer Name</th>
                <th>Feedback</th>
                <th>Rating</th>
                <th>Reply</th>
                <th>No Reply Needed</th>
                <th>Actions</th>
            </tr>
        </thead>

    </table>

    <h3>Reply to Feedback</h3>
    <form method="post" action="reply_feedback.php">
        <label for="feedback-id">Feedback ID:</label>
        <input type="text" id="feedback-id" name="feedback_id" required placeholder="Enter Feedback ID"><br><br>
        <label for="reply">Your Reply:</label>
        <textarea id="reply" name="reply" required></textarea><br><br>
        <button type="submit">Submit Reply</button>
    </form>

    <h3>Manage Feedback Rules</h3>
    <form method="post" action="update_feedback_rules.php">
        <label for="filter-words">Filter Words (comma-separated):</label>
        <input type="text" id="filter-words" name="filter_words" required><br><br>
        <button type="submit">Update Rules</button>
    </form>
</div>

<div class="footer">
    <p>&copy; 2025 Luxus. All rights reserved.</p>
</div>

</body>
</html>
