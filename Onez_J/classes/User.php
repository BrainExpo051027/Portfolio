<?php

require_once __DIR__ . '/../config/Database.php';

class User {
    private $db;
    private $id;
    private $username;
    private $full_name;
    private $email;
    private $role;
    private $is_active;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function login($username, $password) {
        $username = $this->db->escape($username);
        
        $sql = "SELECT id, username, password, full_name, email, role, is_active 
                FROM users 
                WHERE username = ? AND is_active = 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Plain text password comparison
            if ($password === $user['password']) {
                $this->id = $user['id'];
                $this->username = $user['username'];
                $this->full_name = $user['full_name'];
                $this->email = $user['email'];
                $this->role = $user['role'];
                $this->is_active = $user['is_active'];
                
                // Start session and store user data
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $this->id;
                $_SESSION['username'] = $this->username;
                $_SESSION['full_name'] = $this->full_name;
                $_SESSION['role'] = $this->role;
                
                return true;
            }
        }
        
        return false;
    }
    
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        return true;
    }
    
    public function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }
    
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'full_name' => $_SESSION['full_name'] ?? null,
            'role' => $_SESSION['role'] ?? null
        ];
    }
    
    public function hasRole($role) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        return ($_SESSION['role'] ?? null) === $role;
    }
    
    public function createUser($username, $password, $full_name, $email, $role = 'registrar') {
        $username = $this->db->escape($username);
        $full_name = $this->db->escape($full_name);
        $email = $this->db->escape($email);
        $role = $this->db->escape($role);
        
        // Store password as plain text (⚠️ insecure, but as requested)
        $sql = "INSERT INTO users (username, password, full_name, email, role) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssss", $username, $password, $full_name, $email, $role);
        
        return $stmt->execute();
    }
    
    public function updateUser($id, $data) {
        $id = (int)$id;
        $updates = [];
        $types = '';
        $values = [];
        
        if (isset($data['full_name'])) {
            $updates[] = "full_name = ?";
            $types .= 's';
            $values[] = $this->db->escape($data['full_name']);
        }
        
        if (isset($data['email'])) {
            $updates[] = "email = ?";
            $types .= 's';
            $values[] = $this->db->escape($data['email']);
        }
        
        if (isset($data['role'])) {
            $updates[] = "role = ?";
            $types .= 's';
            $values[] = $this->db->escape($data['role']);
        }
        
        if (isset($data['is_active'])) {
            $updates[] = "is_active = ?";
            $types .= 'i';
            $values[] = (int)$data['is_active'];
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
        $types .= 'i';
        $values[] = $id;
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }
    
    public function getAllUsers() {
        $sql = "SELECT id, username, full_name, email, role, is_active, created_at 
                FROM users 
                ORDER BY created_at DESC";
        
        $result = $this->db->query($sql);
        $users = [];
        
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        return $users;
    }
    
    public function getUserById($id) {
        $id = (int)$id;
        
        $sql = "SELECT id, username, full_name, email, role, is_active, created_at 
                FROM users 
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function deleteUser($id) {
        $id = (int)$id;
        
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
}
