<?php
session_start();
require '../includes/config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["is_admin"] != 1) {
    header("Location: ../index.php");
    exit();
}

// Handle Delete Request
if (isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];
    
    $conn->query("DELETE FROM orderline WHERE order_id = '$order_id'") or die($conn->error);
    $conn->query("DELETE FROM orders WHERE order_id = '$order_id'") or die($conn->error);

    header("Location: orders.php");
    exit();
}

// Handle Edit Request (Update Order Status)
if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];

    $conn->query("UPDATE orders SET status = '$new_status' WHERE order_id = '$order_id'") or die($conn->error);

    header("Location: orders.php");
    exit();
}

// Fetch orders
$query = "
    SELECT o.order_id, o.user_id, o.total_price, o.status, o.deliveryAddress, 
           COALESCE(SUM(oi.quantity), 0) AS total_quantity 
    FROM orders o 
    LEFT JOIN orderline oi ON o.order_id = oi.order_id 
    GROUP BY o.order_id
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script>
        function toggleDetails(orderId) {
            var detailsRow = document.getElementById('details-' + orderId);
            detailsRow.style.display = detailsRow.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: white;
            text-align: center;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .table-container {
            width: 90%;
            max-width: 1100px;
            background: rgba(30, 30, 30, 0.9);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 188, 212, 0.3);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #333;
            color: white;
        }
        th {
            background: #1e1e1e;
            color: #00bcd4;
            text-transform: uppercase;
        }
        tr:hover {
            background: rgba(0, 188, 212, 0.2);
            transition: 0.3s;
        }
        .toggle-btn {
            cursor: pointer;
            font-size: 20px;
        }
        .order-details {
            display: none;
            background: rgba(40, 40, 40, 0.8);
        }
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        .back-btn {
            display: inline-block;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: #00bcd4;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Orders List</h2>
    <div class="table-container">
        <table>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>Delivery Address</th>
                <th>Actions</th>
                <th>Details</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['order_id'] ?></td>
                <td><?= $row['user_id'] ?></td>
                <td>₱<?= number_format($row['total_price'], 2) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                        <select name="order_status">
                            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Shipped" <?= $row['status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="Delivered" <?= $row['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                            <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_order">Update</button>
                    </form>
                </td>
                <td><?= $row['total_quantity'] ?></td>
                <td><?= htmlspecialchars($row['deliveryAddress']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                        <button type="submit" name="delete_order">Delete</button>
                    </form>
                </td>
                <td>
                    <span class="toggle-btn" onclick="toggleDetails(<?= $row['order_id'] ?>)">▼</span>
                </td>
            </tr>
            <tr id="details-<?= $row['order_id'] ?>" class="order-details">
                <td colspan="8">
                    <strong>Products in Order:</strong>
                    <table>
                        <tr>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                        <?php 
                        $order_id = $row['order_id'];
                        $order_items = $conn->query("SELECT p.name, p.image, oi.quantity, oi.price FROM orderline oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = '$order_id'");
                        while ($item = $order_items->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td><img src="../img/<?= $item['image'] ?>" class="product-img"></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₱<?= number_format($item['price'], 2) ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <a href="admin.php" class="back-btn">← Back to Admin Panel</a>
</body>
</html>
