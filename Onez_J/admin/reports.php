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

// Get date range (default to current month)
$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-t');

// Get statistics for the date range
$stats = $queueTicket->getQueueStatsByDateRange($startDate, $endDate);
$services = $service->getAllServices();
$dailyStats = $queueTicket->getDailyStats($startDate, $endDate);
$serviceStats = $queueTicket->getServiceStats($startDate, $endDate);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AdminLTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- Chart.js CSS -->
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css" rel="stylesheet">
    <!-- DateRangePicker CSS -->
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">
    
    <style>
        .content-wrapper { background: #f4f6f9; }
        .main-header { background: #667eea !important; }
        .main-sidebar { background: #343a40 !important; }
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active { background-color: #667eea !important; }
        .card-stats { transition: transform 0.3s ease; }
        .card-stats:hover { transform: translateY(-5px); }
        .chart-container { position: relative; height: 400px; }
        .report-card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .metric-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
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
                            <a href="reports.php" class="nav-link active">
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
                            <h1>Reports & Analytics</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Reports</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <!-- Date Range Filter -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card report-card">
                                <div class="card-body">
                                    <form method="GET" class="row align-items-end">
                                        <div class="col-md-3">
                                            <label for="dateRange" class="form-label">Date Range</label>
                                            <input type="text" id="dateRange" name="date_range" class="form-control" value="<?= $startDate ?> - <?= $endDate ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-filter"></i> Apply Filter
                                            </button>
                                            <a href="reports.php" class="btn btn-secondary">
                                                <i class="fas fa-undo"></i> Reset
                                            </a>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <button type="button" class="btn btn-success" onclick="exportReport()">
                                                <i class="fas fa-download"></i> Export Report
                                            </button>
                                            <button type="button" class="btn btn-info" onclick="printReport()">
                                                <i class="fas fa-print"></i> Print Report
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Key Metrics -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="card metric-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="mb-0"><?= $stats['total_tickets'] ?? 0 ?></h3>
                                            <p class="mb-0">Total Tickets</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-ticket-alt fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card metric-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="mb-0"><?= $stats['completed'] ?? 0 ?></h3>
                                            <p class="mb-0">Completed</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card metric-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="mb-0"><?= $stats['average_wait_time'] ?? 0 ?></h3>
                                            <p class="mb-0">Avg Wait (min)</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card metric-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="mb-0"><?= $stats['completion_rate'] ?? 0 ?>%</h3>
                                            <p class="mb-0">Completion Rate</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-percentage fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row mb-4">
                        <div class="col-lg-8">
                            <div class="card report-card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-line"></i> Daily Ticket Trends
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="dailyTrendsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card report-card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie"></i> Service Distribution
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="serviceDistributionChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Statistics -->
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <div class="card report-card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-list"></i> Service Performance
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Service</th>
                                                    <th>Tickets</th>
                                                    <th>Avg Wait</th>
                                                    <th>Completion</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($serviceStats as $service): ?>
                                                <tr>
                                                    <td><?= $service['service_name'] ?></td>
                                                    <td><span class="badge bg-primary"><?= $service['ticket_count'] ?></span></td>
                                                    <td><?= $service['avg_wait_time'] ?> min</td>
                                                    <td><?= $service['completion_rate'] ?>%</td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card report-card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-bar"></i> Status Breakdown
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="statusBreakdownChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Time Analysis -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card report-card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-clock"></i> Peak Hours Analysis
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="peakHoursChart"></canvas>
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
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
    <!-- DateRangePicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!-- Moment.js -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DateRangePicker
            $('#dateRange').daterangepicker({
                opens: 'left',
                startDate: moment('<?= $startDate ?>'),
                endDate: moment('<?= $endDate ?>'),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            // Initialize charts
            initializeCharts();
        });

        function initializeCharts() {
            // Daily Trends Chart
            const dailyCtx = document.getElementById('dailyTrendsChart').getContext('2d');
            new Chart(dailyCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_column($dailyStats, 'date')) ?>,
                    datasets: [{
                        label: 'Total Tickets',
                        data: <?= json_encode(array_column($dailyStats, 'total_tickets')) ?>,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Completed',
                        data: <?= json_encode(array_column($dailyStats, 'completed')) ?>,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Service Distribution Chart
            const serviceCtx = document.getElementById('serviceDistributionChart').getContext('2d');
            new Chart(serviceCtx, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode(array_column($serviceStats, 'service_name')) ?>,
                    datasets: [{
                        data: <?= json_encode(array_column($serviceStats, 'ticket_count')) ?>,
                        backgroundColor: [
                            '#667eea',
                            '#764ba2',
                            '#f093fb',
                            '#f5576c',
                            '#4facfe',
                            '#00f2fe'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });

            // Status Breakdown Chart
            const statusCtx = document.getElementById('statusBreakdownChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: ['Waiting', 'Called', 'Serving', 'Completed', 'Skipped', 'Cancelled'],
                    datasets: [{
                        label: 'Count',
                        data: [
                            <?= $stats['waiting'] ?? 0 ?>,
                            <?= $stats['called'] ?? 0 ?>,
                            <?= $stats['serving'] ?? 0 ?>,
                            <?= $stats['completed'] ?? 0 ?>,
                            <?= $stats['skipped'] ?? 0 ?>,
                            <?= $stats['cancelled'] ?? 0 ?>
                        ],
                        backgroundColor: [
                            '#ffc107',
                            '#17a2b8',
                            '#007bff',
                            '#28a745',
                            '#6c757d',
                            '#dc3545'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Peak Hours Chart
            const peakCtx = document.getElementById('peakHoursChart').getContext('2d');
            new Chart(peakCtx, {
                type: 'bar',
                data: {
                    labels: ['8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM', '5PM'],
                    datasets: [{
                        label: 'Tickets Created',
                        data: [15, 25, 35, 40, 30, 20, 25, 30, 20, 10], // Sample data
                        backgroundColor: '#667eea'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function exportReport() {
            // Implementation for exporting report
            Swal.fire({
                icon: 'info',
                title: 'Export Feature',
                text: 'Report export functionality will be implemented here.',
                confirmButtonText: 'OK'
            });
        }

        function printReport() {
            // Implementation for printing report
            window.print();
        }
    </script>
</body>
</html>
