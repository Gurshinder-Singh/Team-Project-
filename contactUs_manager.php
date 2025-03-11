<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Feedback Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #f4f4f4;
            padding: 10px;
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
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .action-box {
            display: flex;
            gap: 10px;
        }
        .action-box input[type="checkbox"] {
            margin: 0;
        }
        .comment-box {
            margin-top: 5px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f4f4f4;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin - Contact Us Manager</h1>
    </header>

    <div class="content">
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

            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="homepage.php">HOME</a>
                <a href="loyalty_manager.php">LOYALTY MANAGER</a>
                <a href="feedback_manager.php">FEEDBACK MANAGER</a>
                <a href="inventorymanagement.php">INVENTORY MANAGER</a>
           		<a href="order_management.php">ORDER MANAGER</a>
                <a href="contactUs_manager.php">CONTACT US MANAGER</a>
            <?php else: ?>
                <a href="homepage.php">HOME</a>
                <a href="products_page.php">PRODUCTS</a>
            <?php endif; ?>

            <div class="navbar-logo">
                <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
            </div>

           <?php if (isset($_SESSION['user_id'])): ?>
    <a href="profile.php">PROFILE</a>
    <a href="previous_orders.php">PREVIOUS ORDERS</a> 
    <a href="current_orders.php">CURRENT ORDERS</a> 
    <a href="logout.php">LOGOUT</a>
<?php else: ?>
    <a href="login.php">LOGOUT</a>
<?php endif; ?>

        </div>

    <div class="container">
        <h2>Contact Us Manager</h2>
        <p>View customer inquiries, respond where needed, and remove resolved messages.</p>

        <table>
            <thead>
                <tr>
                    <th>Email Adress</th>
                    <th>Subject </th>
                    <th>Message</th>
                    <th>Reply</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customerFeedback as $feedback): ?>
                    // <tr>
                    //     <td><?php echo $feedback['FeedbackID']; ?></td>
                    //     <td><?php echo $feedback['fullname']; ?></td>
                    //     <td><?php echo $feedback['product_name']; ?></td>
                    //     <td><?php echo $feedback['Review']; ?></td>
                    //     <td><?php echo $feedback['Rating']; ?></td>
                    //     <td>
                    //         <form method="POST">
                    //             <input type="hidden" name="feedback_id" value="<?php echo $feedback['FeedbackID']; ?>">
                    //             <textarea name="reply" placeholder="Enter your reply here..."><?php echo $feedback['reply'] ?? ''; ?></textarea>
                    //             <button type="submit" name="submit_reply">Update Reply</button>
                    //         </form>
                    //     </td>
                    //     <td>
                    //         <div class="action-box">
                    //             <form method="POST">
                    //                 <input type="hidden" name="feedback_id" value="<?php echo $feedback['FeedbackID']; ?>">
                    //                 <button type="submit" name="remove_feedback">Remove</button>
                    //             </form>
                    //         </div>
                    //     </td>
                    // </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    <div class="footer">
        © 2025 Luxus. All rights reserved.
    </div>
</body>
</html>
