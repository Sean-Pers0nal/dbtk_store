<?php
session_start();
require '../includes/config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ✅ FIX: Select `username` along with other fields
    $stmt = $conn->prepare("SELECT id, username, password, is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['is_admin'] = $user['is_admin']; 
            $_SESSION['username'] = $user['username']; // ✅ Store username

            // ✅ FIX: Redirect admin users to the correct path
            if ($user['is_admin'] == 1) {
                header("Location: /DWEB/admin/admin.php"); 
            } else {
                header("Location: /DWEB/pages/shop.php");
            }

            // ✅ FIX: Clear login error on success
            unset($_SESSION["login_error"]);
            exit();
        } else {
            $_SESSION["login_error"] = "Invalid password.";
        }
    } else {
        $_SESSION["login_error"] = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DBTK</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    .login-container {
        background: #fff;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        text-align: center;
        width: 400px;
    }
    .login-container h2 {
        margin-bottom: 20px;
        color: #333;
        font-size: 24px;
    }
    .login-container input {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }
    .login-container button {
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
    .login-container button:hover {
        background: #0056b3;
    }
    .login-options {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-top: 10px;
    }
    .login-options a {
        color: #007bff;
        text-decoration: none;
    }
    .login-options a:hover {
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
    .noacc {
        color: black;
    }
</style>
<body>

    <?php include '../includes/navbar.php'; ?>

    <a href="javascript:history.back()" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>

    <div class="login-container">
        <h2>Login to your account</h2>

        <!-- Display login errors -->
        <?php if (isset($_SESSION["login_error"])): ?>
            <p style="color: red;"><?php echo $_SESSION["login_error"]; unset($_SESSION["login_error"]); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <p class="noacc">No account? <a href="/DWEB/pages/signup.php">Sign up</a></p>
    </div>

</body>
</html>
