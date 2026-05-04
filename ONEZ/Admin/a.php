<?php
include "connection.php"; // assumes your $conn is defined

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM student WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo "<script>alert('The username and password do not match.');</script>";
    } else {
        $row = $result->fetch_assoc();
        
        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            // Start session and store user info
            session_start();
            $_SESSION['login_user'] = $row['username'];
            $_SESSION['pic'] = $row['pic']; // if needed

            echo "<script>alert('Login successful.');</script>";
            // header("Location: dashboard.php"); // uncomment to redirect
        } else {
            echo "<script>alert('The username and password do not match.');</script>";
        }
    }

    $stmt->close();
}
?>
