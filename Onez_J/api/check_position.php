<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../classes/QueueTicket.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get POST data
$ticket_number = $_POST['ticket_number'] ?? null;

// Validate required fields
if (!$ticket_number) {
    echo json_encode(['success' => false, 'message' => 'Ticket number is required']);
    exit;
}

try {
    $queueTicket = new QueueTicket();
    
    // Get ticket information
    $ticket = $queueTicket->getTicketByNumber($ticket_number);
    
    if (!$ticket) {
        echo json_encode(['success' => false, 'message' => 'Ticket not found']);
        exit;
    }
    
    // Get current position
    $position = $queueTicket->getCurrentPosition($ticket_number);
    
    echo json_encode([
        'success' => true,
        'ticket_number' => $ticket['ticket_number'],
        'service_name' => $ticket['service_name'],
        'student_name' => $ticket['student_name'],
        'status' => $ticket['status'],
        'position' => $position,
        'created_at' => $ticket['created_at'],
        'called_at' => $ticket['called_at'],
        'completed_at' => $ticket['completed_at']
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
