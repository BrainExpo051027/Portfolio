<?php
session_start();
require_once __DIR__ . '/../classes/User.php';

// Check if user is logged in
$user = new User();
if (!$user->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$currentUser = $user->getCurrentUser();
$userData = $user->getUserById($currentUser['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AdminLTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
    
    <style>
        .content-wrapper { background: #f4f6f9; }
        .main-header { background: #667eea !important; }
        .main-sidebar { background: #343a40 !important; }
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active { background-color: #667eea !important; }
        .profile-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .profile-avatar { width: 120px; height: 120px; border: 4px solid white; }
        .profile-stats { background: white; border-radius: 10px; padding: 1rem; }
        .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
    </style>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> <?= htmlspecialchars($currentUser['full_name']) ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-cog"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="dashboard.php" class="brand-link">
                <i class="fas fa-clipboard-list brand-image img-circle elevation-3" style="opacity: .8"></i>
                <span class="brand-text font-weight-light">Queue System</span>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="dashboard.php" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="queues.php" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>Manage Queues</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="services.php" class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>Manage Services</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="users.php" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Manage Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="reports.php" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Reports</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>My Profile</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <!-- Profile Header -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card profile-header">
                                <div class="card-body text-center py-4">
                                    <div class="mb-3">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($currentUser['full_name']) ?>&size=120&background=667eea&color=fff" 
                                             alt="Profile Avatar" class="profile-avatar rounded-circle">
                                    </div>
                                    <h2 class="mb-1"><?= htmlspecialchars($currentUser['full_name']) ?></h2>
                                    <p class="mb-2 opacity-75">
                                        <i class="fas fa-user-shield"></i> 
                                        <?= ucfirst($currentUser['role']) ?>
                                    </p>
                                    <p class="mb-0 opacity-75">
                                        <i class="fas fa-clock"></i> 
                                        Member since <?= date('M Y', strtotime($userData['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Content -->
                    <div class="row">
                        <!-- Profile Information -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-user"></i> Profile Information
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form id="profileForm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="username" name="username" 
                                                           value="<?= htmlspecialchars($userData['username']) ?>" readonly>
                                                    <small class="form-text text-muted">Username cannot be changed</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="role" class="form-label">Role</label>
                                                    <input type="text" class="form-control" id="role" 
                                                           value="<?= ucfirst($userData['role']) ?>" readonly>
                                                    <small class="form-text text-muted">Role is managed by administrators</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="fullName" class="form-label">Full Name *</label>
                                                    <input type="text" class="form-control" id="fullName" name="full_name" 
                                                           value="<?= htmlspecialchars($userData['full_name']) ?>" required maxlength="100">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email *</label>
                                                    <input type="email" class="form-control" id="email" name="email" 
                                                           value="<?= htmlspecialchars($userData['email']) ?>" required maxlength="100">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="bio" class="form-label">Bio</label>
                                            <textarea class="form-control" id="bio" name="bio" rows="3" maxlength="255" 
                                                      placeholder="Tell us about yourself..."><?= htmlspecialchars($userData['bio'] ?? '') ?></textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Profile
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Change Password -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-key"></i> Change Password
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form id="passwordForm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="currentPassword" class="form-label">Current Password *</label>
                                                    <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="newPassword" class="form-label">New Password *</label>
                                                    <input type="password" class="form-control" id="newPassword" name="new_password" 
                                                           required minlength="6">
                                                    <small class="form-text text-muted">Minimum 6 characters</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="confirmPassword" class="form-label">Confirm New Password *</label>
                                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-key"></i> Change Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Profile Statistics -->
                        <div class="col-lg-4">
                            <!-- Account Status -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-info-circle"></i> Account Status
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="profile-stats">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <h4 class="text-success mb-1">
                                                    <i class="fas fa-check-circle"></i>
                                                </h4>
                                                <small class="text-muted">Active</small>
                                            </div>
                                            <div class="col-6">
                                                <h4 class="text-info mb-1">
                                                    <i class="fas fa-calendar"></i>
                                                </h4>
                                                <small class="text-muted"><?= date('M d, Y', strtotime($userData['created_at'])) ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-bolt"></i> Quick Actions
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="dashboard.php" class="btn btn-outline-primary">
                                            <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                                        </a>
                                        <a href="queues.php" class="btn btn-outline-info">
                                            <i class="fas fa-list"></i> Manage Queues
                                        </a>
                                        <a href="reports.php" class="btn btn-outline-success">
                                            <i class="fas fa-chart-bar"></i> View Reports
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- System Information -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-cog"></i> System Info
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="small text-muted">
                                        <p><strong>Last Login:</strong> <?= date('M d, Y H:i') ?></p>
                                        <p><strong>Session:</strong> Active</p>
                                        <p><strong>Version:</strong> 1.0.0</p>
                                        <p><strong>PHP:</strong> <?= PHP_VERSION ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">Version 1.0.0</div>
            <strong>Copyright &copy; 2024 <a href="#">Registrar Queuing System</a>.</strong> All rights reserved.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Handle profile form submission
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();
                updateProfile();
            });

            // Handle password form submission
            $('#passwordForm').on('submit', function(e) {
                e.preventDefault();
                changePassword();
            });
        });

        function updateProfile() {
            const formData = new FormData(document.getElementById('profileForm'));
            
            $.ajax({
                url: '../api/update_profile.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Profile updated successfully!',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to update profile.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Network error occurred.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        function changePassword() {
            const newPassword = $('#newPassword').val();
            const confirmPassword = $('#confirmPassword').val();
            
            if (newPassword !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'New passwords do not match!',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            const formData = new FormData(document.getElementById('passwordForm'));
            
            $.ajax({
                url: '../api/change_password.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Password changed successfully!',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Clear password fields
                            $('#currentPassword').val('');
                            $('#newPassword').val('');
                            $('#confirmPassword').val('');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to change password.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Network error occurred.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    </script>
</body>
</html>
