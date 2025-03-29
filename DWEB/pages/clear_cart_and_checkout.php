<?php
session_start();
require '../includes/config.php'; 

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Clear the cart
$delete_sql = "DELETE FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($delete_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Redirect to the checkout page
header("Location: checkout.php");
exit();
?>
