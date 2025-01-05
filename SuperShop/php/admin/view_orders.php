<?php
session_start();
include '../db.php';

if ($_SESSION['user_type'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

// Prepare the base query
$query = "SELECT Store_order.O_id, Store_order.O_date, Store_order.Shipment_stat, 
                 Store_order.Payment_stat, Store.S_city, Store.S_division, 
                 Distributor.D_name AS Distributor_Name
          FROM Store_order
          INNER JOIN Store ON Store_order.So_S_id = Store.S_id
          INNER JOIN Distributor ON Store_order.So_D_id = Distributor.D_id";

// Default to showing all orders
$filters = [];
$params = [];
$param_types = '';

// Add filters based on user selection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['payment_status'])) {
        $filters[] = "Store_order.Payment_stat = ?";
        $params[] = $_POST['payment_status'];
        $param_types .= 's';
    }
    if (!empty($_POST['shipment_status'])) {
        $filters[] = "Store_order.Shipment_stat = ?";
        $params[] = $_POST['shipment_status'];
        $param_types .= 's';
    }
    if (!empty($filters)) {
        $query .= " WHERE " . implode(" AND ", $filters);
    }
}

// Add ordering for results
$query .= " ORDER BY Store_order.O_date DESC";

// Prepare and execute the statement
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Error preparing query: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Error executing query: " . $stmt->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
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
        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        form label {
            font-size: 14px;
            color: #555;
        }
        form select, form button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        form button:hover {
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
            margin-top: 30px;
            padding: 10px;
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
    <h1>View Orders</h1>
    <form method="POST">
        <label for="payment_status">Filter by Payment Status:</label>
        <select id="payment_status" name="payment_status">
            <option value="">All</option>
            <option value="Paid" <?php echo isset($_POST['payment_status']) && $_POST['payment_status'] === 'Paid' ? 'selected' : ''; ?>>Paid</option>
            <option value="Not paid" <?php echo isset($_POST['payment_status']) && $_POST['payment_status'] === 'Not paid' ? 'selected' : ''; ?>>Not Paid</option>
        </select>

        <label for="shipment_status">Filter by Shipment Status:</label>
        <select id="shipment_status" name="shipment_status">
            <option value="">All</option>
            <option value="Delivered" <?php echo isset($_POST['shipment_status']) && $_POST['shipment_status'] === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
            <option value="Pending" <?php echo isset($_POST['shipment_status']) && $_POST['shipment_status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
        </select>

        <button type="submit">Filter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Store City</th>
                <th>Store Division</th>
                <th>Distributor Name</th>
                <th>Shipment Status</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['O_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['O_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['S_city']); ?></td>
                        <td><?php echo htmlspecialchars($row['S_division']); ?></td>
                        <td><?php echo htmlspecialchars($row['Distributor_Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Shipment_stat']); ?></td>
                        <td><?php echo htmlspecialchars($row['Payment_stat']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>
