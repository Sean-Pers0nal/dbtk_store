<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function addToCart($productId, $quantity) {
    $_SESSION['cart'][$productId] = $quantity;
}

function removeFromCart($productId) {
    unset($_SESSION['cart'][$productId]);
}

function getCartItems() {
    return $_SESSION['cart'];
}
?>
