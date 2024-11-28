<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into database
        $query = "INSERT INTO users (email, password) VALUES (:email, :password)";
        $stmt = $conn->prepare($query);

        try {
            $stmt->execute([
                ':email' => $email,
                ':password' => $hashed_password,
            ]);
            $success = "Account created successfully! <a href='login.html'>Login here</a>";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { //IF Email already exists
                $error = "Email already exists!";
            } else {
                $error = "An error occurred: " . $e->getMessage();
            }
        }
    }
}
include 'sign_up.html';
?>
