<?php
session_start();
include '../db.php';
if ($_SESSION['user_type'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = $_POST['city'];
    $division = $_POST['division'];
    $password = $_POST['password'];
    $admin_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO Store (S_city, S_division, S_pass, S_A_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $city, $division, $password, $admin_id);
    $stmt->execute();
}

$result = $conn->query("SELECT * FROM Store");
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
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-container {
            max-width: 400px;
            margin: 20px auto;
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            color: #007bff;
            font-size: 18px;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .form-container label {
            font-size: 14px;
            color: #555;
        }
        .form-container input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            max-width: 200px;
            margin-left: auto;
            margin-right: auto;
        }
        .back-link:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <h1>Manage Stores</h1>

    <div class="form-container">
        <h2>Add Store</h2>
        <form method="POST">
            <label for="city">City</label>
            <input type="text" id="city" name="city" placeholder="Enter City" required>

            <label for="division">Division</label>
            <input type="text" id="division" name="division" placeholder="Enter Division" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>

            <button type="submit">Add Store</button>
        </form>
    </div>

    <table>
        <tr>
            <th>Store ID</th>
            <th>City</th>
            <th>Division</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['S_id']; ?></td>
            <td><?php echo $row['S_city']; ?></td>
            <td><?php echo $row['S_division']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>
