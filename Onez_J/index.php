<?php
require_once __DIR__ . '/classes/Service.php';
require_once __DIR__ . '/classes/QueueTicket.php';

$service = new Service();
$services = $service->getAllServices();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Queuing System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            padding: 2rem 0;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 3rem;
        }
        
        .header h1 {
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .admin-link {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        
        .admin-link .btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .admin-link .btn:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            transform: translateY(-2px);
        }
        
        .service-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            overflow: hidden;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .service-card .card-body {
            padding: 2rem;
            text-align: center;
        }
        
        .service-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        
        .service-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .service-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .service-duration {
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            color: #667eea;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .queue-display {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-top: 2rem;
            display: none;
        }
        
        .ticket-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .position-info {
            text-align: center;
            color: #666;
        }
        
        .btn-primary {
            background: #667eea;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }
        
        .footer {
            text-align: center;
            color: white;
            margin-top: 3rem;
            opacity: 0.8;
        }
        
        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            background: #667eea;
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
        }
        
        .modal-header .btn-close {
            filter: invert(1);
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
        }
        
        .btn-submit {
            background: #667eea;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Admin Panel Link -->
    <div class="admin-link">
        <a href="admin/login.php" class="btn">
            <i class="fas fa-user-shield"></i> Admin Panel
        </a>
    </div>

    <div class="container main-container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-clipboard-list"></i> Registrar Queuing System</h1>
            <p>Select a service to get your queue number</p>
        </div>
        
        <!-- Services Grid -->
        <div class="row g-4">
            <?php foreach ($services as $service_item): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card service-card" onclick="openQueueForm(<?= $service_item['id'] ?>, '<?= htmlspecialchars($service_item['service_name']) ?>', '<?= htmlspecialchars($service_item['service_code']) ?>')">
                    <div class="card-body">
                        <div class="service-icon">
                            <?php
                            $icons = [
                                'TOR' => 'fas fa-file-alt',
                                'ENR' => 'fas fa-user-graduate',
                                'IDV' => 'fas fa-id-card',
                                'CER' => 'fas fa-certificate',
                                'GRI' => 'fas fa-chart-line'
                            ];
                            $icon = $icons[$service_item['service_code']] ?? 'fas fa-cog';
                            ?>
                            <i class="<?= $icon ?>"></i>
                        </div>
                        <div class="service-name"><?= $service_item['service_name'] ?></div>
                        <div class="service-description"><?= $service_item['description'] ?></div>
                        <div class="service-duration">
                            <i class="fas fa-clock"></i> ~<?= $service_item['estimated_duration'] ?> minutes
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Queue Display -->
        <div class="queue-display" id="queueDisplay">
            <div class="ticket-number" id="ticketNumber"></div>
            <div class="position-info" id="positionInfo"></div>
            <div class="text-center mt-3">
                <button class="btn btn-primary" onclick="checkPosition()">
                    <i class="fas fa-sync-alt"></i> Check Position
                </button>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2024 Registrar Queuing System. All rights reserved.</p>
        </div>
    </div>

    <!-- Queue Form Modal -->
    <div class="modal fade" id="queueModal" tabindex="-1" aria-labelledby="queueModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="queueModalLabel">
                        <i class="fas fa-clipboard-list"></i> Get Queue Number
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="queueForm">
                        <input type="hidden" id="serviceId" name="service_id">
                        <input type="hidden" id="serviceName" name="service_name">
                        <input type="hidden" id="serviceCode" name="service_code">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="studentName" class="form-label">
                                        <i class="fas fa-user"></i> Full Name *
                                    </label>
                                    <input type="text" class="form-control" id="studentName" name="student_name" 
                                           placeholder="Enter your full name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="studentId" class="form-label">
                                        <i class="fas fa-id-card"></i> Student ID
                                    </label>
                                    <input type="text" class="form-control" id="studentId" name="student_id" 
                                           placeholder="Enter your student ID (optional)">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contactNumber" class="form-label">
                                        <i class="fas fa-phone"></i> Contact Number
                                    </label>
                                    <input type="tel" class="form-control" id="contactNumber" name="contact_number" 
                                           placeholder="Enter your contact number (optional)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">
                                        <i class="fas fa-exclamation-triangle"></i> Priority Level
                                    </label>
                                    <select class="form-select" id="priority" name="priority">
                                        <option value="normal">Normal</option>
                                        <option value="priority">Priority</option>
                                        <option value="emergency">Emergency</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note"></i> Additional Notes
                            </label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Any special requirements or notes (optional)"></textarea>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Service:</strong> <span id="modalServiceName"></span><br>
                            <strong>Code:</strong> <span id="modalServiceCode"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-submit btn-primary" onclick="submitQueueForm()">
                        <i class="fas fa-ticket-alt"></i> Get Queue Number
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        let currentTicketNumber = '';
        let queueModal;
        
        document.addEventListener('DOMContentLoaded', function() {
            queueModal = new bootstrap.Modal(document.getElementById('queueModal'));
        });
        
        function openQueueForm(serviceId, serviceName, serviceCode) {
            // Set form values
            document.getElementById('serviceId').value = serviceId;
            document.getElementById('serviceName').value = serviceName;
            document.getElementById('serviceCode').value = serviceCode;
            document.getElementById('modalServiceName').textContent = serviceName;
            document.getElementById('modalServiceCode').textContent = serviceCode;
            
            // Reset form
            document.getElementById('queueForm').reset();
            
            // Show modal
            queueModal.show();
        }
        
        function submitQueueForm() {
            // Get form data
            const formData = new FormData(document.getElementById('queueForm'));
            
            // Validate required fields
            if (!formData.get('student_name').trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Required Field Missing',
                    text: 'Please enter your full name.',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            // Show loading
            Swal.fire({
                title: 'Getting Queue Number...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form via AJAX
            $.ajax({
                url: 'api/create_ticket.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        currentTicketNumber = response.ticket_number;
                        showTicket(response.ticket_number, response.position, response.service_name);
                        
                        // Close modal
                        queueModal.hide();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Queue Number Generated!',
                            text: 'Your ticket number is: ' + response.ticket_number,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to generate queue number.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to connect to server. Please try again.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
        
        function showTicket(ticketNumber, position, serviceName) {
            document.getElementById('ticketNumber').textContent = ticketNumber;
            document.getElementById('positionInfo').innerHTML = `
                <p><strong>Service:</strong> ${serviceName}</p>
                <p><strong>Current Position:</strong> ${position}</p>
                <p><strong>Time:</strong> ${new Date().toLocaleTimeString()}</p>
            `;
            document.getElementById('queueDisplay').style.display = 'block';

            // Scroll to display
            document.getElementById('queueDisplay').scrollIntoView({ behavior: 'smooth' });
        }
        
        function checkPosition() {
            if (!currentTicketNumber) return;

            $.ajax({
                url: 'api/check_position.php',
                type: 'POST',
                data: { ticket_number: currentTicketNumber },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        document.getElementById('positionInfo').innerHTML = `
                            <p><strong>Service:</strong> ${response.service_name}</p>
                            <p><strong>Current Position:</strong> ${response.position}</p>
                            <p><strong>Status:</strong> ${response.status}</p>
                            <p><strong>Last Updated:</strong> ${new Date().toLocaleTimeString()}</p>
                        `;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to check position.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to connect to server. Please try again.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        // Auto-refresh position every 30 seconds
        setInterval(function() {
            if (currentTicketNumber) {
                checkPosition();
            }
        }, 30000);
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
