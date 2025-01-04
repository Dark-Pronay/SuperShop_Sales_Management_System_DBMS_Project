<?php
session_start();
include '../db.php';

if ($_SESSION['user_type'] !== 'distributor') {
    header("Location: ../../index.php");
    exit;
}

$distributor_id = $_SESSION['user_id'];

// Fetch distributor details
$stmt = $conn->prepare("SELECT * FROM Distributor WHERE D_id = ?");
$stmt->bind_param("i", $distributor_id);
$stmt->execute();
$result = $stmt->get_result();
$distributor = $result->fetch_assoc();
$stmt->close();
$conn->close();
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
            background: url("distributor.jpg") no-repeat center center fixed;
            background-size: cover;
            color: #ffffff;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background for better blending */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
            color: #f1c40f; /* Blends well with the background */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-size: 16px;
            display: block;
            margin-bottom: 5px;
            color: #f1c40f;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.8);
            color: #2c3e50;
            transition: all 0.3s ease;
        }
        .form-group input:focus {
            border-color: #f1c40f;
            background-color: rgba(255, 255, 255, 1);
        }
        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #f1c40f;
            color: #2c3e50;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        .form-group button:hover {
            background-color: #d4ac0d;
            transform: translateY(-2px);
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
        <h1>Manage Profile</h1>
        <form method="POST" action="update_profile.php">
            <div class="form-group">
                <label for="name">Distributor Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($distributor['D_name']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($distributor['D_city']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="type">Type:</label>
                <input type="text" id="type" name="type" value="<?php echo htmlspecialchars($distributor['D_type']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <button type="submit">Update Password</button>
            </div>
        </form>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
