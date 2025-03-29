

<nav class="navbar">
    <!-- Logo -->
    <div class="logo">
        <a href="/DWEB/index.php">
            <img src="/DWEB/img/DBTK_logo.jpg" alt="DBTK Logo">
        </a>
    </div>

    <!-- Navigation Links -->
    <ul class="nav-links">
        <li><a href="/DWEB/index.php">Home</a></li>
        <li><a href="/DWEB/pages/shop.php">Shop</a></li>
        <li><a href="/DWEB/pages/about.php">About</a></li>
        <li><a href="/DWEB/pages/contact.php">Contact</a></li>

        <!-- Show Admin Panel Link for Admins -->
        <?php if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1): ?>
            <li><a href="/DWEB/admin/admin.php">Admin Panel</a></li>
        <?php endif; ?>

    </ul>

    <!-- Cart & Login Icons -->
    <div class="nav-icons">
        <a href="/DWEB/pages/cart.php"><i class="fas fa-shopping-cart"></i></a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/DWEB/pages/logout.php"><i class="fas fa-sign-out-alt"></i></a>
        <?php else: ?>
            <a href="/DWEB/pages/login.php"><i class="fas fa-user"></i></a>
        <?php endif; ?>
    </div>
</nav>
