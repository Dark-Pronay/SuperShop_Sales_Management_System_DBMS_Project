<?php
session_start();
if ($_SESSION['user_type'] !== 'distributor') {
    header("Location: ../../index.php");
    exit;
}

include '../../php/db.php';
$distributorId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT D_name FROM Distributor WHERE D_id = ?");
$stmt->bind_param("i", $distributorId);
$stmt->execute();
$result = $stmt->get_result();
$distributorName = "Distributor";
if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $distributorName = $row['D_name'];
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distributor Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url("distributor.jpg") no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 15px 30px;
        }
        .navbar h1 {
            font-size: 24px;
            margin: 0;
            color: #f1c40f;
        }
        .navbar .user-info {
            font-size: 16px;
        }
        .navbar .user-info span {
            margin-left: 10px;
            font-weight: bold;
        }
        .container {
            text-align: center;
            padding: 50px 20px;
        }
        .welcome {
            background-color: rgba(0, 0, 0, 0.6);
            display: inline-block;
            padding: 20px 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .welcome h2 {
            font-size: 28px;
            margin: 0;
            color: #f39c12;
        }
        .dashboard-options {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .dashboard-options a {
            display: block;
            padding: 15px 25px;
            text-decoration: none;
            background-color: #4CAF50; /* Changed to green to blend with background */
            color: white;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Added shadow for better appearance */
        }
        .dashboard-options a:hover {
            background-color: #45a049; /* Darker green for hover effect */
            transform: translateY(-5px);
        }
        .logout {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background-color: #ff5722; /* Changed to orange for logout button */
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
        }
        .logout:hover {
            background-color: #e64a19; /* Darker orange for hover effect */
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Distributor Dashboard</h1>
        <div class="user-info">
            Distributor - <?php echo htmlspecialchars($distributorName); ?> 
        </div>
    </div>
    <div class="container">
        <div class="welcome">
            <h2>Welcome to your dashboard!</h2>
        </div>
        <div class="dashboard-options">
            <a href="manage_profile.php">Manage Profile</a>
            <a href="incomplete_orders.php">Manage Incompleted Orders</a>
            <a href="completed_orders.php">Completed Orders</a>
        </div>
        <a href="../../logout.php" class="logout">Log Out</a>
    </div>
</body>
</html>
