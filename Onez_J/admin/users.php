<?php
session_start();
require_once __DIR__ . '/../classes/User.php';

// Check if user is logged in and is admin
$user = new User();
if (!$user->isLoggedIn() || !$user->hasRole('admin')) {
    header('Location: login.php');
    exit;
}

$currentUser = $user->getCurrentUser();

// Get all users
$allUsers = $user->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AdminLTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        .content-wrapper { background: #f4f6f9; }
        .main-header { background: #667eea !important; }
        .main-sidebar { background: #343a40 !important; }
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active { background-color: #667eea !important; }
        .card-stats { transition: transform 0.3s ease; }
        .card-stats:hover { transform: translateY(-5px); }
        .badge { font-size: 0.75rem; padding: 0.5em 0.75em; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
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
                            <a href="users.php" class="nav-link active">
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
                            <h1>Manage Users</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Manage Users</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <!-- Statistics Cards -->
                    <div class="row mb-3">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info card-stats">
                                <div class="inner">
                                    <h3><?= count($allUsers) ?></h3>
                                    <p>Total Users</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success card-stats">
                                <div class="inner">
                                    <h3><?= count(array_filter($allUsers, function($u) { return $u['is_active']; })) ?></h3>
                                    <p>Active Users</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning card-stats">
                                <div class="inner">
                                    <h3><?= count(array_filter($allUsers, function($u) { return $u['role'] === 'admin'; })) ?></h3>
                                    <p>Administrators</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary card-stats">
                                <div class="inner">
                                    <h3><?= count(array_filter($allUsers, function($u) { return $u['role'] === 'registrar'; })) ?></h3>
                                    <p>Registrars</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Users Management -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-users"></i> All Users
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="usersTable">
                                            <thead>
                                                <tr>
                                                    <th>Username</th>
                                                    <th>Full Name</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                    <th>Created</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($allUsers as $user_item): ?>
                                                <tr>
                                                    <td>
                                                        <strong><?= $user_item['username'] ?></strong>
                                                        <?php if ($user_item['id'] === $currentUser['id']): ?>
                                                            <span class="badge bg-info">You</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $user_item['full_name'] ?></td>
                                                    <td><?= $user_item['email'] ?></td>
                                                    <td>
                                                        <?php
                                                        $roleColors = [
                                                            'admin' => 'danger',
                                                            'registrar' => 'primary',
                                                            'staff' => 'info'
                                                        ];
                                                        $roleColor = $roleColors[$user_item['role']] ?? 'secondary';
                                                        ?>
                                                        <span class="badge bg-<?= $roleColor ?>"><?= ucfirst($user_item['role']) ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if ($user_item['is_active']): ?>
                                                            <span class="badge bg-success">Active</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= date('M d, Y', strtotime($user_item['created_at'])) ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-primary" onclick="editUser(<?= $user_item['id'] ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <?php if ($user_item['id'] !== $currentUser['id']): ?>
                                                                <?php if ($user_item['is_active']): ?>
                                                                    <button class="btn btn-sm btn-warning" onclick="toggleUser(<?= $user_item['id'] ?>, 0)">
                                                                        <i class="fas fa-pause"></i>
                                                                    </button>
                                                                <?php else: ?>
                                                                    <button class="btn btn-sm btn-success" onclick="toggleUser(<?= $user_item['id'] ?>, 1)">
                                                                        <i class="fas fa-play"></i>
                                                                    </button>
                                                                <?php endif; ?>
                                                                <button class="btn btn-sm btn-info" onclick="resetPassword(<?= $user_item['id'] ?>)">
                                                                    <i class="fas fa-key"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-danger" onclick="deleteUser(<?= $user_item['id'] ?>)">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Add New User -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-user-plus"></i> Add New User
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form id="addUserForm">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username *</label>
                                            <input type="text" class="form-control" id="username" name="username" required maxlength="50">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password *</label>
                                            <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="confirmPassword" class="form-label">Confirm Password *</label>
                                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required minlength="6">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="fullName" class="form-label">Full Name *</label>
                                            <input type="text" class="form-control" id="fullName" name="full_name" required maxlength="100">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email *</label>
                                            <input type="email" class="form-control" id="email" name="email" required maxlength="100">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role *</label>
                                            <select class="form-select" id="role" name="role" required>
                                                <option value="">Select Role</option>
                                                <option value="admin">Administrator</option>
                                                <option value="registrar">Registrar</option>
                                                <option value="staff">Staff</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="isActive" name="is_active" checked>
                                                <label class="form-check-label" for="isActive">
                                                    Active User
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-user-plus"></i> Add User
                                        </button>
                                    </form>
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

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="editUserId" name="id">
                        
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username *</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required maxlength="50">
                        </div>
                        
                        <div class="mb-3">
                            <label for="editFullName" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="editFullName" name="full_name" required maxlength="100">
                        </div>
                        
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required maxlength="100">
                        </div>
                        
                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role *</label>
                            <select class="form-select" id="editRole" name="role" required>
                                <option value="admin">Administrator</option>
                                <option value="registrar">Registrar</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editIsActive" name="is_active">
                                <label class="form-check-label" for="editIsActive">
                                    Active User
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateUser()">Update User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let editModal;
        
        $(document).ready(function() {
            // Initialize DataTable
            $('#usersTable').DataTable({
                "order": [[5, "desc"]],
                "pageLength": 25,
                "responsive": true
            });
            
            // Initialize modal
            editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            
            // Handle form submission
            $('#addUserForm').on('submit', function(e) {
                e.preventDefault();
                addUser();
            });
        });

        function addUser() {
            const password = $('#password').val();
            const confirmPassword = $('#confirmPassword').val();
            
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Passwords do not match!',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            const formData = new FormData(document.getElementById('addUserForm'));
            
            $.ajax({
                url: '../api/add_user.php',
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
                            text: 'User added successfully!',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to add user.',
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

        function editUser(userId) {
            // Get user data and populate modal
            $.ajax({
                url: '../api/get_user.php',
                type: 'GET',
                data: { user_id: userId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const user = response.user;
                        $('#editUserId').val(user.id);
                        $('#editUsername').val(user.username);
                        $('#editFullName').val(user.full_name);
                        $('#editEmail').val(user.email);
                        $('#editRole').val(user.role);
                        $('#editIsActive').prop('checked', user.is_active == 1);
                        
                        editModal.show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to get user data.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        function updateUser() {
            const formData = new FormData(document.getElementById('editUserForm'));
            
            $.ajax({
                url: '../api/update_user.php',
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
                            text: 'User updated successfully!',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to update user.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        function toggleUser(userId, status) {
            const action = status ? 'activate' : 'deactivate';
            
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to ${action} this user?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Yes, ${action}!`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../api/toggle_user.php',
                        type: 'POST',
                        data: { user_id: userId, status: status },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: `User ${action}d successfully!`,
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message || `Failed to ${action} user.`,
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    });
                }
            });
        }

        function resetPassword(userId) {
            Swal.fire({
                title: 'Reset Password',
                text: 'This will reset the user\'s password to "password123". Continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../api/reset_password.php',
                        type: 'POST',
                        data: { user_id: userId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Password reset successfully! New password: password123',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message || 'Failed to reset password.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    });
                }
            });
        }

        function deleteUser(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../api/delete_user.php',
                        type: 'POST',
                        data: { user_id: userId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'User has been deleted.',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message || 'Failed to delete user.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>
