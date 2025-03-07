<?php
session_start();
require_once 'db.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $stmt = $conn->prepare("SELECT p.product_id, p.name 
                            FROM order_items oi
                            JOIN products p ON oi.product_id = p.product_id
                            WHERE oi.order_id = :order_id");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    $products_in_order = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['submit_review'])) {
    $fullName = $_POST['full_name'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];
    $order_id = $_GET['order_id'];
    $product_id = $_POST['product_id'];

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        echo "User not logged in.";
        exit;
    }

    $order_id = intval($order_id);

    try {
        $stmt = $conn->prepare("INSERT INTO CustomerFeedback (user_id, order_id, product_id, Rating, Review, fullname)
                                    VALUES (:user_id, :order_id, :product_id, :rating, :feedback, :fullname)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':feedback', $feedback, PDO::PARAM_STR);
        $stmt->bindParam(':fullname', $fullName, PDO::PARAM_STR);

        $stmt->execute();

        echo "<script>
                alert('Thank you for your feedback!');
                window.location.href = 'profile.php';
              </script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . " - SQL: " . $stmt->queryString;
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback | LUXUS</title>
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #5C4033;
            color: white;
        }
        .nav-links {
            list-style: none;
            display: flex;
            justify-content: center;
            background: #6B4A37;
            padding: 15px;
        }
        .nav-links li {
            margin: 0 15px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px;
            transition: background 0.3s ease, color 0.3s ease;
        }
        .nav-links a:hover {
            background: #F0C987;
            color: black;
            border-radius: 5px;
        }
        .container {
            padding: 40px;
            max-width: 700px;
            margin: auto;
            background: #835C44;
            color: white;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        h1, h2 {
            text-align: center;
            color: #F0C987;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #F0C987;
            font-size: 16px;
            background-color: white;
            color: black;
        }
        textarea {
            resize: none;
            height: 120px;
        }
        button {
            background-color: #F0C987;
            color: black;
            padding: 12px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
        }
        button:hover {
            background-color: #E0B76A;
        }
    </style>
</head>
<body>

    <header>
        <nav>
            <ul class="nav-links">
                <li><a href="profile.php">Profile</a></li>
                <li><a href="reviews.html">Reviews</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Customer Feedback</h1>
        <p>We value your opinion! Let us know what you think about your new watch.</p>

        <form id="feedbackForm" action="<?php echo $_SERVER['PHP_SELF']; ?>?order_id=<?php echo $_GET['order_id']; ?>" method="post">

            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required placeholder="Enter your name">

            <label for="product_id">Select Product:</label>
            <select id="product_id" name="product_id" required>
                <?php foreach ($products_in_order as $product): ?>
                    <option value="<?= $product['product_id'] ?>"><?= $product['name'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="rating">Rate Your Experience:</label>
            <select id="rating" name="rating" required>
                <option value="5">⭐⭐⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="2">⭐⭐</option>
                <option value="1">⭐</option>
            </select>

            <label for="feedback">Your Review:</label>
            <textarea id="feedback" name="feedback" required placeholder="Write your review here..."></textarea>

            <button type="submit" name="submit_review">Submit Feedback</button>
        </form>
    </div>

</body>
</html>