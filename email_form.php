<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars(trim($_POST['firstname']));
    $lastName = htmlspecialchars(trim($_POST['lastname']));
    $country = htmlspecialchars(trim($_POST['country']));
    $message = htmlspecialchars(trim($_POST['subject']));

    $to = "gorsingh200@gmail.com"; 
    $subject = "New Message from $firstName $lastName";
    $body = "You have received a new message.\n\n" .
            "Name: $firstName $lastName\n" .
            "Country: $country\n\n" .
            "Message:\n$message";

    $headers = "From: noreply@yourdomain.com"; 
    if (mail($to, $subject, $body, $headers)) {
        echo "<p>Thank you for your message, $firstName.</p>";
    } else {
        echo "<p>Sorry, your message could not be sent. Please retry to send the message</p>";
    }
} else {
    header("Location: contact.html");
    exit;
}
?>
