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
    
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $position = $_POST['position'] ?? '';
    $office = $_POST['office'] ?? '';
    $age = $_POST['age'] ?? 0;
    $phone = $_POST['phone'] ?? '';
    
    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($position) || empty($office) || empty($age)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($age < 18 || $age > 100) {
        $error = 'Age must be between 18 and 100.';
    } else {
        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Email already exists. Please use a different email.';
        } else {
            // Create new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $start_date = date('Y-m-d');
            $salary = 0; // Default salary for new registrations
            $status = 'active';
            
            $sql = "INSERT INTO users (name, email, password, position, office, age, start_date, salary, phone, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssissss", $name, $email, $hashed_password, $position, $office, $age, $start_date, $salary, $phone, $status);
            
            if ($stmt->execute()) {
                $success = 'Account created successfully! You can now sign in.';
                // Redirect to login after 2 seconds
                header('refresh:2;url=login.php');
            } else {
                $error = 'Error creating account. Please try again.';
            }
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
    <title>Register - Data Table</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form action="register.php" method="POST">
                <h1>Create Account</h1>
                <span>Sign up to get started</span>
                
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
                    <label for="name">Full Name *</label>
                    <input type="text" name="name" id="name" placeholder="Enter your full name" required class="form-control" value="<?php echo htmlspecialchars($name ?? ''); ?>" />
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required class="form-control" value="<?php echo htmlspecialchars($email ?? ''); ?>" />
                </div>
                
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" name="password" id="password" placeholder="Create a password (min 6 characters)" required class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="position">Position *</label>
                    <input type="text" name="position" id="position" placeholder="Enter your position" required class="form-control" value="<?php echo htmlspecialchars($position ?? ''); ?>" />
                </div>
                
                <div class="form-group">
                    <label for="office">Office *</label>
                    <input type="text" name="office" id="office" placeholder="Enter your office location" required class="form-control" value="<?php echo htmlspecialchars($office ?? ''); ?>" />
                </div>
                
                <div class="form-group">
                    <label for="age">Age *</label>
                    <input type="number" name="age" id="age" placeholder="Enter your age" required class="form-control" min="18" max="100" value="<?php echo htmlspecialchars($age ?? ''); ?>" />
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" placeholder="Enter your phone number" class="form-control" value="<?php echo htmlspecialchars($phone ?? ''); ?>" />
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 16px;">
                    <ion-icon name="person-add-outline"></ion-icon>
                    Create Account
                </button>
                
                <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-light);">
                    <p style="color: var(--text-secondary); margin: 0;">
                        Already have an account? 
                        <a href="login.php" style="color: var(--primary); text-decoration: none; font-weight: 500;">Sign in here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
