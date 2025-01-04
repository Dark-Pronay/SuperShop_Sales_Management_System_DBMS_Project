<?php
session_start();
include '../db.php';

// Ensure the user is a store
if ($_SESSION['user_type'] !== 'store') {
    header("Location: ../../index.php");
    exit;
}

$store_id = $_SESSION['user_id'];

// Handle form submission to add sales
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sales_date = $_POST['sales_date'];
    $sales_revenue = $_POST['sales_revenue'];

    // Insert sales data into the database
    $stmt = $conn->prepare("INSERT INTO Sales (Sales_date, Sales_revenue, Sales_S_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $sales_date, $sales_revenue, $store_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all sales for the current store
$result = $conn->query("SELECT * FROM Sales WHERE Sales_S_id = $store_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sales</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url("store.jpg") no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            color: #34495e;
        }
        input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
            color: #2c3e50;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }
        .back-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Sales</h1>
        <form method="POST" action="">
            <label for="sales_date">Sales Date:</label>
            <input type="date" id="sales_date" name="sales_date" required>

            <label for="sales_revenue">Sales Revenue:</label>
            <input type="number" id="sales_revenue" name="sales_revenue" step="0.01" required>

            <button type="submit">Add Sales</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Sales ID</th>
                    <th>Date</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Sales_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['Sales_date']); ?></td>
                        <td><?php echo number_format($row['Sales_revenue'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
