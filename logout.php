<?php
session_start();


$_SESSION = [];

session_destroy();

// Redirects to login page
header("Location: login.html");
exit();
?>
