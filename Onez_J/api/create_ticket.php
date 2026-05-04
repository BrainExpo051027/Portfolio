<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../classes/QueueTicket.php';
require_once __DIR__ . '/../classes/Service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get POST data
$service_id = $_POST['service_id'] ?? null;
$student_name = $_POST['student_name'] ?? null;
$student_id = $_POST['student_id'] ?? '';
$contact_number = $_POST['contact_number'] ?? '';
$priority = $_POST['priority'] ?? 'normal';
$notes = $_POST['notes'] ?? '';

// Validate required fields
if (!$service_id || !$student_name) {
    echo json_encode(['success' => false, 'message' => 'Service ID and student name are required']);
    exit;
}

// Validate priority
$valid_priorities = ['normal', 'priority', 'emergency'];
if (!in_array($priority, $valid_priorities)) {
    $priority = 'normal'; // Default to normal if invalid
}

// Clean and validate input
$student_name = trim($student_name);
$student_id = trim($student_id);
$contact_number = trim($contact_number);
$notes = trim($notes);

if (strlen($student_name) < 2) {
    echo json_encode(['success' => false, 'message' => 'Student name must be at least 2 characters long']);
    exit;
}

if (strlen($student_name) > 100) {
    echo json_encode(['success' => false, 'message' => 'Student name is too long']);
    exit;
}

try {
    $queueTicket = new QueueTicket();
    $service = new Service();

    // Validate service exists and is active
    $serviceData = $service->getServiceById($service_id);
    if (!$serviceData || !$serviceData['is_active']) {
        echo json_encode(['success' => false, 'message' => 'Invalid or inactive service']);
        exit;
    }

    // Create ticket
    $result = $queueTicket->createTicket($service_id, $student_name, $student_id, $contact_number, $priority, $notes);

    if ($result) {
        // Get current position
        $position = $queueTicket->getCurrentPosition($result['ticket_number']);

        echo json_encode([
            'success' => true,
            'ticket_number' => $result['ticket_number'],
            'position' => $position,
            'service_name' => $serviceData['service_name'],
            'service_code' => $serviceData['service_code'],
            'student_name' => $student_name,
            'priority' => $priority,
            'message' => 'Ticket created successfully'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create ticket']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
