<?php
$host = 'localhost'; 
$db_name = 'cs2team30_db'; 
$username = 'cs2team30'; 
$password = 'To9JV8nPTCYwpMh'; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>