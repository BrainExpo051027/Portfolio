<?php
require_once 'auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - Data Table</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form action="adduserprocess.php" method="POST">
                <h1>Add New User</h1>
                <span>Fill in the user details below</span>
                
                <?php if (isset($_GET['error'])): ?>
                    <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                        Error adding user. Please try again.
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['success'])): ?>
                    <div style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                        User added successfully!
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" name="name" id="name" placeholder="Enter full name" required class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" placeholder="Enter email address" class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" name="password" id="password" placeholder="Enter password" required class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="position">Position *</label>
                    <input type="text" name="position" id="position" placeholder="Enter position" required class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="office">Office *</label>
                    <input type="text" name="office" id="office" placeholder="Enter office location" required class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="age">Age *</label>
                    <input type="number" name="age" id="age" placeholder="Enter age" required class="form-control" min="18" max="100" />
                </div>
                
                <div class="form-group">
                    <label for="start_date">Start Date *</label>
                    <input type="date" name="start_date" id="start_date" required class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="salary">Salary *</label>
                    <input type="number" name="salary" id="salary" placeholder="Enter salary" required class="form-control" min="0" step="0.01" />
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" placeholder="Enter phone number" class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="status">Status *</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">
                        <ion-icon name="person-add-outline"></ion-icon>
                        Add User
                    </button>
                    <a href="usertable.php" class="btn btn-secondary">
                        <ion-icon name="arrow-back-outline"></ion-icon>
                        Back to Users
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
