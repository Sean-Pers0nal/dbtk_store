<?php
session_start();
if (empty($_SESSION['cart_items'])) {
    echo "No items in your cart.";
    exit();
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Please log in first."]);
    exit();
}

$user_id = $_SESSION['user_id'];


$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['cart_items']) || empty($data['cart_items'])) {
    echo json_encode(["success" => false, "message" => "No items to checkout."]);
    exit();
}
// Check if cart items are received correctly
echo "<pre>";
var_dump($data['cart_items']);
echo "</pre>";
exit(); 

$cart_items = $data['cart_items'];
$total_amount = 0;

//  Ensure order total is correctly calculated before inserting into orders
foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

//  Insert the order with the correct total amount
$order_sql = "INSERT INTO orders (user_id, total_amount, order_date) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("id", $user_id, $total_amount);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

//  Insert order items into order_items table
foreach ($cart_items as $item) {
    $order_item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($order_item_sql);
    $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
    $stmt->execute();
    $stmt->close();
}

//  Clear cart table (if you're storing items in `cart` table)
$clear_cart_sql = "DELETE FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($clear_cart_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

//  Return success response
echo json_encode(["success" => true, "message" => "Order placed successfully!", "order_id" => $order_id]);

$conn->close();
?>
