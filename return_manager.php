<?php
session_start();
require 'db.php';

// Debugging - Show Errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check Admin Access
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die("ACCESS DENIED");
}

// Fetch return requests
$query = "SELECT * FROM returns ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$returns = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RETURN MANAGER - LUXUS</title>
    <link rel="stylesheet" href="stylesheet.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
            padding: 20px;
        }

        /* ✅ NAVBAR FIX - Matches Contact Manager */
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

        /* ✅ SMALLER HAMBURGER ICON */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            background-color: #363636;
            color: white;
            padding: 8px 12px;
            width: 50px;
            height: 50px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-icon {
            height: 30px;
            width: auto;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #363636;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
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

        /* ✅ TABLE STYLING */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 80px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f0c14b;
            font-weight: bold;
        }

        /* ✅ APPROVE / REJECT BUTTONS */
        .approve-btn, .reject-btn {
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px;
        }

        .approve-btn {
            background-color: green;
            color: white;
        }

        .reject-btn {
            background-color: red;
            color: white;
        }

        .approve-btn:hover {
            background-color: darkgreen;
        }

        .reject-btn:hover {
            background-color: darkred;
        }

    </style>
</head>
<body>

<!-- ✅ FIXED NAVBAR (Matches Contact Manager) -->
<div class="navbar" id="navbar">
    <div class="dropdown">
        <button class="dropbtn">
            <img src="asset/menu_icon.png" alt="MENU ICON" class="menu-icon">
        </button>
        <div class="dropdown-content">
            <a href="about.php">ABOUT US</a>
            <a href="contact.php">CONTACT US</a>
            <a href="FAQ.php">FAQS</a>
        </div>
    </div>

    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="homepage.php">HOME</a>
        <a href="loyalty_manager.php">LOYALTY MANAGER</a>
        <a href="feedback_manager.php">FEEDBACK MANAGER</a>
        <a href="inventorymanagement.php">INVENTORY MANAGER</a>
        <a href="order_management.php">ORDER MANAGER</a>
        <a href="contactUs_manager.php">CONTACT US MANAGER</a>
        <a href="return_manager.php">RETURN MANAGER</a>
    <?php endif; ?>
</div>

<h2>RETURN MANAGER</h2>
<p>VIEW RETURN REQUESTS, APPROVE/REJECT.</p>

<table>
    <thead>
        <tr>
            <th>ORDER ID</th>
            <th>USER ID</th>
            <th>REASON</th>
            <th>DETAILS</th>
            <th>STATUS</th>
            <th>ACTIONS</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($returns as $return): ?>
            <tr>
                <td><?= htmlspecialchars($return['order_id']); ?></td>
                <td><?= htmlspecialchars($return['user_id']); ?></td>
                <td><strong><?= htmlspecialchars($return['reason']); ?></strong></td>
                <td><?= htmlspecialchars($return['details']); ?></td>
                <td id="status_<?= $return['id']; ?>"><?= htmlspecialchars($return['status']); ?></td>
                <td>
                    <button class="approve-btn" onclick="updateStatus(<?= $return['id']; ?>, 'APPROVED')">APPROVE</button>
                    <button class="reject-btn" onclick="updateStatus(<?= $return['id']; ?>, 'REJECTED')">REJECT</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateStatus(returnId, status) {
        $.ajax({
            url: 'update_return_status.php',
            type: 'POST',
            data: { id: returnId, status: status },
            success: function(response) {
                console.log("Server Response: " + response); // Debugging
                if (response.includes("success")) {
                    document.getElementById('status_' + returnId).innerText = status;
                } else {
                    alert("Failed to update status: " + response);
                }
            },
            error: function() {
                alert("Error connecting to server.");
            }
        });
    }
</script>



</body>
</html>
