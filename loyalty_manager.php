<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Loyalty Manager</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>

<!-- Navigation Bar -->
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
    <a href="profile.php">PROFILE</a>
    <a href="logout.php">LOGOUT</a>
</div>

<h1>Loyalty Manager</h1>
<p>Manage customer loyalty points, discounts, rewards, and rules.</p>

<!-- Update Customer Loyalty Form -->
<form method="post" action="update_loyalty.php">
    <label for="customer-id">Customer ID:</label>
    <input type="text" id="customer-id" name="customer_id" required placeholder="Enter Customer ID"><br><br>
    
    <label for="points">Loyalty Points:</label>
    <input type="number" id="points" name="points" min="0" required><br><br>
    
    <label for="discount">Discount Percentage:</label>
    <input type="number" id="discount" name="discount" min="0" max="100" required><br><br>
    
    <label for="reward">Reward Description:</label>
    <textarea id="reward" name="reward" required></textarea><br><br>
    
    <button type="submit">Update Loyalty</button>
</form>

<!-- Display Existing Customer Loyalty Data -->
<h3>Customer Loyalty Information</h3>
<table border="1" style="width: 100%; text-align: left;">
    <thead>
        <tr>
            <th>Customer ID</th>
            <th>Loyalty Points</th>
            <th>Discount</th>
            <th>Reward</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- PHP will dynamically fetch and display customer loyalty data here -->
    </tbody>
</table>

<!-- Add New Loyalty Rule Form -->
<h3>Add New Loyalty Rule</h3>
<form method="post" action="add_loyalty_rule.php">
    <label for="order-amount">Order Amount Threshold:</label>
    <input type="number" id="order-amount" name="order_amount" min="0" required><br><br>
    
    <label for="loyalty-points">Loyalty Points Awarded:</label>
    <input type="number" id="loyalty-points" name="loyalty_points" min="0" required><br><br>
    
    <label for="rule-description">Rule Description:</label>
    <textarea id="rule-description" name="rule_description" required></textarea><br><br>
    
    <label for="start-date">Start Date:</label>
    <input type="date" id="start-date" name="start_date" required><br><br>
    
    <label for="end-date">End Date:</label>
    <input type="date" id="end-date" name="end_date" required><br><br>
    
    <button type="submit">Add Rule</button>
</form>

<!-- Display Existing Loyalty Rules -->
<h3>Existing Loyalty Rules</h3>
<table border="1" style="width: 100%; text-align: left;">
    <thead>
        <tr>
            <th>Order Amount</th>
            <th>Loyalty Points</th>
            <th>Description</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
    </thead>
    <tbody>
        <!-- PHP will dynamically fetch and display loyalty rules here -->
    </tbody>
</table>

</body>
</html>
