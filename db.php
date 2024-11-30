<?php

$db_host = 'localhost'; 
$db_name = 'cs2team30_db'; 
$username = 'cs2team30'; 
$password = 'To9JV8nPTCYwpMh'; 

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
    error_log("Failed to connect to the database: " . $ex->getMessage());
    die("Connection error: " . $ex->getMessage());
}
?>

