<?php
session_start();
require '../includes/config.php';

// Redirect non-admin users
if (!isset($_SESSION["user_id"]) || $_SESSION["is_admin"] != 1) {
    header("Location: ../index.php");
    exit();
}

// ✅ Fetch total users from users table
$total_users_query = "SELECT COUNT(*) AS total_users FROM users";
$total_users_result = $conn->query($total_users_query);
$total_users = $total_users_result->fetch_assoc()['total_users'];

// ✅ Fetch total orders from orders table
$total_orders_query = "SELECT COUNT(*) AS total_orders FROM orders";
$total_orders_result = $conn->query($total_orders_query);
$total_orders = $total_orders_result->fetch_assoc()['total_orders'];

// ✅ Fetch total revenue from confirmed orders
$total_revenue_query = "
    SELECT COALESCE(SUM(total_price), 0) AS total_revenue 
    FROM orders 
    WHERE status IN ('Processing', 'Shipped', 'Delivered')";
$total_revenue_result = $conn->query($total_revenue_query);
$total_revenue = $total_revenue_result->fetch_assoc()['total_revenue'] ?? 0;

// ✅ Fetch total products from products table
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

    <!-- Load FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css"> <!-- Ensure this file is properly linked -->

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        .admin-container {
            colors:black;
            width: 90%;
            max-width: 900px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .stats-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: black;
            width: 200px;
            text-align: center;
            transition: 0.3s;
            display: inline-block;
        }

        .stat-box:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .stat-box i {
            font-size: 40px;
            color: blue;
        }

        .btn-container {
            margin-top: 20px;
            text-align: center;
        }

        .logout-btn {
            background: #ff5733;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #cc4628;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: gray;
        }
    </style>
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <div class="admin-container">
    <h2 style="color: black;">Admin Dashboard</h2>
    <p style="color: black;">Welcome, <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>!</p>

        <div class="stats-container">
            <a href="users.php" class="stat-box">
                <i class="fas fa-users"></i>
                <p>Total Users: <strong><?php echo $total_users; ?></strong></p>
            </a>
            <a href="orders.php" class="stat-box">
                <i class="fas fa-shopping-cart"></i>
                <p>Total Orders: <strong><?php echo $total_orders; ?></strong></p>
            </a>
            <a href="revenue.php" class="stat-box">
                <i class="fas fa-dollar-sign"></i>
                <p>Total Revenue: <strong>₱<?php echo number_format($total_revenue, 2); ?></strong></p>
            </a>
            <a href="products.php" class="stat-box">
                <i class="fas fa-box"></i>
                <p>Total Products: <strong><?php echo $total_products; ?></strong></p>
            </a>
        </div>

        <div class="btn-container">
            <a href="../pages/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <footer class="footer">
        &copy; 2025 DBTK Store. All Rights Reserved.
    </footer>

</body>
</html>
