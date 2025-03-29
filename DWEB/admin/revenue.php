<?php
session_start();
require '../includes/config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["is_admin"] != 1) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: white;
            text-align: center;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }

        .revenue-container {
            background: rgba(30, 30, 30, 0.9);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 188, 212, 0.3);
            display: inline-block;
        }

        h2 {
            font-size: 26px;
            color: #00bcd4;
            text-shadow: 0px 0px 10px rgba(0, 188, 212, 0.8);
            margin-bottom: 15px;
        }

        .amount {
            font-size: 42px;
            font-weight: bold;
            color: #00ffcc;
            margin-top: 10px;
            text-shadow: 0px 0px 15px rgba(0, 255, 204, 0.8);
        }

        .back-btn {
            display: inline-block;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: #00bcd4;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #0097a7;
        }
    </style>
    <script>
        function fetchRevenue() {
            fetch("fetch_revenue.php")
                .then(response => response.json())
                .then(data => {
                    if (data.total_revenue) {
                        document.getElementById("revenueAmount").innerText = "₱" + data.total_revenue;
                    }
                })
                .catch(error => console.error("Error fetching revenue:", error));
        }

        setInterval(fetchRevenue, 5000); // Refresh revenue every 5 seconds
    </script>
</head>
<body>

    <h2>Total Revenue</h2>
    <div class="revenue-container">
        <div id="revenueAmount" class="amount">₱0.00</div>
    </div>

    <!-- Back Button -->
    <a href="admin.php" class="back-btn">← Back to Admin Panel</a>

</body>
</html>
