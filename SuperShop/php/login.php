<?php
session_start();
include 'db.php';

// Get user inputs
$user_id = $_POST['user_id'];
$password = $_POST['password'];
$user_type = $_POST['user_type'];

// Check login logic based on user type
if ($user_type === 'admin') {
    $stmt = $conn->prepare("SELECT * FROM Admin WHERE A_id = ? AND A_pass = ?");
    $stmt->bind_param("is", $user_id, $password);
} elseif ($user_type === 'store') {
    $stmt = $conn->prepare("SELECT * FROM Store WHERE S_id = ? AND S_pass = ?");
    $stmt->bind_param("is", $user_id, $password);
} elseif ($user_type === 'distributor') {
    $stmt = $conn->prepare("SELECT * FROM Distributor WHERE D_id = ? AND D_pass = ?");
    $stmt->bind_param("is", $user_id, $password);
} else {
    header("Location: ../index.php");
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['user_type'] = $user_type;
    $_SESSION['user_id'] = $user_id;

    // Redirect based on user type
    if ($user_type === 'admin') {
        header("Location: admin/dashboard.php");
    } elseif ($user_type === 'store') {
        header("Location: store/dashboard.php");
    } elseif ($user_type === 'distributor') {
        header("Location: distributor/dashboard.php");
    }
} else {
    header("Location: ../index.php?error=invalid_credentials");
}
?>
//this is a sample 
