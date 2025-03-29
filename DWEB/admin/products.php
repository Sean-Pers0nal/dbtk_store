<?php
session_start();
require '../includes/config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["is_admin"] != 1) {
    header("Location: ../index.php");
    exit();
}

$product_query = "SELECT * FROM products ORDER BY id DESC";
$products_result = $conn->query($product_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .container {
            width: 90%;
            max-width: 1100px;
            margin: auto;
            background: rgba(30, 30, 30, 0.9);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 188, 212, 0.3);
        }
        .btn {
            display: inline-block;
            background: #00bcd4;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn:hover { background: #0097a7; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #333;
            text-align: center;
        }
        th { background: #1e1e1e; color: #00bcd4; }
        tr:nth-child(even) { background: rgba(50, 50, 50, 0.5); }
        tr:hover { background: rgba(0, 188, 212, 0.2); }
        .product-image { width: 70px; height: 70px; border-radius: 6px; object-fit: cover; }
        .edit-btn, .delete-btn {
            padding: 8px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        .edit-btn { background: #f39c12; color: white; }
        .delete-btn { background: #e74c3c; color: white; }
        .delete-btn:hover { background: #c0392b; }
        .edit-btn:hover { background: #d68910; }
        .back-btn {
            display: inline-block;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: #00bcd4;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
            margin-top: 20px;
        }
        .back-btn:hover { background-color: #0097a7; }
    </style>
</head>
<body>

    <h2>Product Management</h2>
    <div class="container">
        <a href="admin.php" class="back-btn">← Back to Admin Panel</a>
        <br><br>
        <table>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $products_result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td>
                    <?php if (!empty($row['image'])) { ?>
                        <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" class="product-image">
                    <?php } else { ?>
                        <span style="color: red;">No Image</span>
                    <?php } ?>
                </td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td>₱<?= number_format($row['price'], 2) ?></td>
                <td><?= $row['stock'] ?></td>
                <td>
                    <a href="update_products.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                    <a href="delete_product.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>
