<?php
session_start();
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Service.php';

// Check if user is logged in
$user = new User();
if (!$user->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$currentUser = $user->getCurrentUser();
$service = new Service();

// Get all services
$services = $service->getAllServices();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services - Admin Dashboard</title>
    
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
                            <a href="services.php" class="nav-link active">
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
                            <h1>Manage Services</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Manage Services</li>
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
                                    <h3><?= count($services) ?></h3>
                                    <p>Total Services</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-cogs"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success card-stats">
                                <div class="inner">
                                    <h3><?= count(array_filter($services, function($s) { return $s['is_active']; })) ?></h3>
                                    <p>Active Services</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning card-stats">
                                <div class="inner">
                                    <h3><?= count(array_filter($services, function($s) { return !$s['is_active']; })) ?></h3>
                                    <p>Inactive Services</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-pause-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary card-stats">
                                <div class="inner">
                                    <h3><?= array_sum(array_column($services, 'queue_count')) ?></h3>
                                    <p>Total Queues</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Services Management -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-cogs"></i> All Services
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="servicesTable">
                                            <thead>
                                                <tr>
                                                    <th>Service Code</th>
                                                    <th>Service Name</th>
                                                    <th>Description</th>
                                                    <th>Status</th>
                                                    <th>Queue Count</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($services as $service_item): ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-primary"><?= $service_item['service_code'] ?></span>
                                                    </td>
                                                    <td><?= $service_item['service_name'] ?></td>
                                                    <td><?= $service_item['description'] ?: '-' ?></td>
                                                    <td>
                                                        <?php if ($service_item['is_active']): ?>
                                                            <span class="badge bg-success">Active</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info"><?= $service_item['queue_count'] ?? 0 ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-primary" onclick="editService(<?= $service_item['id'] ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <?php if ($service_item['is_active']): ?>
                                                                <button class="btn btn-sm btn-warning" onclick="toggleService(<?= $service_item['id'] ?>, 0)">
                                                                    <i class="fas fa-pause"></i>
                                                                </button>
                                                            <?php else: ?>
                                                                <button class="btn btn-sm btn-success" onclick="toggleService(<?= $service_item['id'] ?>, 1)">
                                                                    <i class="fas fa-play"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                            <button class="btn btn-sm btn-danger" onclick="deleteService(<?= $service_item['id'] ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
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
                            <!-- Add New Service -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-plus"></i> Add New Service
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form id="addServiceForm">
                                        <div class="mb-3">
                                            <label for="serviceCode" class="form-label">Service Code *</label>
                                            <input type="text" class="form-control" id="serviceCode" name="service_code" required maxlength="10">
                                            <small class="form-text text-muted">Short code for the service (e.g., TOR, ENR)</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="serviceName" class="form-label">Service Name *</label>
                                            <input type="text" class="form-control" id="serviceName" name="service_name" required maxlength="100">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3" maxlength="255"></textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="estimatedTime" class="form-label">Estimated Time (minutes)</label>
                                            <input type="number" class="form-control" id="estimatedTime" name="estimated_time" min="1" max="480" value="30">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="isActive" name="is_active" checked>
                                                <label class="form-check-label" for="isActive">
                                                    Active Service
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-plus"></i> Add Service
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

    <!-- Edit Service Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editServiceForm">
                        <input type="hidden" id="editServiceId" name="id">
                        
                        <div class="mb-3">
                            <label for="editServiceCode" class="form-label">Service Code *</label>
                            <input type="text" class="form-control" id="editServiceCode" name="service_code" required maxlength="10">
                        </div>
                        
                        <div class="mb-3">
                            <label for="editServiceName" class="form-label">Service Name *</label>
                            <input type="text" class="form-control" id="editServiceName" name="service_name" required maxlength="100">
                        </div>
                        
                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="3" maxlength="255"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editEstimatedTime" class="form-label">Estimated Time (minutes)</label>
                            <input type="number" class="form-control" id="editEstimatedTime" name="estimated_time" min="1" max="480">
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editIsActive" name="is_active">
                                <label class="form-check-label" for="editIsActive">
                                    Active Service
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateService()">Update Service</button>
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
            $('#servicesTable').DataTable({
                "order": [[0, "asc"]],
                "pageLength": 25,
                "responsive": true
            });
            
            // Initialize modal
            editModal = new bootstrap.Modal(document.getElementById('editServiceModal'));
            
            // Handle form submission
            $('#addServiceForm').on('submit', function(e) {
                e.preventDefault();
                addService();
            });
        });

        function addService() {
            const formData = new FormData(document.getElementById('addServiceForm'));
            
            $.ajax({
                url: '../api/add_service.php',
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
                            text: 'Service added successfully!',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to add service.',
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

        function editService(serviceId) {
            // Get service data and populate modal
            $.ajax({
                url: '../api/get_service.php',
                type: 'GET',
                data: { service_id: serviceId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const service = response.service;
                        $('#editServiceId').val(service.id);
                        $('#editServiceCode').val(service.service_code);
                        $('#editServiceName').val(service.service_name);
                        $('#editDescription').val(service.description);
                        $('#editEstimatedTime').val(service.estimated_time);
                        $('#editIsActive').prop('checked', service.is_active == 1);
                        
                        editModal.show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to get service data.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        function updateService() {
            const formData = new FormData(document.getElementById('editServiceForm'));
            
            $.ajax({
                url: '../api/update_service.php',
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
                            text: 'Service updated successfully!',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to update service.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        function toggleService(serviceId, status) {
            const action = status ? 'activate' : 'deactivate';
            
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to ${action} this service?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Yes, ${action}!`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../api/toggle_service.php',
                        type: 'POST',
                        data: { service_id: serviceId, status: status },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: `Service ${action}d successfully!`,
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message || `Failed to ${action} service.`,
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    });
                }
            });
        }

        function deleteService(serviceId) {
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
                        url: '../api/delete_service.php',
                        type: 'POST',
                        data: { service_id: serviceId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Service has been deleted.',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message || 'Failed to delete service.',
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
