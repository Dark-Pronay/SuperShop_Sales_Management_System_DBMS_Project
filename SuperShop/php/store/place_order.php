<?php
session_start();
include '../db.php';
if ($_SESSION['user_type'] !== 'store') {
    header("Location: ../../index.php");
    exit;
}

$store_id = $_SESSION['user_id'];

// Place order logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $distributor_id = $_POST['distributor_id'];
    $stmt = $conn->prepare("INSERT INTO Store_order (O_date, Shipment_stat, Payment_stat, So_S_id, So_D_id) VALUES (NOW(), 'Pending', 'Not paid', ?, ?)");
    $stmt->bind_param("ii", $store_id, $distributor_id);
    $stmt->execute();
    $message = "Order placed successfully!";
}

// Fetch store orders related to the store
$store_orders = $conn->query("SELECT * FROM Store_order WHERE So_S_id = $store_id");

// Fetch distributors
$distributors = $conn->query("SELECT * FROM Distributor");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url("store.jpg") no-repeat center center fixed;
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
            margin-bottom: 20px;
            text-align: center;
            color: #34495e;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-size: 16px;
            display: block;
            margin-bottom: 5px;
            color: #34495e;
        }
        .form-group select, .form-group button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .form-group button {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        .form-group button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .table-container {
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #f4f4f4;
        }
        .message {
            color: green;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s, transform 0.2s;
        }
        .back-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Place Order</h1>

        <?php if (isset($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="distributor_id">Select Distributor:</label>
                <select name="distributor_id" id="distributor_id" required>
                    <option value="" disabled selected>Select a Distributor</option>
                    <?php while ($row = $distributors->fetch_assoc()): ?>
                        <option value="<?php echo $row['D_id']; ?>">
                            <?php echo htmlspecialchars($row['D_name']) . " (" . htmlspecialchars($row['D_city']) . ", " . htmlspecialchars($row['D_type']) . ")"; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Place Order</button>
            </div>
        </form>

        <div class="table-container">
            <h2>Store Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Distributor ID</th>
                        <th>Shipment Status</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $store_orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['O_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['O_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['So_D_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['Shipment_stat']); ?></td>
                            <td><?php echo htmlspecialchars($row['Payment_stat']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
