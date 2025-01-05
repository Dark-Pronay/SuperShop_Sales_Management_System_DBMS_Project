<?php
session_start();
include '../db.php';

if ($_SESSION['user_type'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

// Fetch distributors and their corresponding admin names
$query = "SELECT Distributor.D_id, Distributor.D_name, Distributor.D_city, Distributor.D_type, Admin.A_name AS added_by 
          FROM Distributor 
          INNER JOIN Admin ON Distributor.D_A_id = Admin.A_id";
$result = $conn->query($query);

// Handle form submission for adding a new distributor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_distributor'])) {
    $d_name = $_POST['d_name'];
    $d_pass = $_POST['d_pass'];
    $d_city = $_POST['d_city'];
    $d_type = $_POST['d_type'];
    $admin_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO Distributor (D_name, D_pass, D_city, D_type, D_A_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $d_name, $d_pass, $d_city, $d_type, $admin_id);
    $stmt->execute();
    header("Location: manage_distributors.php"); // Refresh the page after adding
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Distributors</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: url("../admin.jpg") no-repeat center center fixed;
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
        <h1>Manage Distributors</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>City</th>
                    <th>Type</th>
                    <th>Added By</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['D_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['D_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['D_city']); ?></td>
                        <td><?php echo htmlspecialchars($row['D_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['added_by']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <form method="POST">
            <label for="d_name">Distributor Name:</label>
            <input type="text" id="d_name" name="d_name" required>

            <label for="d_pass">Password:</label>
            <input type="password" id="d_pass" name="d_pass" required>

            <label for="d_city">City:</label>
            <input type="text" id="d_city" name="d_city" required>

            <label for="d_type">Type:</label>
            <input type="text" id="d_type" name="d_type" required>

            <button type="submit" name="add_distributor">Add Distributor</button>
        </form>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
