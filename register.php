<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hashing the password

    $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $conn->prepare($query);

    try {
        $stmt->execute(['username' => $username, 'password' => $password]);
        echo "Registration successful. <a href='login.html'>Login here</a>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Integrity constraint violation: 1062 Duplicate entry
            echo "Username already exists. <a href='signup.html'>Try again</a>";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>