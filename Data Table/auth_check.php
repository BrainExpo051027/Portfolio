<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Optional: Check if user is still active in database
include('dbcon.php');
$user_id = $_SESSION['user_id'];
$sql = "SELECT status FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0 || $result->fetch_assoc()['status'] !== 'active') {
    // User not found or inactive, destroy session and redirect
    session_destroy();
    header('Location: login.php');
    exit();
}

$stmt->close();
?>
