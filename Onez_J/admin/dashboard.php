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

// Get statistics
$stats = $queueTicket->getQueueStats();
$services = $service->getAllServices();
$todayTickets = $queueTicket->getTodayTickets();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Registrar Queuing System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AdminLTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        .content-wrapper {
            background: #f4f6f9;
        }
        
        .main-header {
            background: #667eea !important;
        }
        
        .main-sidebar {
            background: #343a40 !important;
        }
        
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #667eea !important;
        }
        
        .card-stats {
            transition: transform 0.3s ease;
        }
        
        .card-stats:hover {
            transform: translateY(-5px);
        }
        
        .queue-display {
            background: #667eea;
            color: white;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .current-ticket {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .service-name {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .notifications {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .alert {
            margin-bottom: 0.5rem;
            border-radius: 8px;
        }
        
        .filter-controls {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.5em 0.75em;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
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
            <!-- Brand Logo -->
            <a href="../../index.html" class="brand-link">
                <i class="fas fa-clipboard-list brand-image img-circle elevation-3" style="opacity: .8"></i>
                <span class="brand-text font-weight-light">Queue System</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="dashboard.php" class="nav-link active">
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
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Dashboard</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Welcome Message -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fas fa-info-circle"></i>
                                <strong>Welcome back, <?= htmlspecialchars($currentUser['full_name']) ?>!</strong>
                                You're managing the queue system. Use the quick actions to call next tickets or manage individual tickets below.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-lg-2 col-6">
                            <div class="small-box bg-info card-stats">
                                <div class="inner">
                                    <h3><?= $stats['total_tickets'] ?? 0 ?></h3>
                                    <p>Total Tickets Today</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-2 col-6">
                            <div class="small-box bg-warning card-stats">
                                <div class="inner">
                                    <h3><?= $stats['waiting'] ?? 0 ?></h3>
                                    <p>Waiting</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-2 col-6">
                            <div class="small-box bg-primary card-stats">
                                <div class="inner">
                                    <h3><?= $stats['called'] ?? 0 ?></h3>
                                    <p>Called</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-2 col-6">
                            <div class="small-box bg-success card-stats">
                                <div class="inner">
                                    <h3><?= $stats['completed'] ?? 0 ?></h3>
                                    <p>Completed</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-2 col-6">
                            <div class="small-box bg-danger card-stats">
                                <div class="inner">
                                    <h3><?= $stats['skipped'] ?? 0 ?></h3>
                                    <p>Skipped</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-forward"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-2 col-6">
                            <div class="small-box bg-secondary card-stats">
                                <div class="inner">
                                    <h3><?= $stats['average_wait_time'] ?? 0 ?></h3>
                                    <p>Avg Wait (min)</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Queue Management -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-list"></i> Today's Queue
                                    </h3>
                                    <div class="card-tools">
                                        <div class="input-group input-group-sm" style="width: 250px;">
                                            <input type="text" id="searchInput" class="form-control float-right" placeholder="Search tickets...">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-default" onclick="clearSearch()">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Filter Controls -->
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <select id="statusFilter" class="form-select form-select-sm" onchange="applyFilters()">
                                                <option value="">All Status</option>
                                                <option value="waiting">Waiting</option>
                                                <option value="called">Called</option>
                                                <option value="serving">Serving</option>
                                                <option value="completed">Completed</option>
                                                <option value="skipped">Skipped</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select id="priorityFilter" class="form-select form-select-sm" onchange="applyFilters()">
                                                <option value="">All Priority</option>
                                                <option value="normal">Normal</option>
                                                <option value="priority">Priority</option>
                                                <option value="emergency">Emergency</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select id="serviceFilter" class="form-select form-select-sm" onchange="applyFilters()">
                                                <option value="">All Services</option>
                                                <?php foreach ($services as $service_item): ?>
                                                <option value="<?= $service_item['id'] ?>"><?= $service_item['service_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                                                <i class="fas fa-filter"></i> Clear Filters
                                            </button>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="queueTable">
                                            <thead>
                                                <tr>
                                                    <th>Ticket #</th>
                                                    <th>Service</th>
                                                    <th>Student</th>
                                                    <th>Priority</th>
                                                    <th>Status</th>
                                                    <th>Time</th>
                                                    <th>Notes</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($todayTickets as $ticket): ?>
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
                                                    <td><?= date('H:i', strtotime($ticket['created_at'])) ?></td>
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
                                                        <?php if ($ticket['status'] === 'waiting'): ?>
                                                        <button class="btn btn-sm btn-info" onclick="callTicket(<?= $ticket['id'] ?>)">
                                                            <i class="fas fa-bullhorn"></i> Call
                                                        </button>
                                                        <?php elseif ($ticket['status'] === 'called'): ?>
                                                        <button class="btn btn-sm btn-success" onclick="completeTicket(<?= $ticket['id'] ?>)">
                                                            <i class="fas fa-check"></i> Complete
                                                        </button>
                                                        <button class="btn btn-sm btn-warning" onclick="skipTicket(<?= $ticket['id'] ?>)">
                                                            <i class="fas fa-forward"></i> Skip
                                                        </button>
                                                        <?php endif; ?>
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
                            <!-- Service Quick Actions -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-cogs"></i> Quick Actions
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($services as $service_item): ?>
                                    <div class="mb-3">
                                        <h6><?= $service_item['service_name'] ?></h6>
                                        <button class="btn btn-primary btn-sm w-100" onclick="callNextTicket(<?= $service_item['id'] ?>)">
                                            <i class="fas fa-bullhorn"></i> Call Next
                                        </button>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Current Display -->
                            <div class="queue-display" id="currentDisplay" style="display: none;">
                                <div class="current-ticket" id="currentTicket"></div>
                                <div class="service-name" id="currentService"></div>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-light" onclick="hideCurrentDisplay()">
                                        <i class="fas fa-eye-slash"></i> Hide
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Notifications -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-bell"></i> Recent Activity
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div id="notifications" style="max-height: 200px; overflow-y: auto;">
                                        <!-- Notifications will be populated here -->
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
            <div class="float-right d-none d-sm-inline">
                Version 1.0.0
            </div>
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
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        

        
        // Real-time queue updates
        function updateQueueDisplay() {
            $.ajax({
                url: '../api/get_queue_status.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateStatistics(response.stats);
                        updateQueueTable(response.tickets);
                        updateCurrentDisplay(response.current);
                    }
                },
                error: function() {
                    console.log('Failed to update queue display');
                }
            });
        }
        
        function updateStatistics(stats) {
            // Update statistics cards
            $('.bg-info .inner h3').text(stats.total_tickets || 0);
            $('.bg-warning .inner h3').text(stats.waiting || 0);
            $('.bg-primary .inner h3').text(stats.called || 0);
            $('.bg-success .inner h3').text(stats.completed || 0);
        }
        
        function updateQueueTable(tickets) {
            // Update queue table without full page reload
            let tbody = $('#queueTable tbody');
            tbody.empty();
            
            tickets.forEach(function(ticket) {
                let statusColors = {
                    'waiting': 'warning',
                    'called': 'info',
                    'serving': 'primary',
                    'completed': 'success',
                    'skipped': 'secondary',
                    'cancelled': 'danger'
                };
                
                let statusColor = statusColors[ticket.status] || 'secondary';
                let actions = '';
                
                if (ticket.status === 'waiting') {
                    actions = `<button class="btn btn-sm btn-info" onclick="callTicket(${ticket.id})">
                        <i class="fas fa-bullhorn"></i> Call
                    </button>`;
                } else if (ticket.status === 'called') {
                    actions = `<button class="btn btn-sm btn-success" onclick="completeTicket(${ticket.id})">
                        <i class="fas fa-check"></i> Complete
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="skipTicket(${ticket.id})">
                        <i class="fas fa-forward"></i> Skip
                    </button>`;
                }
                
                let priorityColors = {
                    'normal': 'secondary',
                    'priority': 'warning',
                    'emergency': 'danger'
                };
                let priorityColor = priorityColors[ticket.priority] || 'secondary';
                let priorityText = (ticket.priority || 'normal').charAt(0).toUpperCase() + (ticket.priority || 'normal').slice(1);
                
                let notes = ticket.notes ? 
                    `<span class="text-muted" title="${ticket.notes}">
                        <i class="fas fa-sticky-note"></i> ${ticket.notes.length > 20 ? ticket.notes.substring(0, 20) + '...' : ticket.notes}
                    </span>` : 
                    '<span class="text-muted">-</span>';
                
                let row = `<tr>
                    <td><span class="badge bg-primary">${ticket.ticket_number}</span></td>
                    <td>${ticket.service_name}</td>
                    <td>${ticket.student_name}</td>
                    <td><span class="badge bg-${priorityColor}">${priorityText}</span></td>
                    <td><span class="badge bg-${statusColor}">${ticket.status.charAt(0).toUpperCase() + ticket.status.slice(1)}</span></td>
                    <td>${new Date(ticket.created_at).toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'})}</td>
                    <td>${notes}</td>
                    <td>${actions}</td>
                </tr>`;
                
                tbody.append(row);
            });
        }
        
        function updateCurrentDisplay(current) {
            if (current && current.ticket_number) {
                $('#currentTicket').text(current.ticket_number);
                $('#currentService').text(current.service_name);
                $('#currentDisplay').show();
                
                // Auto-hide after 10 seconds
                setTimeout(function() {
                    $('#currentDisplay').hide();
                }, 10000);
            } else {
                $('#currentDisplay').hide();
            }
        }
        
        // Initialize real-time updates
        $(document).ready(function() {
            // Initialize DataTable
            $('#queueTable').DataTable({
                "order": [[4, "desc"]],
                "pageLength": 25,
                "responsive": true,
                "autoWidth": false
            });
            
            // Real-time updates every 5 seconds
            setInterval(updateQueueDisplay, 5000);
            
            // Initial update
            updateQueueDisplay();
        });
        
        // Sound notification for new tickets
        function playNotificationSound() {
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT');
            audio.play().catch(e => console.log('Audio play failed:', e));
        }
        
        // Keyboard shortcuts
        $(document).keydown(function(e) {
            if (e.ctrlKey && e.key === 'r') {
                e.preventDefault();
                updateQueueDisplay();
            }
        });
        
        // Search and filter functionality
        function applyFilters() {
            let statusFilter = $('#statusFilter').val();
            let priorityFilter = $('#priorityFilter').val();
            let serviceFilter = $('#serviceFilter').val();
            let searchText = $('#searchInput').val().toLowerCase();
            
            $('#queueTable tbody tr').each(function() {
                let row = $(this);
                let status = row.find('td:eq(4) .badge').text().toLowerCase();
                let priority = row.find('td:eq(3) .badge').text().toLowerCase();
                let service = row.find('td:eq(1)').text().toLowerCase();
                let student = row.find('td:eq(2)').text().toLowerCase();
                let ticket = row.find('td:eq(0) .badge').text().toLowerCase();
                
                let statusMatch = !statusFilter || status === statusFilter.toLowerCase();
                let priorityMatch = !priorityFilter || priority === priorityFilter.toLowerCase();
                let serviceMatch = !serviceFilter || service.includes(serviceFilter.toLowerCase());
                let searchMatch = !searchText || 
                    status.includes(searchText) || 
                    priority.includes(searchText) || 
                    service.includes(searchText) || 
                    student.includes(searchText) || 
                    ticket.includes(searchText);
                
                if (statusMatch && priorityMatch && serviceMatch && searchMatch) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }
        
        function clearFilters() {
            $('#statusFilter').val('');
            $('#priorityFilter').val('');
            $('#serviceFilter').val('');
            $('#searchInput').val('');
            $('#queueTable tbody tr').show();
        }
        
        function clearSearch() {
            $('#searchInput').val('');
            applyFilters();
        }
        
        // Live search
        $('#searchInput').on('input', function() {
            applyFilters();
        });
        
        // Notification system
        function addNotification(message, type = 'info') {
            const notifications = $('#notifications');
            const timestamp = new Date().toLocaleTimeString();
            const icon = type === 'success' ? 'check-circle' : 
                        type === 'warning' ? 'exclamation-triangle' : 
                        type === 'error' ? 'times-circle' : 'info-circle';
            
            const notification = $(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${icon}"></i>
                    <span class="ms-2">${message}</span>
                    <small class="text-muted ms-2">${timestamp}</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            
            notifications.prepend(notification);
            
            // Keep only last 10 notifications
            if (notifications.children().length > 10) {
                notifications.children().last().remove();
            }
            
            // Auto-remove after 10 seconds
            setTimeout(() => {
                notification.alert('close');
            }, 10000);
        }
        
        function hideCurrentDisplay() {
            $('#currentDisplay').hide();
        }
        
        // Enhanced call ticket function with notifications
        function callTicket(ticketId) {
            $.ajax({
                url: '../api/update_ticket_status.php',
                type: 'POST',
                data: { ticket_id: ticketId, status: 'called' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        addNotification(`Ticket ${response.ticket_number} has been called!`, 'success');
                        playNotificationSound();
                        updateQueueDisplay();
                    } else {
                        addNotification(response.message || 'Failed to call ticket.', 'error');
                    }
                },
                error: function() {
                    addNotification('Network error occurred while calling ticket.', 'error');
                }
            });
        }
        
        // Enhanced call next ticket function
        function callNextTicket(serviceId) {
            $.ajax({
                url: '../api/call_next_ticket.php',
                type: 'POST',
                data: { service_id: serviceId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Show current display
                        document.getElementById('currentTicket').textContent = response.ticket_number;
                        document.getElementById('currentService').textContent = response.service_name;
                        document.getElementById('currentDisplay').style.display = 'block';
                        
                        addNotification(`Next ticket ${response.ticket_number} called for ${response.service_name}!`, 'success');
                        playNotificationSound();
                        
                        // Auto-hide after 15 seconds
                        setTimeout(function() {
                            $('#currentDisplay').hide();
                        }, 15000);
                        
                        updateQueueDisplay();
                    } else {
                        addNotification(response.message || 'No waiting tickets for this service.', 'info');
                    }
                },
                error: function() {
                    addNotification('Network error occurred while calling next ticket.', 'error');
                }
            });
        }
        
        // Enhanced complete ticket function
        function completeTicket(ticketId) {
            $.ajax({
                url: '../api/update_ticket_status.php',
                type: 'POST',
                data: { ticket_id: ticketId, status: 'completed' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        addNotification(`Ticket ${response.ticket_number} has been completed!`, 'success');
                        updateQueueDisplay();
                    } else {
                        addNotification(response.message || 'Failed to complete ticket.', 'error');
                    }
                },
                error: function() {
                    addNotification('Network error occurred while completing ticket.', 'error');
                }
            });
        }
        
        // Enhanced skip ticket function
        function skipTicket(ticketId) {
            $.ajax({
                url: '../api/update_ticket_status.php',
                type: 'POST',
                data: { ticket_id: ticketId, status: 'skipped' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        addNotification(`Ticket ${response.ticket_number} has been skipped!`, 'warning');
                        updateQueueDisplay();
                    } else {
                        addNotification(response.message || 'Failed to skip ticket.', 'error');
                    }
                },
                error: function() {
                    addNotification('Network error occurred while skipping ticket.', 'error');
                }
            });
        }
    </script>
</body>
</html>
