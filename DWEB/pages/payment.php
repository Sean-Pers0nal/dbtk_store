<?php
session_start();
require '../includes/config.php';

// Check if the user is logged in and has the 'customer' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../pages/login.php");
    exit();
}

// Check if order details exist
if (!isset($_SESSION['order_details'])) {
    header("Location: checkout.php");
    exit();
}

// Retrieve order details
$order_details = $_SESSION['order_details'];
$total_price = $order_details['total_price'];
$total_quantity = $order_details['total_quantity'];
$items = $order_details['items'];
$order_id = $order_details['order_id'];

// Handle payment confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_payment'])) {
    $delivery_address = $_POST['delivery_address'] ?? ''; // Get the delivery address
    $payment_method = $_POST['payment_method'] ?? ''; 

    // Sanitize input
    $delivery_address = mysqli_real_escape_string($conn, $delivery_address);
    $payment_method = mysqli_real_escape_string($conn, $payment_method);

    // Update order with delivery address and payment method
    $query = "UPDATE orders SET delivery_address = ?, payment_method = ?, status = 'Processing' WHERE order_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('ssi', $delivery_address, $payment_method, $order_id);
        if ($stmt->execute()) {
            // Payment method redirection
            if ($payment_method == 'PayPal') {
                header("Location: paypal_payment.php?order_id=$order_id");
            } elseif ($payment_method == 'GCash') {
                header("Location: gcash_payment.php?order_id=$order_id");
            } else {
                header("Location: confirmation.php");
            }
            exit();
        } else {
            echo "Error updating order: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/payment.css?v=<?php echo time(); ?>">
    <title>Payment - DBTK Store</title>
</head>
<body>
<header class="header">
    <div class="header-left">
        <a href="../index.php">
            <img alt="DBTK Logo" src="../css/images/logo.png" class="logo" />
        </a>
        <h3 class="tixt">DBTK Store</h3>
    </div>
</header>

<section>
    <h1>Confirm Your Payment</h1>
    <div class="payment-summary">
        <h2>Order Details</h2>
        <ul>
        <?php foreach ($items as $id => $item): ?>
            <li>
                <?php echo htmlspecialchars($item['product_name']); ?> - 
                <?php echo $item['quantity']; ?> x ₱<?php echo number_format($item['product_price'], 2); ?>
            </li>
        <?php endforeach; ?>
        </ul>
        <hr>
        <h2>Total Quantity: <?php echo $total_quantity; ?></h2>
        <h2>Total Price: ₱<?php echo number_format($total_price, 2); ?></h2>
    </div>

    <form method="post" action="">
        <label for="delivery_address">Delivery Address:</label>
        <input type="text" id="delivery_address" name="delivery_address" placeholder="Enter Delivery Address" required>

        <label for="payment_method">Select Payment Method:</label>
        <select id="payment_method" name="payment_method" required>
            <option value="COD">Cash on Delivery</option>
            <option value="PayPal">PayPal</option>
            <option value="GCash">GCash</option>
        </select>

        <button type="submit" name="confirm_payment" class="confirm-payment-button">Confirm Payment</button>
        <br>
        <button type="button" class="cancel-payment-button" onclick="window.location.href='checkout.php';">Return</button>
    </form>
</section>

</body>
</html>
