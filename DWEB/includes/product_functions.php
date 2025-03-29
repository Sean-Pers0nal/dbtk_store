<?php
require 'config.php';

function getProducts() {
    global $conn;
    $result = $conn->query("SELECT * FROM products");
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>
