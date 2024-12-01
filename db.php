
<?php
$host = "localhost";
$dbname = "cs2team30_db";
$username = "cs2team30";
$password = "To9JV8nPTCYwpMh";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

