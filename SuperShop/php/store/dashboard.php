<?php
session_start();
if ($_SESSION['user_type'] !== 'store') {
    header("Location: ../../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url("store.jpg") no-repeat center center fixed;
            background-size: cover;
            color: #f5f5f5;
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
            color: #f39c12;
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
            color: #f5f5f5;
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
            background-color: #007bff;
            color: #fff;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
        }
        .dashboard-options a:hover {
            background-color: #0056b3;
            transform: translateY(-5px);
        }
        .logout {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
        }
        .logout:hover {
            background-color: #c0392b;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Store Dashboard</h1>
        <div class="user-info">
            Store - <?php echo htmlspecialchars($_SESSION['user_id']); ?> 
        </div>
    </div>
    <div class="container">
        <div class="welcome">
            <h2>Welcome to your dashboard!</h2>
        </div>
        <div class="dashboard-options">
            <a href="manage_profile.php">Manage Profile</a>
            <a href="manage_stock.php">Manage Stocks</a>
            <a href="place_order.php">Store Orders</a>
            <a href="manage_sales.php">Manage Sales</a>
        </div>
        <a href="../../logout.php" class="logout">Log Out</a>
    </div>
</body>
</html>
