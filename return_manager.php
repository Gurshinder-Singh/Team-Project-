<?php
session_start();
require 'db.php';


ini_set('display_errors', 1);
error_reporting(E_ALL);

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    exit("ACCESS DENIED: You must be an admin to view this page.");
}

$sql = "
    SELECT r.*, p.name AS product_name
    FROM returns r
    JOIN orders o ON r.order_id = o.order_id
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    ORDER BY r.created_at DESC
";
$fetchReturns = $conn->prepare($sql);
$fetchReturns->execute();
$returnRequests = $fetchReturns->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 600) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$_SESSION['last_activity'] = time();
?>

<script>
    setTimeout(function() {
        window.location.href = 'login.php';
    }, 600000); // 600000ms = 10 minutes
</script>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>RETURN MANAGER - LUXUS</title>
  <link rel="stylesheet" href="stylesheet.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: white;
      margin: 0;
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
            z-index: 100;
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

    h2 {
      text-align: center;
      margin-top: 100px;
      font-size: 2em;
      color: #d4af37;
    }

    p {
      text-align: center;
      font-size: 1.1em;
      color: #333;
      margin-bottom: 30px;
    }

    table {
      width: 90%;
      border-collapse: collapse;
      margin-top: 20px;
          margin-left: auto;
      margin-right: auto;

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
 <!-- NAV BAR -->

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


  <h2>RETURN MANAGER</h2>
  <p>Review return requests and manage approvals below.</p>

  <table>
    <thead>
      <tr>
       <th>ORDER ID</th>
       <th>PRODUCT NAME</th>
       <th>USER ID</th>
       <th>REASON</th>
       <th>DETAILS</th>
       <th>STATUS</th>
       <th>ACTIONS</th>
     </tr>
    </thead>
    <tbody>
      <?php foreach ($returnRequests as $return): ?>
        <tr>
          <td><?= htmlspecialchars($return['order_id']); ?></td>
          <td><?= htmlspecialchars($return['product_name']); ?></td>
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
    function updateStatus(returnId, status) {
      $.ajax({
        url: 'update_return_status.php',
        type: 'POST',
        data: {
          id: returnId,
          status: status
        },
        success: function (response) {
          if (response.includes("success")) {
            document.getElementById('status_' + returnId).innerText = status;
          } else {
            alert("Update failed: " + response);
          }
        },
        error: function () {
          alert("Connection error. Please try again.");
        }
      });
    }
  </script>
</body>
</html>










