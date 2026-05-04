<?php
/**
 * Database Setup Script
 * Run this file to initialize the database with the new schema
 */

// Database configuration
$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'blk7';

echo "<h2>Database Setup Script</h2>";
echo "<p>Setting up database for Data Table project...</p>";

try {
    // Connect to MySQL server
    $conn = new mysqli($server, $username, $password);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "<p>✓ Connected to MySQL server</p>";
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
        echo "<p>✓ Database '$dbname' created or already exists</p>";
    } else {
        echo "<p>✗ Error creating database: " . $conn->error . "</p>";
    }
    
    // Select the database
    $conn->select_db($dbname);
    echo "<p>✓ Selected database '$dbname'</p>";
    
    // Read and execute the schema file
    $schemaFile = 'database/schema.sql';
    if (file_exists($schemaFile)) {
        $sql = file_get_contents($schemaFile);
        
        // Split the SQL into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                if ($conn->query($statement) === TRUE) {
                    echo "<p>✓ Executed SQL statement</p>";
                } else {
                    echo "<p>✗ Error executing statement: " . $conn->error . "</p>";
                    echo "<p>Statement: " . htmlspecialchars(substr($statement, 0, 100)) . "...</p>";
                }
            }
        }
        
        echo "<p>✓ Database schema loaded successfully</p>";
    } else {
        echo "<p>✗ Schema file not found: $schemaFile</p>";
    }
    
    // Test the connection with the new database
    $testConn = new mysqli($server, $username, $password, $dbname);
    if ($testConn->connect_error) {
        echo "<p>✗ Error connecting to new database: " . $testConn->connect_error . "</p>";
    } else {
        echo "<p>✓ Successfully connected to new database</p>";
        
        // Check if tables exist
        $result = $testConn->query("SHOW TABLES");
        if ($result && $result->num_rows > 0) {
            echo "<p>✓ Tables created successfully:</p><ul>";
            while ($row = $result->fetch_array()) {
                echo "<li>" . $row[0] . "</li>";
            }
            echo "</ul>";
        }
        
        // Check user count
        $result = $testConn->query("SELECT COUNT(*) as count FROM users");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p>✓ Users table contains " . $row['count'] . " records</p>";
        }
        
        $testConn->close();
    }
    
    $conn->close();
    
    echo "<h3>Setup Complete!</h3>";
    echo "<p>Your database has been set up successfully. You can now:</p>";
    echo "<ul>";
    echo "<li><a href='index.php'>View Dashboard</a></li>";
    echo "<li><a href='usertable.php'>View User Table</a></li>";
    echo "<li><a href='adduser.php'>Add New User</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p>✗ Error: " . $e->getMessage() . "</p>";
}
?>

<style>
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: #f8fafc;
    color: #111827;
}

h2, h3 {
    color: #6366f1;
}

p {
    margin: 8px 0;
    padding: 8px 12px;
    border-radius: 6px;
    background: #f3f4f6;
}

p:contains("✓") {
    background: #d1fae5;
    color: #065f46;
}

p:contains("✗") {
    background: #fee2e2;
    color: #991b1b;
}

ul {
    background: white;
    padding: 16px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

a {
    color: #6366f1;
    text-decoration: none;
    font-weight: 500;
}

a:hover {
    text-decoration: underline;
}
</style>

