<?php
session_start();
require '../includes/config.php'; // Database connection

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT c.id, p.name, p.price, p.image, c.quantity 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_price = 0;
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}
$stmt->close();

if (empty($cart_items)) {
    echo "<p>Your cart is empty. Please add items before proceeding.</p>";
    exit();
}

// Handle order confirmation
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $delivery_address = isset($_POST['address']) ? trim($_POST['address']) : '';
    
    if (!empty($delivery_address)) {
        $orderList = json_encode($cart_items);
        $insert_sql = "INSERT INTO orders (user_id, total_price, quantity, orderList, deliveryAddress) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $total_quantity = array_sum(array_column($cart_items, 'quantity'));
        $insert_stmt->bind_param("idiss", $user_id, $total_price, $total_quantity, $orderList, $delivery_address);
        $insert_stmt->execute();
        $insert_stmt->close();

        // Clear cart after order confirmation
        $clear_cart_sql = "DELETE FROM cart WHERE user_id = ?";
        $clear_cart_stmt = $conn->prepare($clear_cart_sql);
        $clear_cart_stmt->bind_param("i", $user_id);
        $clear_cart_stmt->execute();
        $clear_cart_stmt->close();

        header("Location: ../pages/checkout_success.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            background-color: #121212; /* Modern black theme */
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 800px;
            width: 100%;
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(255, 255, 255, 0.1);
        }
        h1 {
            text-align: center;
            color: white;
        }
        .product-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .product-item {
            display: flex;
            align-items: center;
            gap: 20px;
            border: 1px solid #333;
            padding: 15px;
            border-radius: 10px;
            background-color: #222;
        }
        .product-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .product-details {
            flex-grow: 1;
            font-weight: bold;
        }
        .quantity {
            font-weight: bold;
            color: #ffcc00;
        }
        .total-price {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
            color: #ffcc00;
        }
        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
        }
        input[type="text"] {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #333;
            color: white;
        }
        .error-message {
            color: red;
            font-size: 14px;
            display: none;
            margin-bottom: 10px;
        }
        .btn-group {
            display: flex;
            justify-content: space-between;
        }
        button, .goback-btn {
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 48%;
            text-align: center;
        }
        .confirm-btn {
            background-color: #28a745;
            color: white;
        }
        .confirm-btn:hover {
            background-color: #218838;
        }
        .goback-btn {
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .goback-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Confirm Your Order</h1>
        <div class="product-list">
            <?php foreach ($cart_items as $item) : ?>
                <div class="product-item">
                    <img src="../uploads/<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>">
                    <div class="product-details">
                        <h3><?= htmlspecialchars($item['name']); ?></h3>
                        <p>₱<?= number_format($item['price'], 2); ?></p>
                    </div>
                    <p class="quantity">x<?= $item['quantity']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="total-price">Total Price: ₱<?= number_format($total_price, 2); ?></p>

        <form method="POST" onsubmit="return validateForm()">
            <label for="address">Delivery Address:</label>
            <input type="text" id="address" name="address">
            <p id="error-message" class="error-message">Delivery address cannot be empty.</p>

            <div class="btn-group">
                <button type="submit" class="confirm-btn">Confirm Order</button>
                <a href="cart.php" class="goback-btn">Go Back</a>
            </div>
        </form>
    </div>

    <script>
    function validateForm() {
        let addressInput = document.getElementById("address").value.trim();
        let errorMessage = document.getElementById("error-message");

        if (addressInput === "") {
            errorMessage.style.display = "block"; // Show error message
            return false; // Prevent form submission
        }
        return true;
    }
    </script>
</body>
</html>
