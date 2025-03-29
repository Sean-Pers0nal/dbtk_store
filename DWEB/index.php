<?php
session_start();
require 'includes/config.php';

// Check if the user just logged in
$showDashboard = isset($_SESSION["show_dashboard"]) ? true : false;
unset($_SESSION["show_dashboard"]); // Remove session variable after displaying the dashboard
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DBTK - Home</title>

    <!-- Load FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <?php include 'includes/navbar.php'; ?>
    <?php if (isset($_SESSION["user_id"]) && $_SESSION["is_admin"] == 1): ?>
<?php endif; ?>

    <main>
        <!-- Hero Section -->
        <section class="hero fade-in">
            <div class="hero-image">
                <img src="img/header.jpg" alt="DBTK Header">
            </div>
        </section>

        <!-- Discover Section -->
        <section class="discover-section fade-in">
            <h1>Discover Streetwear Like Never Before</h1>
            <p>Quality. Style. DBTK.</p>
            <a href="pages/shop.php" class="btn-shop">Shop Now</a>
        </section>

        <!-- Featured Products -->
        <section class="featured-products fade-in">
            <h2>Featured Products</h2>
            <div class="product-container">
                <div class="product-frame fade-in">
                    <img src="img/dbtkhome1.jpg" alt="Product 1">
                    <h3>DBTK Vs MondaySucks</h3>
                    <p>₱800.00</p>
                    <a href="pages/shop.php" class="btn-shop">Shop Now</a>
                </div>
                <div class="product-frame fade-in">
                    <img src="uploads/DBTK x Hotwheels racing pants.jpg" alt="Product 2">
                    <h3>DBTK x Hotwheels racing pants</h3>
                    <p>₱1,500.00</p>
                    <a href="pages/shop.php" class="btn-shop">Shop Now</a>
                </div>
                <div class="product-frame fade-in">
                    <img src="uploads/DBTK OG Multi-pocket essential crossbody bag.jpg" alt="Product 3">
                    <h3>DBTK OG Multi-pocket</h3>
                    <p>₱2,000.00</p>
                    <a href="pages/shop.php" class="btn-shop">Shop Now</a>
                </div>
            </div>
            <!-- Floating Admin Panel -->
            <div id="adminDashboard" class="dashboard-container" style="display:none; position:fixed; top:10%; left:50%; transform:translate(-50%, 0); background:white; padding:20px; box-shadow: 0 0 10px rgba(0,0,0,0.3);">
                <h2>Admin Dashboard</h2>
                <p>Total Users: <?php echo $total_users; ?></p>
                <p>Total Orders: <?php echo $total_orders; ?></p>
                <p>Total Revenue: ₱<?php echo number_format($total_revenue, 2); ?></p>
                <p>Total Products: <?php echo $total_products; ?></p>
                <button onclick="toggleDashboard()">Close</button>
            </div>
        </section>
    </main>

    <footer class="footer">
        &copy; 2025 DBTK Store. All Rights Reserved.
    </footer>

    <!-- Floating Dashboard Window -->
    <div id="dashboard" class="dashboard" style="display: none;">
        <div class="dashboard-header">
            <span>Welcome!</span>
            <button id="closeDashboard">&times;</button>
        </div>
        <div class="dashboard-content">
            <p>Welcome, <?php echo isset($_SESSION["user"]) ? $_SESSION["user"] : "Guest"; ?>!</p>
        </div>
    </div>

    <script>
                function toggleDashboard() {
            var dashboard = document.getElementById("adminDashboard");
            if (dashboard.style.display === "none") {
                dashboard.style.display = "block";
            } else {
                dashboard.style.display = "none";
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
            let dashboard = document.getElementById("dashboard");
            let closeButton = document.getElementById("closeDashboard");

            <?php if ($showDashboard): ?>
                console.log("Dashboard should appear");
                dashboard.style.display = "block";

                setTimeout(() => {
                    dashboard.style.display = "none";
                    console.log("Dashboard disappearing...");
                }, 3000);
            <?php endif; ?>

            closeButton.addEventListener("click", function() {
                dashboard.style.display = "none";
            });
        });

        $(document).ready(function() {
            function fadeInOnScroll() {
                $(".fade-in").each(function() {
                    var position = $(this).offset().top;
                    var windowBottom = $(window).scrollTop() + $(window).height();

                    if (windowBottom > position + 50) {
                        $(this).addClass("visible");
                    }
                });
            }

            fadeInOnScroll();
            $(window).scroll(function() {
                fadeInOnScroll();
            });
        });

        let navbar = document.querySelector(".navbar");
        window.addEventListener("scroll", function() {
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });
    </script>

    <style>
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .dashboard {
        position: fixed;
        color:black;
        top: 20%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 255, 255, 0.9); 
        padding: 30px;
        width: 300px; 
        border-radius: 15px;
        text-align: center;
        z-index: 1000;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        animation: fadeInScale 0.5s ease-in-out;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 18px;
        font-weight: bold;
    }

    .dashboard-header button {
        background: none;
        border: none;
        font-size: 22px;
        cursor: pointer;
        transition: color 0.3s ease-in-out;
    }

    .dashboard-header button:hover {
        color: red;
    }

    .dashboard-content {
        margin-top: 10px;
        font-size: 16px;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.8);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }
    </style>

</body>
</html>
