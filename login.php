<<<<<<< Updated upstream
=======

<!--Dylan section-->

>>>>>>> Stashed changes
<?php
include 'db_config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->execute(['username' => $username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Store session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Redirect to a protected page
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>
<<<<<<< Updated upstream
 <!--  NEED TO ADD THIS TO FRONTEND FORM HTML <form action="login.php" method="POST"></form>  -->
=======

 <!--  NEED TO ADD THIS TO FRONTEND FORM HTML <form action="login.php" method="POST"></form>  -->
>>>>>>> Stashed changes
