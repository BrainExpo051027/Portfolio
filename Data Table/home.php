<?php
session_start();

// If user is logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
} else {
    // If not logged in, redirect to login
    header('Location: login.php');
    exit();
}
?>
