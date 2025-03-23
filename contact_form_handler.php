<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $query = "INSERT INTO contact_us (email, subject, message) VALUES (:email, :subject, :message)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':message', $message);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "<p style='color: green; font-weight: bold; text-align: center;'>Message has been sent successfully!</p>";
    } else {
        $_SESSION['success_message'] = "<p style='color: red; font-weight: bold; text-align: center;'>There was an error, please try again.</p>";
    }
}

// ✅ Stay on the same page & display message
header("Location: contact.php");
exit;
