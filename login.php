<?php
// Include database connection
require_once 'db.php'; // This file should contain your database connection code.

// Start session
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        echo "Both fields are required.";
        exit;
    }

    try {
        // Prepare and execute query to fetch user
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Check if user exists
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array
        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name']; // Store the name in the session

                // Redirect to a protected page
                header("Location: dashboard.php");
                exit;
            } else {
                echo "Invalid email or password.";
            }
        } else {
            echo "No account found with this email.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
