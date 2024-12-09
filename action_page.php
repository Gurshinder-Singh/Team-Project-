<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $customerNumber = htmlspecialchars(trim($_POST['customer-number']));
    $message = htmlspecialchars(trim($_POST['message']));

   
    $to = "email";
    $subject = " Message from Customer";

    
    $body = "You have received a new message.\n\n" .
            "Email Address: $email\n" .
            ($customerNumber ? "Customer Number: $customerNumber\n" : "") . 
            "Message:\n$message";

    $headers = "From: noreply@cs2410-web01pvm.aston.ac.uk"; 

    if (mail($to, $subject, $body, $headers)) {
        echo "<p>Thank you for your message. We’ll get back to you soon!</p>";
        header("refresh:3;url=contact.php");
    } else {
        echo "<p>Sorry, your message could not be sent. Please try again later.</p>";
        header("refresh:3;url=contact.php");
    }
} else {
    header("Location: contact.php");
    exit;
}
?>
