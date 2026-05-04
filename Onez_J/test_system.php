<?php
// Test the new form system
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing New Form System</h2>";

try {
    // Test database connection
    require_once 'config/Database.php';
    $db = new Database();
    echo "<p style='color: green;'>✓ Database connection successful!</p>";
    
    // Test service retrieval
    require_once 'classes/Service.php';
    $service = new Service();
    $services = $service->getAllServices();
    
    if ($services) {
        echo "<p style='color: green;'>✓ Services loaded successfully! Found " . count($services) . " services.</p>";
        
        echo "<h3>Available Services:</h3>";
        echo "<ul>";
        foreach ($services as $s) {
            echo "<li><strong>{$s['service_name']}</strong> ({$s['service_code']}) - {$s['estimated_duration']} minutes</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>⚠ No services found. You may need to run the installation.</p>";
    }
    
    // Test QueueTicket class
    require_once 'classes/QueueTicket.php';
    $queueTicket = new QueueTicket();
    echo "<p style='color: green;'>✓ QueueTicket class loaded successfully!</p>";
    
    echo "<h3>System Status:</h3>";
    echo "<p><strong>Database:</strong> onez</p>";
    echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
    echo "<p><strong>MySQLi Extension:</strong> " . (extension_loaded('mysqli') ? 'Available' : 'Not Available') . "</p>";
    
    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li><a href='index.php' target='_blank'>Test the main student interface</a></li>";
    echo "<li><a href='admin/login.php' target='_blank'>Test the admin login</a></li>";
    echo "<li>If you need to install the database, run: <a href='install.php' target='_blank'>install.php</a></li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}
?>
