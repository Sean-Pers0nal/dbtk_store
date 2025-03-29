<?php
$host = "localhost";
$username = "root";  // Change if needed
$password = "";  // Change if needed
$database = "dbtk_store";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
