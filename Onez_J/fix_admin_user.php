<?php
// Fix admin user login issue
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Fixing Admin User Login</h2>";

try {
    // Connect to database
    $connection = new mysqli('localhost', 'root', '', 'onez');
    
    if ($connection->connect_error) {
        throw new Exception("Connection failed: " . $connection->connect_error);
    }
    
    echo "<p style='color: green;'>✓ Database connection successful!</p>";
    
    // Check if users table exists
    $result = $connection->query("SHOW TABLES LIKE 'users'");
    if (!$result || $result->num_rows === 0) {
        echo "<p style='color: red;'>✗ Users table does not exist!</p>";
        echo "<p>You need to run the installation script first: <a href='install.php'>install.php</a></p>";
        exit;
    }
    
    // Check if admin user exists
    $result = $connection->query("SELECT id, username, role, is_active, password FROM users WHERE username = 'admin'");
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "<p style='color: green;'>✓ Admin user found (ID: {$user['id']})</p>";
        echo "<p><strong>Current password hash:</strong> " . substr($user['password'], 0, 20) . "...</p>";
        
        // Test current password
        $test_password = 'admin123';
        if (password_verify($test_password, $user['password'])) {
            echo "<p style='color: green;'>✓ Password 'admin123' is already correct!</p>";
        } else {
            echo "<p style='color: orange;'>⚠ Password 'admin123' is incorrect. Resetting password...</p>";
            
            // Reset admin password
            $new_password = 'admin123';
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $stmt = $connection->prepare("UPDATE users SET password = ?, is_active = 1 WHERE username = 'admin'");
            $stmt->bind_param("s", $hashed_password);
            
            if ($stmt->execute()) {
                echo "<p style='color: green;'>✓ Admin password reset successfully!</p>";
                echo "<p><strong>New password hash:</strong> " . substr($hashed_password, 0, 20) . "...</p>";
                echo "<p><strong>New Login Credentials:</strong></p>";
                echo "<ul>";
                echo "<li><strong>Username:</strong> admin</li>";
                echo "<li><strong>Password:</strong> admin123</li>";
                echo "</ul>";
                
                // Verify the new password works
                if (password_verify('admin123', $hashed_password)) {
                    echo "<p style='color: green;'>✓ Password verification test passed!</p>";
                } else {
                    echo "<p style='color: red;'>✗ Password verification test failed!</p>";
                }
                
            } else {
                echo "<p style='color: red;'>✗ Failed to reset password: " . $stmt->error . "</p>";
            }
        }
        
    } else {
        echo "<p style='color: orange;'>⚠ Admin user not found. Creating new admin user...</p>";
        
        // Create admin user
        $username = 'admin';
        $password = 'admin123';
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $full_name = 'System Administrator';
        $email = 'admin@registrar.edu';
        $role = 'admin';
        
        $stmt = $connection->prepare("INSERT INTO users (username, password, full_name, email, role, is_active) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("sssss", $username, $hashed_password, $full_name, $email, $role);
        
        if ($stmt->execute()) {
            echo "<p style='color: green;'>✓ Admin user created successfully!</p>";
            echo "<p><strong>Login Credentials:</strong></p>";
            echo "<ul>";
            echo "<li><strong>Username:</strong> admin</li>";
            echo "<li><strong>Password:</strong> admin123</li>";
            echo "</ul>";
        } else {
            echo "<p style='color: red;'>✗ Failed to create admin user: " . $stmt->error . "</p>";
        }
    }
    
    // Check if services exist
    $result = $connection->query("SELECT COUNT(*) as count FROM services");
    if ($result) {
        $count = $result->fetch_assoc()['count'];
        if ($count > 0) {
            echo "<p style='color: green;'>✓ Found $count services</p>";
        } else {
            echo "<p style='color: orange;'>⚠ No services found. You may need to run the full installation.</p>";
        }
    }
    
    $connection->close();
    
    echo "<hr>";
    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Try logging in with the credentials above</li>";
    echo "<li>Go to admin login: <a href='admin/login.php'>admin/login.php</a></li>";
    echo "<li>If it still doesn't work, there might be a different issue</li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    
    echo "<h3>Possible Solutions:</h3>";
    echo "<ol>";
    echo "<li>Make sure XAMPP MySQL is running</li>";
    echo "<li>Check if database 'onez' exists</li>";
    echo "<li>Run the installation script: <a href='install.php'>install.php</a></li>";
    echo "</ol>";
}
?>
