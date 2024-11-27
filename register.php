<!-- Bilal section-->
<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

if (mysqli_query($conn, $sql)) {
    echo "Registration successful";
} else {
        echo "Error: " . mysqli_error($conn);
}

    mysqli_close($conn);
}

?>

