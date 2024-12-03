<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars(trim($_POST['firstname']));
    $lastName = htmlspecialchars(trim($_POST['lastname']));
    $country = htmlspecialchars(trim($_POST['country']));
    $message = htmlspecialchars(trim($_POST['subject']));

    $to = "email"; 
    $subject = "New Message from $firstName $lastName";
    $body = "You have received a new message.\n\n" .
            "Name: $firstName $lastName\n" .
            "Country: $country\n\n" .
            "Message:\n$message";

    $headers = "From: noreply@cs2410-web01pvm.aston.ac.uk\r\n"; 
    if (mail($to, $subject, $body, $headers)) {
        echo "<p>Thank you for your message, $firstName. We’ll get back to you soon!</p>";
    header("refresh:3;url=contact.html");
    } else {
        echo "<p>Sorry, your message could not be sent. Please try again later.</p>";
    header("refresh:3;url=contact.html");
    }
} else {
    header("Location: contact.html");
    exit;
}
?>
