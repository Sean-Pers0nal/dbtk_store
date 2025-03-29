<?php
require '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); 

    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.location.href='signup.php';</script>";
        exit();
    }
    $stmt->close();

    // Insert new user
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);
    
    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: Could not register.'); window.location.href='signup.php';</script>";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - DBTK</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<style>
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        background-color: #121212;
        font-family: Arial, sans-serif;
        margin: 0;
    }
    .signup-container {
        background: #fff;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        text-align: center;
        width: 400px;
    }
    .signup-container h2 {
        margin-bottom: 20px;
        color: #333;
        font-size: 24px;
    }
    .signup-container input {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }
    .signup-container button {
        width: 100%;
        padding: 12px;
        background: #007bff;
        border: none;
        color: white;
        font-size: 18px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }
    .signup-container button:hover {
        background: #0056b3;
    }
    .signup-options {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-top: 10px;
    }
    .signup-options a {
        color: #007bff;
        text-decoration: none;
    }
    .signup-options a:hover {
        text-decoration: underline;
    }
    .back-btn {
        background: #444;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 15px;
        font-size: 16px;
    }
    .back-btn:hover {
        background: #333;
    }
    .haveacc{
        color: black;
    }
</style>
<body>

    <a href="javascript:history.back()" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>

    <div class="signup-container">
        <h2>Create an Account</h2>

        <form method="POST" action="signup.php">
            <input type="text" name="username" required placeholder="Username">
            <input type="email" name="email" required placeholder="Email">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit">Register</button>
        </form>

        <p class="haveacc">Already have an account? <a href="login.php">Login here</a></p>
    </div>

</body>
</html>
