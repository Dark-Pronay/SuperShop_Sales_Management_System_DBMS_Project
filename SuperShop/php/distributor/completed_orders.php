<?php
session_start();
include '../db.php';
if ($_SESSION['user_type'] !== 'distributor') {
    header("Location: ../../index.php");
    exit;
}

// Fetch completed orders for the distributor
$distributor_id = $_SESSION['user_id'];
$completed_orders = $conn->query("
    SELECT * FROM Store_order 
    WHERE So_D_id = $distributor_id 
    AND Payment_stat = 'Paid' 
    AND Shipment_stat = 'Delivered'
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url("distributor.jpg") no-repeat center center fixed;
            background-size: cover;
            color: #ffffff; /* Adjusted to blend with the background */
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: rgba(0, 0, 0, 0.7); /* Darker background for better contrast */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
            color: #f1c40f; /* Yellow shade to stand out */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6); /* Slight shadow for better readability */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            color: #2c3e50; /* Dark text for contrast */
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: rgba(0, 0, 0, 0.8); /* Dark header for better contrast */
            color: #f1c40f; /* Yellow shade for visibility */
            font-weight: bold;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s, transform 0.2s;
        }
        .back-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .message {
            color: #2ecc71; /* Green shade for positive message */
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Completed Orders</h1>
        <?php if ($completed_orders->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Store ID</th>
                        <th>Shipment Status</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $completed_orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['O_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['O_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['So_S_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['Shipment_stat']); ?></td>
                            <td><?php echo htmlspecialchars($row['Payment_stat']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="message">No completed orders found.</div>
        <?php endif; ?>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
