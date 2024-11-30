<?php
$host = 'localhost';
$dbname = 'cs2team30_db';
$username = 'cs2team30';
$password = 'To9JV8nPTCYwpMh';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection successful!<br>";
    
    // Check if the `users` table exists
    $result = $conn->query("SHOW TABLES LIKE 'users'");
    if ($result->rowCount() > 0) {
        echo "Table `users` exists and is connected correctly.";
    } else {
        echo "Table `users` does not exist in the database.";
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
