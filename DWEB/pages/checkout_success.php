<?php
session_start();

// Clear cart after successful checkout
include '../includes/config.php';
$user_id = $_SESSION['user_id'];
$conn->query("DELETE FROM cart WHERE user_id = $user_id");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Successful</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script>
        setTimeout(function() {
            alert("Thank you for your purchase!");
            window.location.href = "shop.php"; 
        }, 2000); // Show message, then redirect after 2 seconds
    </script>
</head>
<body>
    <h2>Thank you for your order!</h2>
    <p>Redirecting to the shop...</p>
</body>
</html>
