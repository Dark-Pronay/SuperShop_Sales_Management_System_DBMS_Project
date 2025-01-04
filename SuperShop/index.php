<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/scripts.js" defer></script>
    <title>Superstore Sales Management</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url("home.jpg") no-repeat center center fixed;
            background-size: cover;
            color: #ffffff; /* Text color for blending */
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent background for better readability */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            margin-top: 10%;
            transform: translateX(-10%); /* Move the form slightly to the left */
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #f39c12; /* Changed the color to orange for emphasis */
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); /* Added a shadow for better visibility */
        }
        .login-sections button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .login-sections button:hover {
            background-color: #0056b3;
        }
        #login-form {
            margin-top: 20px;
        }
        #login-form label {
            display: block;
            margin-bottom: 5px;
            color: #f39c12; /* Set color for better visibility */
        }
        #login-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
        }
        #login-form button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #login-form button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome to Supershop Sales Management System</h1>
    <div class="login-sections">
        <button onclick="showLogin('admin')">Admin Login</button>
        <button onclick="showLogin('store')">Store Login</button>
        <button onclick="showLogin('distributor')">Distributor Login</button>
    </div>
    <div id="login-form" style="display: none;">
        <form action="php/login.php" method="POST">
            <input type="hidden" id="user-type" name="user_type">

            <label id="user-id-label" for="user_id">User ID:</label>
            <input type="text" id="user_id" name="user_id" placeholder="Enter User ID" required>

            <label id="password-label" for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</div>

<script>
    function showLogin(userType) {
        const userTypeInput = document.getElementById('user-type');
        const userIdLabel = document.getElementById('user-id-label');
        const passwordLabel = document.getElementById('password-label');
        const userIdInput = document.getElementById('user_id');
        const loginForm = document.getElementById('login-form');

        // Update the hidden input field for user type
        userTypeInput.value = userType;

        // Update the label and placeholder based on user type
        if (userType === "admin") {
            userIdLabel.innerText = "Admin ID:";
            userIdInput.placeholder = "Enter Admin ID";
            userIdLabel.style.color = "#f39c12"; // Admin color
            passwordLabel.style.color = "#f39c12"; // Admin color
        } else if (userType === "store") {
            userIdLabel.innerText = "Store ID:";
            userIdInput.placeholder = "Enter Store ID";
            userIdLabel.style.color = "#3498db"; // Store color
            passwordLabel.style.color = "#3498db"; // Store color
        } else if (userType === "distributor") {
            userIdLabel.innerText = "Distributor ID:";
            userIdInput.placeholder = "Enter Distributor ID";
            userIdLabel.style.color = "#2ecc71"; // Distributor color
            passwordLabel.style.color = "#2ecc71"; // Distributor color
        }

        // Show the login form
        loginForm.style.display = 'block';
    }
</script>
</body>
</html>
