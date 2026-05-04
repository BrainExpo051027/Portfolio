<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../classes/QueueTicket.php';
require_once __DIR__ . '/../classes/Service.php';

try {
    $queueTicket = new QueueTicket();
    $service = new Service();
    
    // Get current queue statistics
    $stats = $queueTicket->getQueueStats();
    
    // Get today's tickets
    $todayTickets = $queueTicket->getTodayTickets();
    
    // Get currently called ticket (if any)
    $currentTicket = null;
    foreach ($todayTickets as $ticket) {
        if ($ticket['status'] === 'called') {
            $currentTicket = $ticket;
            break;
        }
    }
    
    // Format tickets for display
    $formattedTickets = [];
    foreach ($todayTickets as $ticket) {
        $formattedTickets[] = [
            'id' => $ticket['id'],
            'ticket_number' => $ticket['ticket_number'],
            'service_name' => $ticket['service_name'],
            'student_name' => $ticket['student_name'],
            'status' => $ticket['status'],
            'created_at' => $ticket['created_at'],
            'priority' => $ticket['priority'] ?? 'normal',
            'notes' => $ticket['notes'] ?? ''
        ];
    }
    
    echo json_encode([
        'success' => true,
        'stats' => $stats,
        'tickets' => $formattedTickets,
        'current' => $currentTicket,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
