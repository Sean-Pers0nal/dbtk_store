<?php
session_start();
require '../includes/config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["is_admin"] != 1) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

// Ensure database connection exists
if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// Fetch latest total revenue
$query = "SELECT COALESCE(SUM(total_price), 0) AS total_revenue FROM orders WHERE status IN ('Processing', 'Shipped', 'Delivered')";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $total_revenue = $row['total_revenue'] ?? 0;
    echo json_encode(["total_revenue" => number_format($total_revenue, 2)]);
} else {
    echo json_encode(["error" => "Failed to fetch revenue"]);
}
?>
