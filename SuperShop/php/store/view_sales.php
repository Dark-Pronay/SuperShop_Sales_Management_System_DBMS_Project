<?php
session_start();
include '../db.php';
if ($_SESSION['user_type'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

$division = "";
$store_id = "";
$sales_result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $division = $_POST['division'];
    $store_id = $_POST['store_id'];

    $query = "SELECT Sales.Sales_id, Sales.Sales_date, Sales.Sales_revenue, Store.S_city, Store.S_division, Store.S_id
              FROM Sales
              INNER JOIN Store ON Sales.Sales_S_id = Store.S_id
              WHERE 1=1";

    if (!empty($division)) {
        $query .= " AND Store.S_division = '" . $conn->real_escape_string($division) . "'";
    }

    if (!empty($store_id)) {
        $query .= " AND Store.S_id = " . intval($store_id);
    }

    $sales_result = $conn->query($query);
} else {
    $sales_result = $conn->query(
        "SELECT Sales.Sales_id, Sales.Sales_date, Sales.Sales_revenue, Store.S_city, Store.S_division, Store.S_id
         FROM Sales
         INNER JOIN Store ON Sales.Sales_S_id = Store.S_id"
    );
}

$divisions = $conn->query("SELECT DISTINCT S_division FROM Store");
$stores = $conn->query("SELECT S_id FROM Store");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sales Records</title>
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
    <h1>View Sales Records</h1>
    <form method="POST">
        <label for="division">Select Division:
            <select id="division" name="division">
                <option value="">--Select Division--</option>
                <?php while ($row = $divisions->fetch_assoc()): ?>
                    <option value="<?php echo $row['S_division']; ?>" <?php if ($row['S_division'] === $division) echo 'selected'; ?>>
                        <?php echo $row['S_division']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>
        <label for="store_id">Select Store ID:
            <select id="store_id" name="store_id">
                <option value="">--Select Store--</option>
                <?php while ($row = $stores->fetch_assoc()): ?>
                    <option value="<?php echo $row['S_id']; ?>" <?php if ($row['S_id'] == $store_id) echo 'selected'; ?>>
                        <?php echo $row['S_id']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>
        <button type="submit">Filter</button>
    </form>

    <h2>Sales Records</h2>
    <table>
        <tr>
            <th>Sales ID</th>
            <th>Sales Date</th>
            <th>Sales Revenue</th>
            <th>City</th>
            <th>Division</th>
            <th>Store ID</th>
        </tr>
        <?php while ($row = $sales_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['Sales_id']; ?></td>
            <td><?php echo $row['Sales_date']; ?></td>
            <td><?php echo $row['Sales_revenue']; ?></td>
            <td><?php echo $row['S_city']; ?></td>
            <td><?php echo $row['S_division']; ?></td>
            <td><?php echo $row['S_id']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>
