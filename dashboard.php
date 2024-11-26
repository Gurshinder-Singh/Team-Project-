<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.html");
    exit();
}

echo "Welcome to Luxus, " . htmlspecialchars($_SESSION['username']) . "!";
?>
