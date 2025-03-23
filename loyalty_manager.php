<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}


$stmt = $conn->prepare("SELECT user_id, name FROM users ORDER BY name ASC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_loyalty'])) {
    $user_id = intval($_POST['user_id']);
    $points = intval($_POST['points']);
    $discount = floatval($_POST['discount']);
    $reward = trim($_POST['reward']);
    $expiration_date = $_POST['expiration_date'];

  
    $sql = "INSERT INTO loyalty_points (user_id, points, discount, reward, expiration_date)
            VALUES (:user_id, :points, :discount, :reward, :expiration_date)
            ON DUPLICATE KEY UPDATE points = VALUES(points), discount = VALUES(discount), 
                reward = VALUES(reward), expiration_date = VALUES(expiration_date)";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':points', $points, PDO::PARAM_INT);
    $stmt->bindValue(':discount', $discount, PDO::PARAM_STR);
    $stmt->bindValue(':reward', $reward, PDO::PARAM_STR);
    $stmt->bindValue(':expiration_date', $expiration_date, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $success_message = "Loyalty points updated successfully!";
    } else {
        $error_message = "Error updating loyalty points.";
    }
}
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM loyalty_points WHERE user_id = :user_id");
    $stmt->bindValue(':user_id', $delete_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        header("Location: loyalty_manager.php?deleted=true");
        exit;
    }
}


$query = "SELECT lp.*, u.name FROM loyalty_points lp 
          JOIN users u ON lp.user_id = u.user_id";
$result = $conn->query($query);

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Loyalty Manager</title>
	<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">

       <style>
        body {
        	font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            position: relative;
			padding: 0;
			padding-top: 90px;
            overflow:auto;
        }

      
.content {
    padding-top: 90px; 
}

      
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        	
        }

        form {
            background: white;
            min-width: 500px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;

        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            height: 80px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #363636;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #222;
        }

      
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #363636;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #f1f1f1;
        }

     
        .success {
            color: green;
            text-align: center;
            font-weight: bold;
        }

        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            table {
                width: 100%;
            }

            form {
                width: 90%;
            }

            .navbar a {
                font-size: 14px;
                padding: 10px;
            }
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

    </style>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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
<h1>Loyalty Manager</h1>

<?php if (isset($success_message)): ?>
    <p class="success"><?php echo $success_message; ?></p>
<?php elseif (isset($error_message)): ?>
    <p class="error"><?php echo $error_message; ?></p>
<?php endif; ?>

<!-- Form for Updating Loyalty Points -->
<h3 style="text-align:center;">Update Customer Loyalty</h3>
<form method="post" action="loyalty_manager.php">
    <label for="user_id">Select User:</label>
    <select id="user_id" name="user_id" required>
        <option value="">-- Select a User --</option>
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['user_id'] ?>"><?= $user['user_id'] . " - " . htmlspecialchars($user['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="points">Loyalty Points:</label>
    <input type="number" id="points" name="points" min="0" required>

    <label for="reward">Reward Description:</label>
<textarea id="reward" name="reward" placeholder="Enter Authorisation Name" ></textarea>

    <button type="submit" name="update_loyalty">Update Loyalty</button>
</form>

<!-- Loyalty Points Table -->
<h3 style="text-align:center;">Existing Customer Loyalty Data</h3>
<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Loyalty Points</th>
            <th>Reward</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['user_id']; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= $row['points']; ?></td>
                <td><?= $row['reward']; ?></td>
                <td><a href="loyalty_manager.php?edit=<?= $row['user_id']; ?>">Edit</a></td>
                <td>
    <a href="loyalty_manager.php?edit=<?= $row['user_id']; ?>">Edit</a> | 
    <a href="loyalty_manager.php?delete=<?= $row['user_id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
</td>

            </tr>
            
        <?php endwhile; ?>
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

</body>
</html>