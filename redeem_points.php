<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT points FROM loyalty_points WHERE user_id = :user_id");
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);
$points = $user_data ? $user_data['points'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['redeem_discount'])) {
    $redeem_points = intval($_POST['redeem_points']);
    
    if ($redeem_points > 0 && $redeem_points <= $points) {
     
        $discount_percentage = floor($redeem_points / 5000) * 5;
        
        if ($discount_percentage > 0) {
           
            $new_points = $points - $redeem_points;
            $stmt = $conn->prepare("UPDATE loyalty_points SET points = :new_points WHERE user_id = :user_id");
            $stmt->bindValue(':new_points', $new_points, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            
            $_SESSION['redeemed_discount'] = $discount_percentage;

            
            $_SESSION['discount_message'] = "$discount_percentage% discount has been applied to your cart!";
            header ("Location: checkout.php");
            exit;
        } else {
            $error_message = "You must redeem at least 5000 points to get a discount.";
        }
    } else {
        $error_message = "Invalid number of points.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redeem Loyalty Points</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>

<h1>Coming Soon! <br> Redeem Your Loyalty Points </h1>

<p>Your Current Points: <strong><?= $points ?></strong></p>

<?php if (isset($success_message)): ?>
    <p class="success"><?= $success_message; ?></p>
<?php elseif (isset($error_message)): ?>
    <p class="error"><?= $error_message; ?></p>
<?php endif; ?>

<h3>Redeem for Discount</h3>
<form method="POST">
    <label for="redeem_points">Enter Points to Redeem (5000 points = 5% off):</label>
    <input type="number" name="redeem_points" id="redeem_points" min="5000" step="5000" max="<?= $points ?>" required>
    <button type="submit" name="redeem_discount">Redeem</button>
</form>

<a href="profile.php">Back to Profile</a>

</body>
</html>
