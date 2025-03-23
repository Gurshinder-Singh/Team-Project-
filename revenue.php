<?php
session_start();
require 'db.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: homepage.php");
    exit();
}

$totalRevenue = 0;
$startDate = null;
$endDate = null;
$revenueData = [];
$revenueChartData = [];

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-30 days'));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
}

try {
    $startDateParam = $startDate . ' 00:00:00';
    $endDateParam = $endDate . ' 23:59:59';

    $sql = "SELECT total_price, created_at FROM orders WHERE status = 'Completed' AND created_at BETWEEN :startDate AND :endDate";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':startDate', $startDateParam);
    $stmt->bindParam(':endDate', $endDateParam);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);



    foreach ($results as $row) {
        $totalRevenue += $row['total_price'];
        $revenueData[] = [
            'total_price' => $row['total_price'],
            'created_at' => $row['created_at']
        ];

        $date = date('Y-m-d', strtotime($row['created_at']));
        if (!isset($revenueChartData[$date])) {
            $revenueChartData[$date] = 0;
        }
        $revenueChartData[$date] += $row['total_price'];
    }
} catch (PDOException $e) {
    die("Error fetching revenue: " . $e->getMessage());
}

$chartLabels = array_keys($revenueChartData);
$chartValues = array_values($revenueChartData);
$chartDataJSON = json_encode(['labels' => $chartLabels, 'values' => $chartValues]);

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
    <title>Revenue Report</title>
    <link rel="icon" type="image/favicon" href="asset/LUXUS_logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            position: relative;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        .revenue-form {
            margin-bottom: 20px;
        }

        .revenue-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .revenue-table th, .revenue-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .revenue-table th {
            background-color: #f2f2f2;
        }

        .total-revenue {
            margin-top: 20px;
            font-weight: bold;
            text-align: center;
            font-size: 1.5em;
        
        }

        .chart-container {
            width: 80%;
            margin: 20px auto;
            padding: 50px;

        
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
            background-color: white !important;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            transition: transform 0.3s ease-in-out;
        }

        .dropdown-content a {
            color: black !important;
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

        .navbar > a {
            color: white !important;
        }

        .navbar > a:hover {
            color: gray !important;
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

<!-- CHART -->
    <div class="content">
       
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
<div class="total-revenue">
            Total Revenue: £<?php echo number_format($totalRevenue, 2); ?>
        </div>
        <form method="post" class="revenue-form">
            <label for="startDate">Start Date:</label>
            <input type="date" name="startDate" id="startDate" required value="<?php echo $startDate; ?>">

            <label for="endDate">End Date:</label>
            <input type="date" name="endDate" id="endDate" required value="<?php echo $endDate; ?>">

            <button type="submit">Generate Report</button>
        </form>

        <?php if (!empty($revenueData)): ?>
            <table class="revenue-table">
                <thead>
                    <tr>
                        <th>Order Date</th>
                        <th>Order Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($revenueData as $data): ?>
                        <tr>
                            <td><?php echo date('Y-m-d H:i:s', strtotime($data['created_at'])); ?></td>
                            <td>£<?php echo number_format($data['total_price'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
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
        const chartData = <?php echo $chartDataJSON; ?>;
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.values,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>