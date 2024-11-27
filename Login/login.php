<?php
// Database connection settings
$servername = "cs2410-web01pvm.aston.ac.uk";
$username = "cs2team30";
$password = "To9JV8nPTCYwpMh";
$dbname = "cs2team30_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$user_username = $_POST['username'];
$user_password = $_POST['password'];

// Check if user exists
$sql = "SELECT user_id, username, password FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $username, $hashed_password);
    $stmt->fetch();

    if (password_verify($user_password, $hashed_password)) {
        echo "Login successful! Welcome, " . $username;
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found with that username.";
}

$stmt->close();
$conn->close();
?>
