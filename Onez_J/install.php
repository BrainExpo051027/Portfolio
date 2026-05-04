<?php
// Simple installation script for Registrar Queuing System
// Run this file once to set up the database and initial configuration

// Check if already installed
if (file_exists('config/installed.txt')) {
    die('System is already installed. Remove config/installed.txt to reinstall.');
}

// Check PHP version
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    die('PHP 7.4 or higher is required. Current version: ' . PHP_VERSION);
}

// Check if mysqli extension is available
if (!extension_loaded('mysqli')) {
    die('MySQLi extension is required but not installed.');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Registrar Queuing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .install-container { max-width: 600px; margin: 50px auto; }
        .step { margin-bottom: 2rem; }
        .step-number { 
            background: #667eea; 
            color: white; 
            width: 30px; 
            height: 30px; 
            border-radius: 50%; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            margin-right: 10px; 
        }
    </style>
</head>
<body>
    <div class="container install-container">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h3><i class="fas fa-clipboard-list"></i> Registrar Queuing System Installation</h3>
            </div>
            <div class="card-body">
                
                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <?php
                    $host = $_POST['host'] ?? 'localhost';
                    $username = $_POST['username'] ?? '';
                    $password = $_POST['password'] ?? '';
                    $database = $_POST['database'] ?? 'onez';
                    
                    if (empty($username) || empty($database)) {
                        echo '<div class="alert alert-danger">Username and database name are required.</div>';
                    } else {
                        try {
                            // Test database connection
                            $connection = new mysqli($host, $username, $password);
                            
                            if ($connection->connect_error) {
                                throw new Exception("Connection failed: " . $connection->connect_error);
                            }
                            
                            // Create database if it doesn't exist
                            $connection->query("CREATE DATABASE IF NOT EXISTS `$database`");
                            $connection->select_db($database);
                            
                            // Read and execute schema
                            $schema = file_get_contents('database/schema.sql');
                            $queries = explode(';', $schema);
                            
                            foreach ($queries as $query) {
                                $query = trim($query);
                                if (!empty($query)) {
                                    if (!$connection->query($query)) {
                                        throw new Exception("Error executing query: " . $connection->error);
                                    }
                                }
                            }
                            
                            // Update database configuration
                            $config_content = file_get_contents('config/Database.php');
                            $config_content = str_replace(
                                "private \$host = 'localhost';",
                                "private \$host = '$host';",
                                $config_content
                            );
                            $config_content = str_replace(
                                "private \$username = 'root';",
                                "private \$username = '$username';",
                                $config_content
                            );
                            $config_content = str_replace(
                                "private \$password = '';",
                                "private \$password = '$password';",
                                $config_content
                            );
                            $config_content = str_replace(
                                "private \$database = 'onez';",
                                "private \$database = '$database';",
                                $config_content
                            );
                            
                            file_put_contents('config/Database.php', $config_content);
                            
                            // Create installed marker
                            file_put_contents('config/installed.txt', date('Y-m-d H:i:s'));
                            
                            echo '<div class="alert alert-success">
                                <h4><i class="fas fa-check-circle"></i> Installation Successful!</h4>
                                <p>The Registrar Queuing System has been installed successfully.</p>
                                <hr>
                                <h5>Default Login Credentials:</h5>
                                <p><strong>Username:</strong> admin<br>
                                <strong>Password:</strong> admin123</p>
                                <div class="alert alert-warning">
                                    <strong>Important:</strong> Please change the default password after first login!
                                </div>
                                <hr>
                                <a href="index.php" class="btn btn-primary">Go to Student Interface</a>
                                <a href="admin/login.php" class="btn btn-success">Go to Admin Panel</a>
                            </div>';
                            
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger">
                                <h4><i class="fas fa-exclamation-triangle"></i> Installation Failed</h4>
                                <p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>
                                <a href="install.php" class="btn btn-primary">Try Again</a>
                            </div>';
                        }
                    }
                    ?>
                <?php else: ?>
                    <div class="step">
                        <h5><span class="step-number">1</span>System Requirements Check</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>PHP Version:</strong> <?= PHP_VERSION ?> 
                                    <?= version_compare(PHP_VERSION, '7.4.0', '>=') ? '<span class="text-success">✓</span>' : '<span class="text-danger">✗</span>' ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>MySQLi Extension:</strong> 
                                    <?= extension_loaded('mysqli') ? '<span class="text-success">✓</span>' : '<span class="text-danger">✗</span>' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="step">
                        <h5><span class="step-number">2</span>Database Configuration</h5>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="host" class="form-label">Database Host</label>
                                <input type="text" class="form-control" id="host" name="host" value="localhost" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Database Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="root" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Database Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            
                            <div class="mb-3">
                                <label for="database" class="form-label">Database Name</label>
                                <input type="text" class="form-control" id="database" name="database" value="onez" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-download"></i> Install System
                            </button>
                        </form>
                    </div>
                    
                    <div class="step">
                        <h5><span class="step-number">3</span>What Will Be Installed</h5>
                        <ul class="list-group">
                            <li class="list-group-item">Database tables (users, services, queue_tickets)</li>
                            <li class="list-group-item">Default admin user (admin/admin123)</li>
                            <li class="list-group-item">Default services (TOR, Enrollment, ID Validation, etc.)</li>
                            <li class="list-group-item">Database configuration</li>
                        </ul>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
