<?php
session_start();
require '../includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - DBTK</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <main class="contact-section fade-in">
        <h1>Contact Us</h1>
        <p>Reach out to us for inquiries and collaborations.</p>

        <!-- Contact Form -->
        <form action="contact_process.php" method="POST" class="contact-form">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn-submit">Send Message</button>
        </form>

        <!-- Location Image -->
        <div class="location-image">
            <img src="../img/contact.png" alt="DBTK Location" class="contact-location">
        </div>

        <!-- Google Map -->
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4607.987439192104!2d120.57785737578058!3d15.166388962973034!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396f3003f071119%3A0x7a52b229a5870683!2sDBTK!5e1!3m2!1sen!2sph!4v1742750356355!5m2!1sen!2sph"
                width="350" height="350" style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </main>

    <footer class="footer">
        &copy; 2025 DBTK Store. All Rights Reserved.
    </footer>

</body>
</html>
