<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = trim($_POST['userName']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if (empty($userName) || empty($comment) || $rating < 1 || $rating > 5) {
        die("Invalid input. Please check your details and try again.");
    }

    // Store feedback in a text file (could also be a database)
    $feedbackData = "User: $userName | Rating: $rating | Comment: $comment\n";
    file_put_contents("feedback_data.txt", $feedbackData, FILE_APPEND);

    echo "Thanks for your feedback! It has been recorded.";
}
?>
