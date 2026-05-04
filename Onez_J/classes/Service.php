<?php

require_once __DIR__ . '/../config/Database.php';

class Service {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAllServices($active_only = true) {
        $sql = "SELECT id, service_name, service_code, description, estimated_duration, is_active, created_at 
                FROM services";
        
        if ($active_only) {
            $sql .= " WHERE is_active = 1";
        }
        
        $sql .= " ORDER BY service_name ASC";
        
        $result = $this->db->query($sql);
        $services = [];
        
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        
        return $services;
    }
    
    public function getServiceById($id) {
        $id = (int)$id;
        
        $sql = "SELECT id, service_name, service_code, description, estimated_duration, is_active, created_at 
                FROM services 
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function getServiceByCode($code) {
        $code = $this->db->escape($code);
        
        $sql = "SELECT id, service_name, service_code, description, estimated_duration, is_active, created_at 
                FROM services 
                WHERE service_code = ? AND is_active = 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function createService($service_name, $service_code, $description = '', $estimated_duration = 15) {
        $service_name = $this->db->escape($service_name);
        $service_code = $this->db->escape($service_code);
        $description = $this->db->escape($description);
        $estimated_duration = (int)$estimated_duration;
        
        $sql = "INSERT INTO services (service_name, service_code, description, estimated_duration) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssi", $service_name, $service_code, $description, $estimated_duration);
        
        return $stmt->execute();
    }
    
    public function updateService($id, $data) {
        $id = (int)$id;
        $updates = [];
        $types = '';
        $values = [];
        
        if (isset($data['service_name'])) {
            $updates[] = "service_name = ?";
            $types .= 's';
            $values[] = $this->db->escape($data['service_name']);
        }
        
        if (isset($data['service_code'])) {
            $updates[] = "service_code = ?";
            $types .= 's';
            $values[] = $this->db->escape($data['service_code']);
        }
        
        if (isset($data['description'])) {
            $updates[] = "description = ?";
            $types .= 's';
            $values[] = $this->db->escape($data['description']);
        }
        
        if (isset($data['estimated_duration'])) {
            $updates[] = "estimated_duration = ?";
            $types .= 'i';
            $values[] = (int)$data['estimated_duration'];
        }
        
        if (isset($data['is_active'])) {
            $updates[] = "is_active = ?";
            $types .= 'i';
            $values[] = (int)$data['is_active'];
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $sql = "UPDATE services SET " . implode(", ", $updates) . " WHERE id = ?";
        $types .= 'i';
        $values[] = $id;
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }
    
    public function deleteService($id) {
        $id = (int)$id;
        
        // Check if service has active tickets
        $sql = "SELECT COUNT(*) as count FROM queue_tickets WHERE service_id = ? AND status IN ('waiting', 'called', 'serving')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            return false; // Cannot delete service with active tickets
        }
        
        $sql = "DELETE FROM services WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    public function toggleServiceStatus($id) {
        $id = (int)$id;
        
        $sql = "UPDATE services SET is_active = NOT is_active WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    public function getServiceStats($service_id = null) {
        if ($service_id) {
            $service_id = (int)$service_id;
            $sql = "SELECT 
                        s.service_name,
                        COUNT(qt.id) as total_tickets,
                        SUM(CASE WHEN qt.status = 'waiting' THEN 1 ELSE 0 END) as waiting,
                        SUM(CASE WHEN qt.status = 'called' THEN 1 ELSE 0 END) as called,
                        SUM(CASE WHEN qt.status = 'serving' THEN 1 ELSE 0 END) as serving,
                        SUM(CASE WHEN qt.status = 'completed' THEN 1 ELSE 0 END) as completed,
                        AVG(CASE WHEN qt.status = 'completed' THEN TIMESTAMPDIFF(MINUTE, qt.created_at, qt.completed_at) END) as avg_duration
                    FROM services s
                    LEFT JOIN queue_tickets qt ON s.id = qt.service_id
                    WHERE s.id = ?
                    GROUP BY s.id, s.service_name";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $service_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_assoc();
        } else {
            $sql = "SELECT 
                        s.service_name,
                        COUNT(qt.id) as total_tickets,
                        SUM(CASE WHEN qt.status = 'waiting' THEN 1 ELSE 0 END) as waiting,
                        SUM(CASE WHEN qt.status = 'called' THEN 1 ELSE 0 END) as called,
                        SUM(CASE WHEN qt.status = 'serving' THEN 1 ELSE 0 END) as serving,
                        SUM(CASE WHEN qt.status = 'completed' THEN 1 ELSE 0 END) as completed
                    FROM services s
                    LEFT JOIN queue_tickets qt ON s.id = qt.service_id
                    WHERE s.is_active = 1
                    GROUP BY s.id, s.service_name
                    ORDER BY s.service_name";
            
            $result = $this->db->query($sql);
            $stats = [];
            
            while ($row = $result->fetch_assoc()) {
                $stats[] = $row;
            }
            
            return $stats;
        }
    }
}
