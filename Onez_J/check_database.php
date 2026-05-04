<?php
// Check database setup and admin user
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database and User Check</h2>";

try {
    // Test basic connection
    echo "<p>Testing database connection...</p>";
    $connection = new mysqli('localhost', 'root', '', 'onezdb');
    
    if ($connection->connect_error) {
        throw new Exception("Connection failed: " . $connection->connect_error);
    }
    
    echo "<p style='color: green;'>✓ Database connection successful!</p>";
    
    // Check if tables exist
    echo "<p>Checking if tables exist...</p>";
    $tables = ['users', 'services', 'queue_tickets'];
    
    foreach ($tables as $table) {
        $result = $connection->query("SHOW TABLES LIKE '$table'");
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✓ Table '$table' exists</p>";
        } else {
            echo "<p style='color: red;'>✗ Table '$table' does not exist</p>";
        }
    }
    
    // Check if admin user exists
    echo "<p>Checking admin user...</p>";
    $result = $connection->query("SELECT id, username, role, is_active FROM users WHERE username = 'admin'");
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "<p style='color: green;'>✓ Admin user found:</p>";
        echo "<ul>";
        echo "<li><strong>ID:</strong> " . $user['id'] . "</li>";
        echo "<li><strong>Username:</strong> " . $user['username'] . "</li>";
        echo "<li><strong>Role:</strong> " . $user['role'] . "</li>";
        echo "<li><strong>Active:</strong> " . ($user['is_active'] ? 'Yes' : 'No') . "</li>";
        echo "</ul>";
        
        // Test password verification
        echo "<p>Testing password verification...</p>";
        $test_password = 'admin123';
        $result = $connection->query("SELECT password FROM users WHERE username = 'admin'");
        $user = $result->fetch_assoc();
        
        if (password_verify($test_password, $user['password'])) {
            echo "<p style='color: green;'>✓ Password 'admin123' is correct!</p>";
        } else {
            echo "<p style='color: red;'>✗ Password 'admin123' is incorrect</p>";
            echo "<p>Current password hash: " . substr($user['password'], 0, 20) . "...</p>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ Admin user not found!</p>";
        echo "<p>You need to run the installation script.</p>";
    }
    
    // Check services
    echo "<p>Checking services...</p>";
    $result = $connection->query("SELECT COUNT(*) as count FROM services");
    if ($result) {
        $count = $result->fetch_assoc()['count'];
        echo "<p style='color: green;'>✓ Found $count services</p>";
    } else {
        echo "<p style='color: red;'>✗ No services found</p>";
    }
    
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    
    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Make sure XAMPP MySQL is running</li>";
    echo "<li>Run the installation script: <a href='install.php'>install.php</a></li>";
    echo "<li>Or manually create the database and tables</li>";
    echo "</ol>";
}
?>
