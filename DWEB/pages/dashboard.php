<?php
session_start();
require '../includes/config.php'; // Ensure this is the correct path to your config file

// Check if user is logged in and is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["is_admin"] != 1) {
    header("Location: ../pages/login.php");
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
$total_revenue = $total_revenue_result->fetch_assoc()['total_revenue'];
$total_revenue = $total_revenue ? $total_revenue : 0; // Ensure it doesn't display NULL

// Fetch total products
$total_products_query = "SELECT COUNT(*) AS total_products FROM products";
$total_products_result = $conn->query($total_products_query);
$total_products = $total_products_result->fetch_assoc()['total_products'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Link your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            text-align: center;
        }
        .dashboard-container {
            width: 80%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .card {
            background: #ff5733;
            color: white;
            padding: 20px;
            border-radius: 10px;
            width: 200px;
            text-align: center;
        }
        .card h2 {
            margin: 0;
        }
        .logout-btn {
            text-decoration: none;
            background: #333;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        .logout-btn:hover {
            background: #555;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <h1>Welcome, Admin <?php echo $_SESSION["username"]; ?>!</h1>
        
        <div class="stats">
            <div class="card">
                <h2><?php echo $total_users; ?></h2>
                <p>Total Users</p>
            </div>
            <div class="card">
                <h2><?php echo $total_orders; ?></h2>
                <p>Total Orders</p>
            </div>
            <div class="card">
                <h2>₱<?php echo number_format($total_revenue, 2); ?></h2>
                <p>Total Revenue</p>
            </div>
            <div class="card">
                <h2><?php echo $total_products; ?></h2>
                <p>Total Products</p>
            </div>
        </div>

        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

</body>
</html>
