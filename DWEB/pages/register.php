<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
?>

<?php
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (registerUser($username, $email, $password)) {
        echo "Registration successful!";
    } else {
        echo "Registration failed: Email may already be in use.";
    }
}
?>
<form method="POST">
    <input type="text" name="username" required placeholder="Username">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit" name="register">Register</button>
</form>


