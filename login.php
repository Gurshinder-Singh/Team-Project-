<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->execute(['username' => $username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        echo "Login successful! Welcome to Luxus, " . htmlspecialchars($user['username']) . "!";
    } else {
        echo "Invalid username or password.";
    }
}
?>
 <!--  NEED TO ADD THIS TO FRONTEND FORM HTML <form action="login.php" method="POST"></form>  -->