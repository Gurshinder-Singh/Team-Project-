<?php
$servername = "cs2410-web01pvm.aston.ac.uk";  
$username = "cs2team30";                      
$password = "To9JV8nPTCYwpMh";                
$dbname = "cs2team30_db";                     

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to the database.";
?>
