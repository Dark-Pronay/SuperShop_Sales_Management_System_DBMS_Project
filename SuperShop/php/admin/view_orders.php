<?php
session_start();
include '../db.php';
if ($_SESSION['user_type'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

$result = $conn->query("SELECT * FROM Store_order WHERE Shipment_stat = 'Pending'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Orders</title>
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
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
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
    <h1>Pending Orders</h1>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Store ID</th>
            <th>Distributor ID</th>
            <th>Payment Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['O_id']; ?></td>
            <td><?php echo $row['O_date']; ?></td>
            <td><?php echo $row['So_S_id']; ?></td>
            <td><?php echo $row['So_D_id']; ?></td>
            <td><?php echo $row['Payment_stat']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>
