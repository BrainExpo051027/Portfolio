<?php

require_once __DIR__ . '/../config/Database.php';

class QueueTicket {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function createTicket($service_id, $student_name, $student_id = '', $contact_number = '', $priority = 'normal', $notes = '') {
        $service_id = (int)$service_id;
        $student_name = $this->db->escape($student_name);
        $student_id = $this->db->escape($student_id);
        $contact_number = $this->db->escape($contact_number);
        $priority = $this->db->escape($priority);
        $notes = $this->db->escape($notes);
        
        // Generate ticket number
        $ticket_number = $this->generateTicketNumber($service_id);
        
        $sql = "INSERT INTO queue_tickets (ticket_number, service_id, student_name, student_id, contact_number, priority, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sisssss", $ticket_number, $service_id, $student_name, $student_id, $contact_number, $priority, $notes);
        
        if ($stmt->execute()) {
            return [
                'id' => $this->db->getLastInsertId(),
                'ticket_number' => $ticket_number
            ];
        }
        
        return false;
    }
    
    private function generateTicketNumber($service_id) {
        // Get service code
        $sql = "SELECT service_code FROM services WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $service = $result->fetch_assoc();
        
        if (!$service) {
            return false;
        }
        
        $service_code = $service['service_code'];
        
        // Get current date
        $date = date('Ymd');
        
        // Get count of tickets for today for this service
        $sql = "SELECT COUNT(*) as count FROM queue_tickets 
                WHERE service_id = ? AND DATE(created_at) = CURDATE()";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $count = $row['count'] + 1;
        
        // Format: TOR-20241201-001
        return $service_code . '-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
    
    public function getTicketByNumber($ticket_number) {
        $ticket_number = $this->db->escape($ticket_number);
        
        $sql = "SELECT qt.*, s.service_name, s.service_code 
                FROM queue_tickets qt
                JOIN services s ON qt.service_id = s.id
                WHERE qt.ticket_number = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $ticket_number);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function getTicketById($id) {
        $id = (int)$id;
        
        $sql = "SELECT qt.*, s.service_name, s.service_code 
                FROM queue_tickets qt
                JOIN services s ON qt.service_id = s.id
                WHERE qt.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function getCurrentPosition($ticket_number) {
        $ticket = $this->getTicketByNumber($ticket_number);
        
        if (!$ticket) {
            return false;
        }
        
        $sql = "SELECT COUNT(*) as position FROM queue_tickets 
                WHERE service_id = ? AND status = 'waiting' AND created_at < ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is", $ticket['service_id'], $ticket['created_at']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['position'] + 1; // Position starts from 1
    }
    
    public function getQueueByService($service_id, $status = null) {
        $service_id = (int)$service_id;
        
        $sql = "SELECT qt.*, s.service_name, s.service_code 
                FROM queue_tickets qt
                JOIN services s ON qt.service_id = s.id
                WHERE qt.service_id = ?";
        
        if ($status) {
            $status = $this->db->escape($status);
            $sql .= " AND qt.status = ?";
        }
        
        $sql .= " ORDER BY qt.priority DESC, qt.created_at ASC";
        
        $stmt = $this->db->prepare($sql);
        
        if ($status) {
            $stmt->bind_param("is", $service_id, $status);
        } else {
            $stmt->bind_param("i", $service_id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $tickets = [];
        
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }
        
        return $tickets;
    }
    
    public function getAllQueues($status = null, $service_id = null) {
        $sql = "SELECT qt.*, s.service_name, s.service_code 
                FROM queue_tickets qt
                JOIN services s ON qt.service_id = s.id
                WHERE 1=1";
        
        $types = '';
        $values = [];
        
        if ($status) {
            $status = $this->db->escape($status);
            $sql .= " AND qt.status = ?";
            $types .= 's';
            $values[] = $status;
        }
        
        if ($service_id) {
            $service_id = (int)$service_id;
            $sql .= " AND qt.service_id = ?";
            $types .= 'i';
            $values[] = $service_id;
        }
        
        $sql .= " ORDER BY qt.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        
        if (!empty($values)) {
            $stmt->bind_param($types, ...$values);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $tickets = [];
        
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }
        
        return $tickets;
    }
    
    public function callNextTicket($service_id) {
        $service_id = (int)$service_id;
        
        // Get the next waiting ticket (highest priority first, then oldest)
        $sql = "SELECT id FROM queue_tickets 
                WHERE service_id = ? AND status = 'waiting'
                ORDER BY priority DESC, created_at ASC 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false; // No waiting tickets
        }
        
        $ticket = $result->fetch_assoc();
        
        // Update status to 'called'
        $sql = "UPDATE queue_tickets SET status = 'called', called_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $ticket['id']);
        
        if ($stmt->execute()) {
            return $this->getTicketById($ticket['id']);
        }
        
        return false;
    }
    
    public function updateTicketStatus($ticket_id, $status, $notes = '') {
        $ticket_id = (int)$ticket_id;
        $status = $this->db->escape($status);
        $notes = $this->db->escape($notes);
        
        $sql = "UPDATE queue_tickets SET status = ?, notes = ?";
        
        if ($status === 'completed') {
            $sql .= ", completed_at = NOW()";
        } elseif ($status === 'called') {
            $sql .= ", called_at = NOW()";
        }
        
        $sql .= " WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        
        if ($status === 'completed' || $status === 'called') {
            $stmt->bind_param("ssi", $status, $notes, $ticket_id);
        } else {
            $stmt->bind_param("ssi", $status, $notes, $ticket_id);
        }
        
        return $stmt->execute();
    }
    
    public function skipTicket($ticket_id, $notes = '') {
        return $this->updateTicketStatus($ticket_id, 'skipped', $notes);
    }
    
    public function completeTicket($ticket_id, $notes = '') {
        return $this->updateTicketStatus($ticket_id, 'completed', $notes);
    }
    
    public function cancelTicket($ticket_id, $notes = '') {
        return $this->updateTicketStatus($ticket_id, 'cancelled', $notes);
    }
    
    public function getQueueStats() {
        $sql = "SELECT 
                    COUNT(*) as total_tickets,
                    SUM(CASE WHEN status = 'waiting' THEN 1 ELSE 0 END) as waiting,
                    SUM(CASE WHEN status = 'called' THEN 1 ELSE 0 END) as called,
                    SUM(CASE WHEN status = 'serving' THEN 1 ELSE 0 END) as serving,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'skipped' THEN 1 ELSE 0 END) as skipped,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
                FROM queue_tickets 
                WHERE DATE(created_at) = CURDATE()";
        
        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }
    
    public function getTodayTickets() {
        $sql = "SELECT qt.*, s.service_name, s.service_code 
                FROM queue_tickets qt
                JOIN services s ON qt.service_id = s.id
                WHERE DATE(qt.created_at) = CURDATE()
                ORDER BY qt.created_at DESC";
        
        $result = $this->db->query($sql);
        $tickets = [];
        
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }
        
        return $tickets;
    }
    
    public function getAllTickets($limit = 50, $offset = 0, $status = null, $service_id = null) {
        $sql = "SELECT qt.*, s.service_name, s.service_code 
                FROM queue_tickets qt
                JOIN services s ON qt.service_id = s.id
                WHERE 1=1";
        
        $types = '';
        $values = [];
        
        if ($status) {
            $status = $this->db->escape($status);
            $sql .= " AND qt.status = ?";
            $types .= 's';
            $values[] = $status;
        }
        
        if ($service_id) {
            $service_id = (int)$service_id;
            $sql .= " AND qt.service_id = ?";
            $types .= 'i';
            $values[] = $service_id;
        }
        
        $sql .= " ORDER BY qt.created_at DESC LIMIT ? OFFSET ?";
        $types .= 'ii';
        $values[] = (int)$limit;
        $values[] = (int)$offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
        $result = $stmt->get_result();
        $tickets = [];
        
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }
        
        return $tickets;
    }
    
    public function getTotalTickets($status = null, $service_id = null) {
        $sql = "SELECT COUNT(*) as total FROM queue_tickets qt WHERE 1=1";
        
        $types = '';
        $values = [];
        
        if ($status) {
            $status = $this->db->escape($status);
            $sql .= " AND qt.status = ?";
            $types .= 's';
            $values[] = $status;
        }
        
        if ($service_id) {
            $service_id = (int)$service_id;
            $sql .= " AND qt.service_id = ?";
            $types .= 'i';
            $values[] = $service_id;
        }
        
        $stmt = $this->db->prepare($sql);
        
        if (!empty($values)) {
            $stmt->bind_param($types, ...$values);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }
    
    public function getTicketsByStatus($status) {
        return $this->getTotalTickets($status);
    }
    
    public function getQueueStatsByDateRange($start_date, $end_date) {
        $start_date = $this->db->escape($start_date);
        $end_date = $this->db->escape($end_date);
        
        $sql = "SELECT 
                    COUNT(*) as total_tickets,
                    SUM(CASE WHEN status = 'waiting' THEN 1 ELSE 0 END) as waiting,
                    SUM(CASE WHEN status = 'called' THEN 1 ELSE 0 END) as called,
                    SUM(CASE WHEN status = 'serving' THEN 1 ELSE 0 END) as serving,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'skipped' THEN 1 ELSE 0 END) as skipped,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                    AVG(CASE WHEN status = 'completed' AND called_at IS NOT NULL 
                        THEN TIMESTAMPDIFF(MINUTE, created_at, called_at) 
                        ELSE NULL END) as average_wait_time,
                    ROUND((SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as completion_rate
                FROM queue_tickets 
                WHERE DATE(created_at) BETWEEN ? AND ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function getDailyStats($start_date, $end_date) {
        $start_date = $this->db->escape($start_date);
        $end_date = $this->db->escape($end_date);
        
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as total_tickets,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'waiting' THEN 1 ELSE 0 END) as waiting
                FROM queue_tickets 
                WHERE DATE(created_at) BETWEEN ? AND ?
                GROUP BY DATE(created_at)
                ORDER BY DATE(created_at)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats = [];
        
        while ($row = $result->fetch_assoc()) {
            $stats[] = $row;
        }
        
        return $stats;
    }
    
    public function getServiceStats($start_date, $end_date) {
        $start_date = $this->db->escape($start_date);
        $end_date = $this->db->escape($end_date);
        
        $sql = "SELECT 
                    s.service_name,
                    COUNT(qt.id) as ticket_count,
                    AVG(CASE WHEN qt.status = 'completed' AND qt.called_at IS NOT NULL 
                        THEN TIMESTAMPDIFF(MINUTE, qt.created_at, qt.called_at) 
                        ELSE NULL END) as avg_wait_time,
                    ROUND((SUM(CASE WHEN qt.status = 'completed' THEN 1 ELSE 0 END) / COUNT(qt.id)) * 100, 2) as completion_rate
                FROM services s
                LEFT JOIN queue_tickets qt ON s.id = qt.service_id 
                    AND DATE(qt.created_at) BETWEEN ? AND ?
                GROUP BY s.id, s.service_name
                ORDER BY ticket_count DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats = [];
        
        while ($row = $result->fetch_assoc()) {
            $stats[] = [
                'service_name' => $row['service_name'],
                'ticket_count' => (int)$row['ticket_count'],
                'avg_wait_time' => $row['avg_wait_time'] ? round($row['avg_wait_time']) : 0,
                'completion_rate' => $row['completion_rate'] ?: 0
            ];
        }
        
        return $stats;
    }
}
