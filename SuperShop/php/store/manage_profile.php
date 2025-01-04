<?php
session_start();
include '../db.php';
if ($_SESSION['user_type'] !== 'store') {
    header("Location: ../../index.php");
    exit;
}

// Fetch store details
$store_id = $_SESSION['user_id'];
$query = "SELECT * FROM Store WHERE S_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $store_id);
$stmt->execute();
$result = $stmt->get_result();
$store = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url("store.jpg") no-repeat center center fixed;
            background-size: cover;
            color: #f5f5f5;
        }
        .container {
            max-width: 700px;
            margin: 50px auto;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            color: #f5f5f5;
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
            color: #f39c12;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
            padding: 10px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Your Profile</h1>
        <form action="update_profile.php" method="POST">
            <label for="store_name">Store Name:</label>
            <input type="text" id="store_name" name="store_name" value="<?php echo htmlspecialchars($store['S_city']); ?>" readonly>

            <label for="store_city">City:</label>
            <input type="text" id="store_city" name="store_city" value="<?php echo htmlspecialchars($store['S_city']); ?>" readonly>

            <label for="store_division">Division:</label>
            <input type="text" id="store_division" name="store_division" value="<?php echo htmlspecialchars($store['S_division']); ?>" readonly>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password">

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password">

            <button type="submit">Update Profile</button>
        </form>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
