<?php
session_start();
require '../includes/config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - DBTK</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <main>
        <div class="shop-container">
            <h1>Shop Our Collection</h1>
        </div>

        <div class="product-container">
            <?php
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product-frame">';
                    echo '<img src="../uploads/' . htmlspecialchars($row["image"]) . '" alt="' . htmlspecialchars($row["name"]) . '">';
                    echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
                    echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                    echo '<p>₱' . number_format($row["price"], 2) . '</p>';
                    
                    // Add to Cart Form
                    echo '<form action="cart.php" method="POST">';
                    echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($row["id"]) . '">';
                    echo '<input type="hidden" name="name" value="' . htmlspecialchars($row["name"]) . '">';
                    echo '<input type="hidden" name="price" value="' . htmlspecialchars($row["price"]) . '">';
                    echo '<input type="hidden" name="image" value="../uploads/' . htmlspecialchars($row["image"]) . '">';
                    echo '<input type="number" name="quantity" value="1" min="1" class="quantity-input">';
                    echo '<button type="submit" name="add_to_cart" class="btn-add-to-cart">Add to Cart</button>';
                    echo '</form>';

                    echo '</div>';
                }
            } else {
                echo "<p>No products available.</p>";
            }
            ?>
        </div>
    </main>
    <script>
        function updateCartCount() {
            fetch('/DWEB/includes/cart_count.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('cart-count').innerText = data;
                });
        }

        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', () => {
                setTimeout(updateCartCount, 500); 
            });
        });
    </script>


    <footer class="footer">
        &copy; 2025 DBTK Store. All Rights Reserved.
    </footer>

</body>
</html>
