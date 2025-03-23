<?php
// action_page.php

// Database connection (use your own db connection file)
require_once('db_connection.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Prepare SQL query to insert the contact us submission
    $sql = "INSERT INTO contact_us (email, subject, message) VALUES ('$email', '$subject', '$message')";

    // Execute query
    if (mysqli_query($conn, $sql)) {
        echo "Thank you for contacting us. We will get back to you shortly.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
}
?>
