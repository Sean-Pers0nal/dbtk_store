<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = trim($_POST["username_or_email"]);
    $password = trim($_POST["password"]);

    // Prepare SQL query to check for either username OR email
    $stmt = $conn->prepare("SELECT id, username, email, password, is_admin FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Debugging: Display stored hash and entered password
        error_log("Stored Hash: " . $row["password"]);
        error_log("Entered Password: " . $password);

        // Fix: Ensure password is verified correctly
        if (!password_verify($password, $row["password"])) {
            $_SESSION["login_error"] = "Invalid password!";
            header("Location: ../pages/login.php");
            exit();
        }

        // If password is correct, store session variables
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        $_SESSION["email"] = $row["email"];
        $_SESSION["is_admin"] = $row["is_admin"];

        // Redirect based on admin status
        if ($row["is_admin"] == 1) {
            header("Location: ../pages/dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit();
    } else {
        $_SESSION["login_error"] = "User not found!";
    }

    $stmt->close();
    $conn->close();
    
    header("Location: ../pages/login.php");
    exit();
}
?>
