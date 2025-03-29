<?php
session_start();
require 'config.php';

// Check if user is admin
if (!isset($_SESSION["user_id"]) || $_SESSION["is_admin"] != 1) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

// Fetch total users
$total_users_query = "SELECT COUNT(*) AS total_users FROM users";
$total_users_result = $conn->query($total_users_query);
$total_users = $total_users_result->fetch_assoc()['total_users'];

// Fetch total orders
$total_orders_query = "SELECT COUNT(*) AS total_orders FROM orders";
$total_orders_result = $conn->query($total_orders_query);
$total_orders = $total_orders_result->fetch_assoc()['total_orders'];

// Fetch total revenue
$total_revenue_query = "SELECT SUM(total_price) AS total_revenue FROM orders";
$total_revenue_result = $conn->query($total_revenue_query);
$total_revenue = $total_revenue_result->fetch_assoc()['total_revenue'] ?? 0;

// Fetch total products
$total_products_query = "SELECT COUNT(*) AS total_products FROM products";
$total_products_result = $conn->query($total_products_query);
$total_products = $total_products_result->fetch_assoc()['total_products'];

// Return JSON response
echo json_encode([
    "total_users" => $total_users,
    "total_orders" => $total_orders,
    "total_revenue" => number_format($total_revenue, 2),
    "total_products" => $total_products
]);
?>
