<?php
session_start();
require 'db.php';

// Debugging - Show Errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

//  Ensure Admin Access
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die("ACCESS DENIED");
}

//  Validate Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $return_id = $_POST['id'];
    $status = $_POST['status'];

    //  Ensure status is valid
    if (!in_array($status, ['APPROVED', 'REJECTED'])) {
        die("INVALID STATUS");
    }

   
    $query = "UPDATE returns SET status = :status WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $return_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid_request";
}
?>
