<?php
session_start();
include '../db.php';
if ($_SESSION['user_type'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $city = $_POST['city'];
    $type = $_POST['type'];
    $password = $_POST['password'];
    $admin_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO Distributor (D_name, D_city, D_type, D_pass, D_A_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $city, $type, $password, $admin_id);
    $stmt->execute();
}

$result = $conn->query("SELECT * FROM Distributor");
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
    <h1>Manage Distributors</h1>

    <div class="form-container">
        <h2>Add Distributor</h2>
        <form method="POST">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter Name" required>

            <label for="city">City</label>
            <input type="text" id="city" name="city" placeholder="Enter City" required>

            <label for="type">Type</label>
            <input type="text" id="type" name="type" placeholder="Enter Type" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>

            <button type="submit">Add Distributor</button>
        </form>
    </div>

    <table>
        <tr>
            <th>Distributor ID</th>
            <th>Name</th>
            <th>City</th>
            <th>Type</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['D_id']; ?></td>
            <td><?php echo $row['D_name']; ?></td>
            <td><?php echo $row['D_city']; ?></td>
            <td><?php echo $row['D_type']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>
