<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

echo "<h1>Welcome, " . $_SESSION['email'] . "!</h1>";
echo "<p>You are logged in.</p>";

// Optionally add a logout link
//echo '<a href="logout.php">Logout</a>';
?>
