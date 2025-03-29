<?php
session_start();
require '../includes/config.php'; // Database connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle "Add to Cart"
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);

    // Check if item is already in the cart
    $check_sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($cart_id, $existing_quantity);
        $stmt->fetch();
        $new_quantity = $existing_quantity + $quantity;
    
        $update_sql = "UPDATE cart SET quantity = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_quantity, $cart_id);
        $update_stmt->execute();
        $update_stmt->close();
    }
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Item exists, update quantity
        $stmt->bind_result($existing_quantity);
        $stmt->fetch();
        $new_quantity = $existing_quantity + $quantity;

        $update_sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Item does not exist, insert new record
        $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
    $stmt->close();

    header("Location: cart.php");
    exit();
}

// Fetch cart items from the database
$sql = "SELECT c.id, p.name, p.price, p.image, c.quantity 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$cartTotal = 0;
$cartItemCount = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $cartTotal += $row['price'] * $row['quantity'];
    $cartItemCount += $row['quantity'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DBTK - Cart</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">

    <style>
        .cart-summary {
    text-align: center; /* Centers everything inside */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
}

.cart-summary form {
    width: 100%;
    display: flex;
    justify-content: center;
}

.cart-summary button, .cart-summary .btn-shop {
    display: block;
    width: 250px; /* Adjust button width */
    padding: 10px;
    text-align: center;
    margin: 10px 0; /* Adds spacing between buttons */
    font-size: 16px;
}
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
            padding: 20px;
        }
        .footer {
            background: black;
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            position: relative;
            bottom: 0;
        }
        /* Floating Message */
        .floating-message {
            display: none;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <main>
        <h1>Your Shopping Cart</h1>

        <div class="product-container">
            <?php if (!empty($cart_items)) : ?>
                <?php foreach ($cart_items as $item) : ?>
                    <div class="product-frame">
                        <img src="../uploads/<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>">
                        <h3><?= htmlspecialchars($item['name']); ?></h3>
                        <p>₱<?= number_format($item['price'], 2); ?></p>
                        <p>Quantity: <?= $item['quantity']; ?></p>

                        <!-- Remove Item Form -->
                        <form action="remove_from_cart.php" method="POST" onsubmit="return confirmRemove();">
                            <input type="hidden" name="cart_id" value="<?= $item['id']; ?>">
                            <button type="submit" name="remove_item" class="remove-btn">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>

        <div class="cart-summary">
            <h3>Subtotal: ₱<?= number_format($cartTotal, 2); ?></h3>

            <!-- Checkout Form -->
            <form id="checkoutForm" action="../pages/order_confirmation.php" method="POST">
                <input type="hidden" name="cart_total" value="<?= $cartTotal; ?>">
                <input type="hidden" name="cart_quantity" value="<?= $cartItemCount; ?>">
                <input type="hidden" name="cart_items" value="<?= htmlentities(json_encode($cart_items), ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" <?= empty($cart_items) ? 'disabled' : ''; ?>>Proceed to Checkout</button>
            </form>

            <a href="/DWEB/pages/shop.php" class="btn-shop">Back to Shopping</a>
        </div>
    </main>

    <!-- Floating Message -->
    <div id="floatingMessage" class="floating-message"></div>

    <script>
        function confirmRemove() {
            return confirm("Are you sure you want to remove this item?");
        }

        window.onload = function() {
            let messageBox = document.getElementById('floatingMessage');
            let message = "<?= $_SESSION['checkout_success'] ?? ''; ?>";
            if (message) {
                messageBox.innerText = message;
                messageBox.style.display = 'block';
                setTimeout(() => { messageBox.style.display = 'none'; }, 2000);
                <?php unset($_SESSION['checkout_success']); ?>
            }
        };
    </script>

    <footer class="footer">
        &copy; 2025 DBTK Store. All Rights Reserved.
    </footer>
</body>
</html>
