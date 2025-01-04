<?php
session_start();
include '../db.php';
if ($_SESSION['user_type'] !== 'store') {
    header("Location: ../../index.php");
    exit;
}

$store_id = $_SESSION['user_id'];

// Add new stock functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_stock') {
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $quantity = $_POST['quantity'];
    $stmt = $conn->prepare("INSERT INTO Stock (Category, Subcategory, Quantity, St_S_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $category, $subcategory, $quantity, $store_id);
    $stmt->execute();
}

// Update stock quantity functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_stock') {
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $new_quantity = $_POST['new_quantity'];
    $stmt = $conn->prepare("UPDATE Stock SET Quantity = ? WHERE Category = ? AND Subcategory = ? AND St_S_id = ?");
    $stmt->bind_param("issi", $new_quantity, $category, $subcategory, $store_id);
    $stmt->execute();
}

// Fetch all stock related to the store
$result = $conn->query("SELECT * FROM Stock WHERE St_S_id = $store_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Stock</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url("store.jpg") no-repeat center center fixed;
            background-size: cover;
            color: #2c3e50;
        }

        /* Container Styling */
        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* Header Styles */
        h1, h2 {
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
            color: #34495e;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 16px;
            display: block;
            margin-bottom: 5px;
            color: #34495e;
        }

        .form-group input, 
        .form-group button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: all 0.3s ease;
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

        /* Table Styling */
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
        }

        table th {
            background-color: #f4f4f4;
            color: #34495e;
            font-weight: bold;
        }

        /* Back Button Styling */
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
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
        <h1>Manage Stock</h1>

        <h2>Add New Stock</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add_stock">
            <div class="form-group">
                <label>Category:</label>
                <input type="text" name="category" required>
            </div>
            <div class="form-group">
                <label>Subcategory:</label>
                <input type="text" name="subcategory" required>
            </div>
            <div class="form-group">
                <label>Quantity Left:</label>
                <input type="number" name="quantity" required>
            </div>
            <div class="form-group">
                <button type="submit">Add Stock</button>
            </div>
        </form>

        <h2>Current Stock</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Quantity Left</th>
                    <th>Update Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Category']); ?></td>
                    <td><?php echo htmlspecialchars($row['Subcategory']); ?></td>
                    <td><?php echo htmlspecialchars($row['Quantity']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="update_stock">
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($row['Category']); ?>">
                            <input type="hidden" name="subcategory" value="<?php echo htmlspecialchars($row['Subcategory']); ?>">
                            <input type="number" name="new_quantity" placeholder="New Quantity" required>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
