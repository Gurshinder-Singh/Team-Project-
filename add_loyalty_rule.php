<?php
session_start();
require_once 'db.php';

// Check if user is an admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_amount = floatval($_POST['order_amount']);
    $loyalty_points = intval($_POST['loyalty_points']);
    $rule_description = trim($_POST['rule_description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $conn->prepare("INSERT INTO loyalty_rules (order_amount, loyalty_points, rule_description, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $order_amount, $loyalty_points, $rule_description, $start_date, $end_date);

    if ($stmt->execute()) {
        $success_message = "Loyalty rule added successfully!";
    } else {
        $error_message = "Error adding loyalty rule: " . $conn->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Loyalty Rule</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Add New Loyalty Rule</h1>

<?php if (isset($success_message)): ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
<?php elseif (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>

<!-- Add New Loyalty Rule Form -->
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

</body>
</html>

<?php

$conn->close();
?>
