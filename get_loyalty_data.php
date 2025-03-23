<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id']; // Get logged-in user ID

// Get loyalty points
$stmt = $conn->prepare("SELECT points FROM loyalty_points WHERE user_id = ?");
$stmt->execute([$user_id]);
$points = $stmt->fetchColumn() ?: 0;

// Get vouchers
$stmt = $conn->prepare("SELECT code, discount, expiration_date FROM vouchers WHERE user_id = ?");
$stmt->execute([$user_id]);
$vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['points' => $points, 'vouchers' => $vouchers]);
?>
