<?php
session_start();
include '../db.php';

if ($_SESSION['user_type'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

// Fetch stores and their corresponding admin names
$query = "SELECT Store.S_id, Store.S_city, Store.S_division, Admin.A_name AS added_by 
          FROM Store 
          INNER JOIN Admin ON Store.S_A_id = Admin.A_id";
$result = $conn->query($query);

// Handle form submission for adding a new store
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_store'])) {
    $s_city = $_POST['s_city'];
    $s_division = $_POST['s_division'];
    $s_pass = $_POST['s_pass'];
    $admin_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO Store (S_city, S_division, S_pass, S_A_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $s_city, $s_division, $s_pass, $admin_id);
    $stmt->execute();
    header("Location: manage_stores.php"); // Refresh the page after adding
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Stores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: url("admin.jpg") no-repeat center center fixed;
            background-size: cover;
            color: #2c3e50;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }
        h1 {
            font-size: 28px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #3498db;
            color: white;
        }
        form {
            margin-top: 20px;
        }
        form label {
            display: block;
            margin-bottom: 5px;
        }
        form input, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            background-color: #2ecc71;
            color: white;
            cursor: pointer;
        }
        form button:hover {
            background-color: #27ae60;
        }
        .back-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Stores</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>City</th>
                    <th>Division</th>
                    <th>Added By</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['S_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['S_city']); ?></td>
                        <td><?php echo htmlspecialchars($row['S_division']); ?></td>
                        <td><?php echo htmlspecialchars($row['added_by']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <form method="POST">
            <label for="s_city">Store City:</label>
            <input type="text" id="s_city" name="s_city" required>

            <label for="s_division">Division:</label>
            <input type="text" id="s_division" name="s_division" required>

            <label for="s_pass">Password:</label>
            <input type="password" id="s_pass" name="s_pass" required>

            <button type="submit" name="add_store">Add Store</button>
        </form>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
