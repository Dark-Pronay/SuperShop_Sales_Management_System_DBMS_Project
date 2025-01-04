<?php
session_start();
include '../db.php';

// Check if the user is logged in and is a distributor
if ($_SESSION['user_type'] !== 'distributor') {
    header("Location: ../../index.php");
    exit;
}

// Fetch incomplete orders for the distributor
$distributor_id = $_SESSION['user_id'];

// Update Payment or Shipment Status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $action = $_POST['action'];

    if ($action === 'update_payment') {
        $update_query = "UPDATE Store_order SET Payment_stat = 'Paid' WHERE O_id = ? AND So_D_id = ?";
    } elseif ($action === 'update_shipment') {
        $update_query = "UPDATE Store_order SET Shipment_stat = 'Delivered' WHERE O_id = ? AND So_D_id = ?";
    }

    if (isset($update_query)) {
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ii", $order_id, $distributor_id);
        $stmt->execute();
    }
}

// Fetch incomplete orders
$query = "SELECT * FROM Store_order WHERE So_D_id = ? AND (Payment_stat != 'Paid' OR Shipment_stat != 'Delivered')";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $distributor_id);
$stmt->execute();
$incomplete_orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incomplete Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: url("distributor.jpg") no-repeat center center fixed;
            background-size: cover;
            color: #34495e;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
            color: #2c3e50;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            color: #2c3e50;
        }
        table th {
            background-color: #f4f4f4;
            color: #34495e;
            font-weight: bold;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .actions form {
            display: inline;
        }
        .actions button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        .actions .mark-paid {
            background-color: #4caf50;
            color: white;
        }
        .actions .mark-paid:hover {
            background-color: #45a049;
        }
        .actions .mark-delivered {
            background-color: #3498db;
            color: white;
        }
        .actions .mark-delivered:hover {
            background-color: #2980b9;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
        .back-btn:hover {
            background-color: #c0392b;
        }
        .message {
            color: #e74c3c;
            font-size: 16px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Incomplete Orders</h1>
        <?php if ($incomplete_orders->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Store ID</th>
                        <th>Shipment Status</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $incomplete_orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['O_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['O_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['So_S_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['Shipment_stat']); ?></td>
                            <td><?php echo htmlspecialchars($row['Payment_stat']); ?></td>
                            <td class="actions">
                                <?php if ($row['Payment_stat'] !== 'Paid'): ?>
                                    <form method="POST">
                                        <input type="hidden" name="order_id" value="<?php echo $row['O_id']; ?>">
                                        <input type="hidden" name="action" value="update_payment">
                                        <button type="submit" class="mark-paid">Mark as Paid</button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($row['Shipment_stat'] !== 'Delivered'): ?>
                                    <form method="POST">
                                        <input type="hidden" name="order_id" value="<?php echo $row['O_id']; ?>">
                                        <input type="hidden" name="action" value="update_shipment">
                                        <button type="submit" class="mark-delivered">Mark as Delivered</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="message">No incomplete orders found.</div>
        <?php endif; ?>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
