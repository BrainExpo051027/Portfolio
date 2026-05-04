<?php
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('dbcon.php');
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        // Check if user exists
        $sql = "SELECT id, name, email, password, position, status FROM users WHERE email = ? AND status = 'active'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_position'] = $user['position'];
                
                header('Location: index.php');
                exit();
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Data Table</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form action="login.php" method="POST">
                <h1>Welcome Back</h1>
                <span>Sign in to your account</span>
                
                <?php if ($error): ?>
                    <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required class="form-control" value="<?php echo htmlspecialchars($email ?? ''); ?>" />
                </div>
                
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required class="form-control" />
                </div>
                
                <div class="form-group" style="display: flex; justify-content: space-between; align-items: center;">
                    <label style="margin: 0;">
                        <input type="checkbox" name="remember" style="margin-right: 8px;">
                        Remember me
                    </label>
                    <a href="#" style="color: var(--primary); text-decoration: none; font-size: 0.875rem;">Forgot password?</a>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 16px;">
                    <ion-icon name="log-in-outline"></ion-icon>
                    Sign In
                </button>
                
                <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-light);">
                    <p style="color: var(--text-secondary); margin: 0;">
                        Don't have an account? 
                        <a href="register.php" style="color: var(--primary); text-decoration: none; font-weight: 500;">Sign up here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
