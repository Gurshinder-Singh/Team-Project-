
<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM admin_log WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id']; 
                $_SESSION['username'] = $user['email']; 
                header("Location: welcome.php"); 
                exit();
            } else {
                echo "<div class='error'>Invalid password. Please try again.</div>";
            }
        } else {
            echo "<div class='error'>No account found with that email. Please sign up.</div>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
