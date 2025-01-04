<?php
session_start();
include '../db.php';

if ($_SESSION['user_type'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

// Calculate dates for the past 12 months
$current_month = date('Y-m');
$past_months = [];
for ($i = 0; $i < 12; $i++) {
    $past_months[] = date('Y-m', strtotime("-$i month"));
}

// Default to all-time analytics
$selected_month = '';
$selected_date = '';
$query = "SELECT Store.S_id, Store.S_city, Store.S_division, 
                 SUM(Sales.Sales_revenue) AS store_revenue
          FROM Sales
          INNER JOIN Store ON Sales.Sales_S_id = Store.S_id";
$params = [];

// Check if filtering is applied
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_month = $_POST['month'] ?? '';
    $selected_date = $_POST['date'] ?? '';
    
    // Add filters based on user input
    $where_conditions = [];
    if (!empty($selected_month)) {
        $where_conditions[] = "DATE_FORMAT(Sales.Sales_date, '%Y-%m') = ?";
        $params[] = $selected_month;
    }
    if (!empty($selected_date)) {
        $where_conditions[] = "Sales.Sales_date = ?";
        $params[] = $selected_date;
    }
    if (!empty($where_conditions)) {
        $query .= " WHERE " . implode(" AND ", $where_conditions);
    }
}

// Group by store ID to calculate revenue and contributions
$query .= " GROUP BY Store.S_id";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$total_revenue = 0;
$store_contributions = [];

// Calculate total revenue and store contributions
while ($row = $result->fetch_assoc()) {
    $total_revenue += $row['store_revenue'];
    $store_contributions[] = [
        'store_id' => $row['S_id'],
        'store_revenue' => $row['store_revenue'],
    ];
}

// Calculate percentage contribution for each store
foreach ($store_contributions as &$store) {
    $store['percentage'] = ($total_revenue > 0) ? round(($store['store_revenue'] / $total_revenue) * 100, 2) : 0;
}
unset($store); // Unset reference
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profit Analytics</title>
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
        form select, form input, form button {
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
    <h1>Profit Analytics</h1>
    <form method="POST">
        <label for="month">Select Month:
            <select id="month" name="month">
                <option value="">All Time</option>
                <?php foreach ($past_months as $month): ?>
                    <option value="<?php echo $month; ?>" <?php if ($selected_month === $month) echo 'selected'; ?>>
                        <?php echo date('F Y', strtotime($month)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label for="date">Select Date (Optional):
            <input type="date" id="date" name="date" value="<?php echo $selected_date; ?>">
        </label>
        <button type="submit">Filter</button>
    </form>

    <h2>Total Revenue: <?php echo number_format($total_revenue, 2); ?></h2>
    <h3>Store Contributions</h3>
    <table>
        <tr>
            <th>Store ID</th>
            <th>Store Revenue</th>
            <th>Contribution (%)</th>
        </tr>
        <?php foreach ($store_contributions as $store): ?>
        <tr>
            <td><?php echo $store['store_id']; ?></td>
            <td><?php echo number_format($store['store_revenue'], 2); ?></td>
            <td><?php echo $store['percentage']; ?>%</td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>
