<?php
session_start();
require '../includes/config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["is_admin"] != 1) {
    header("Location: ../index.php");
    exit();
}

// Ensure status column exists
$conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS status ENUM('Active', 'Blocked') DEFAULT 'Active'");

// Fetch all users
$query = "SELECT id, username, email, status FROM users";
$result = $conn->query($query);

if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_status = $_POST['status'];

    $update_query = "UPDATE users SET username = ?, email = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssi", $new_username, $new_email, $new_status, $user_id);
    $stmt->execute();
    header("Location: users.php");
    exit();
}

if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: white;
            text-align: center;
        }

        .table-container {
            width: 90%;
            max-width: 800px;
            background: rgba(30, 30, 30, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 188, 212, 0.3);
            overflow-x: auto;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #333;
            color: white;
        }

        th {
            background-color: #1e1e1e;
            color: #00bcd4;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: rgba(50, 50, 50, 0.5);
        }

        .edit-btn, .delete-btn {
            padding: 8px 15px;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }

        .edit-btn { background: #00bcd4; }
        .delete-btn { background: red; }

        .edit-btn:hover { background: #0097a7; }
        .delete-btn:hover { background: darkred; }

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

        .edit-form {
            display: none;
            background: #222;
            padding: 20px;
            border-radius: 10px;
            margin: auto;
            width: 80%;
            max-width: 400px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
        }

        .save-btn {
            background: #00bcd4;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .save-btn:hover {
            background: #0097a7;
        }

    </style>
    <script>
        function showEditForm(userId, username, email, status) {
            document.getElementById('edit-form').style.display = 'block';
            document.getElementById('user_id').value = userId;
            document.getElementById('username').value = username;
            document.getElementById('email').value = email;
            document.getElementById('status').value = status;
        }
    </script>
</head>
<body>

    <h2>Users List</h2>
    <div class="table-container">
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <button class="edit-btn" onclick="showEditForm('<?= $row['id'] ?>', '<?= htmlspecialchars($row['username']) ?>', '<?= htmlspecialchars($row['email']) ?>', '<?= $row['status'] ?>')">Edit</button>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="delete_user" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Edit Form -->
    <div id="edit-form" class="edit-form">
        <h3>Edit User</h3>
        <form method="post">
            <input type="hidden" name="user_id" id="user_id">
            <label>Username:</label>
            <input type="text" name="username" id="username" required>
            <label>Email:</label>
            <input type="email" name="email" id="email" required>
            <label>Status:</label>
            <select name="status" id="status">
                <option value="Active">Active</option>
                <option value="Blocked">Blocked</option>
            </select>
            <button type="submit" name="update_user" class="save-btn">Save Changes</button>
        </form>
    </div>

    <a href="admin.php" class="back-btn">← Back to Admin Panel</a>

</body>
</html>
