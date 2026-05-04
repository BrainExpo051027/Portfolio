<?php
session_start();
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/QueueTicket.php';
require_once __DIR__ . '/../classes/Service.php';

// Check if user is logged in
$user = new User();
if (!$user->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$currentUser = $user->getCurrentUser();
$queueTicket = new QueueTicket();
$service = new Service();

// Get all tickets with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

$allTickets = $queueTicket->getAllTickets($limit, $offset);
$totalTickets = $queueTicket->getTotalTickets();
$totalPages = ceil($totalTickets / $limit);

$services = $service->getAllServices();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Queues - Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AdminLTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- DateRangePicker CSS -->
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">
    
    <style>
        .content-wrapper { background: #f4f6f9; }
        .main-header { background: #667eea !important; }
        .main-sidebar { background: #343a40 !important; }
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active { background-color: #667eea !important; }
        .card-stats { transition: transform 0.3s ease; }
        .card-stats:hover { transform: translateY(-5px); }
        .badge { font-size: 0.75rem; padding: 0.5em 0.75em; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
        .filter-section { background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
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
                            <a href="queues.php" class="nav-link active">
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
                            <h1>Manage Queues</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Manage Queues</li>
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
                                    <h3><?= $queueTicket->getTotalTickets() ?></h3>
                                    <p>Total Tickets</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning card-stats">
                                <div class="inner">
                                    <h3><?= $queueTicket->getTicketsByStatus('waiting') ?></h3>
                                    <p>Waiting</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success card-stats">
                                <div class="inner">
                                    <h3><?= $queueTicket->getTicketsByStatus('completed') ?></h3>
                                    <p>Completed</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger card-stats">
                                <div class="inner">
                                    <h3><?= $queueTicket->getTicketsByStatus('cancelled') ?></h3>
                                    <p>Cancelled</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="filter-section">
                        <div class="row">
                            <div class="col-md-2">
                                <label>Date Range:</label>
                                <input type="text" id="dateRange" class="form-control form-control-sm" placeholder="Select dates">
                            </div>
                            <div class="col-md-2">
                                <label>Status:</label>
                                <select id="statusFilter" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="waiting">Waiting</option>
                                    <option value="called">Called</option>
                                    <option value="serving">Serving</option>
                                    <option value="completed">Completed</option>
                                    <option value="skipped">Skipped</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Service:</label>
                                <select id="serviceFilter" class="form-select form-select-sm">
                                    <option value="">All Services</option>
                                    <?php foreach ($services as $service_item): ?>
                                    <option value="<?= $service_item['id'] ?>"><?= $service_item['service_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Priority:</label>
                                <select id="priorityFilter" class="form-select form-select-sm">
                                    <option value="">All Priority</option>
                                    <option value="normal">Normal</option>
                                    <option value="priority">Priority</option>
                                    <option value="emergency">Emergency</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Search:</label>
                                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="applyFilters()">
                                        <i class="fas fa-filter"></i> Apply
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="clearFilters()">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Queue Table -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i> All Queue Tickets
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success btn-sm" onclick="exportToCSV()">
                                    <i class="fas fa-download"></i> Export CSV
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="queueTable">
                                    <thead>
                                        <tr>
                                            <th>Ticket #</th>
                                            <th>Service</th>
                                            <th>Student</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Updated</th>
                                            <th>Notes</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allTickets as $ticket): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary"><?= $ticket['ticket_number'] ?></span>
                                            </td>
                                            <td><?= $ticket['service_name'] ?></td>
                                            <td><?= $ticket['student_name'] ?></td>
                                            <td>
                                                <?php
                                                $priorityColors = [
                                                    'normal' => 'secondary',
                                                    'priority' => 'warning',
                                                    'emergency' => 'danger'
                                                ];
                                                $priorityColor = $priorityColors[$ticket['priority'] ?? 'normal'] ?? 'secondary';
                                                $priorityText = ucfirst($ticket['priority'] ?? 'normal');
                                                ?>
                                                <span class="badge bg-<?= $priorityColor ?>"><?= $priorityText ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                $statusColors = [
                                                    'waiting' => 'warning',
                                                    'called' => 'info',
                                                    'serving' => 'primary',
                                                    'completed' => 'success',
                                                    'skipped' => 'secondary',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusColor = $statusColors[$ticket['status']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $statusColor ?>"><?= ucfirst($ticket['status']) ?></span>
                                            </td>
                                            <td><?= date('M d, Y H:i', strtotime($ticket['created_at'])) ?></td>
                                            <td><?= date('M d, Y H:i', strtotime($ticket['updated_at'])) ?></td>
                                            <td>
                                                <?php if (!empty($ticket['notes'])): ?>
                                                    <span class="text-muted" title="<?= htmlspecialchars($ticket['notes']) ?>">
                                                        <i class="fas fa-sticky-note"></i> <?= strlen($ticket['notes']) > 20 ? substr($ticket['notes'], 0, 20) . '...' : $ticket['notes'] ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <?php if ($ticket['status'] === 'waiting'): ?>
                                                        <button class="btn btn-sm btn-info" onclick="callTicket(<?= $ticket['id'] ?>)">
                                                            <i class="fas fa-bullhorn"></i>
                                                        </button>
                                                    <?php elseif ($ticket['status'] === 'called'): ?>
                                                        <button class="btn btn-sm btn-success" onclick="completeTicket(<?= $ticket['id'] ?>)">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-warning" onclick="skipTicket(<?= $ticket['id'] ?>)">
                                                            <i class="fas fa-forward"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button class="btn btn-sm btn-secondary" onclick="viewTicket(<?= $ticket['id'] ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="cancelTicket(<?= $ticket['id'] ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if ($totalPages > 1): ?>
                            <nav aria-label="Page navigation" class="mt-3">
                                <ul class="pagination justify-content-center">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                            <?php endif; ?>
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
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- DateRangePicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#queueTable').DataTable({
                "order": [[5, "desc"]],
                "pageLength": 50,
                "responsive": true,
                "autoWidth": false
            });

            // Initialize DateRangePicker
            $('#dateRange').daterangepicker({
                opens: 'left',
                maxDate: new Date(),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });
        });

        function applyFilters() {
            // Implementation for applying filters
            console.log('Filters applied');
        }

        function clearFilters() {
            $('#dateRange').val('');
            $('#statusFilter').val('');
            $('#serviceFilter').val('');
            $('#priorityFilter').val('');
            $('#searchInput').val('');
        }

        function callTicket(ticketId) {
            // Implementation for calling ticket
            console.log('Calling ticket:', ticketId);
        }

        function completeTicket(ticketId) {
            // Implementation for completing ticket
            console.log('Completing ticket:', ticketId);
        }

        function skipTicket(ticketId) {
            // Implementation for skipping ticket
            console.log('Skipping ticket:', ticketId);
        }

        function viewTicket(ticketId) {
            // Implementation for viewing ticket details
            console.log('Viewing ticket:', ticketId);
        }

        function cancelTicket(ticketId) {
            // Implementation for cancelling ticket
            console.log('Cancelling ticket:', ticketId);
        }

        function exportToCSV() {
            // Implementation for exporting to CSV
            console.log('Exporting to CSV');
        }
    </script>
</body>
</html>
