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
$ticket_id = $_POST['ticket_id'] ?? null;
$status = $_POST['status'] ?? null;
$notes = $_POST['notes'] ?? '';

// Validate required fields
if (!$ticket_id || !$status) {
    echo json_encode(['success' => false, 'message' => 'Ticket ID and status are required']);
    exit;
}

// Validate status
$valid_statuses = ['waiting', 'called', 'serving', 'completed', 'skipped', 'cancelled'];
if (!in_array($status, $valid_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

try {
    $queueTicket = new QueueTicket();
    
    // Update ticket status
    $result = $queueTicket->updateTicketStatus($ticket_id, $status, $notes);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Ticket status updated successfully'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update ticket status']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
