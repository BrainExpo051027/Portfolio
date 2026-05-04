<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../classes/QueueTicket.php';
require_once __DIR__ . '/../classes/User.php';

// Check if user is logged in
session_start();
$user = new User();
if (!$user->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get POST data
$service_id = $_POST['service_id'] ?? null;

// Validate required fields
if (!$service_id) {
    echo json_encode(['success' => false, 'message' => 'Service ID is required']);
    exit;
}

try {
    $queueTicket = new QueueTicket();
    
    // Call next ticket
    $result = $queueTicket->callNextTicket($service_id);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'ticket_number' => $result['ticket_number'],
            'service_name' => $result['service_name'],
            'student_name' => $result['student_name'],
            'message' => 'Next ticket called successfully'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No waiting tickets for this service']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
