-- Registrar Queuing System Database Schema
-- Database: onez

DROP DATABASE IF EXISTS onez;
        CREATE DATABASE IF NOT EXISTS onez;
USE onez;

-- Users table (Admin/Registrar accounts)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('admin', 'registrar') DEFAULT 'registrar',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Services table (Available services for queuing)
CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    service_name VARCHAR(100) NOT NULL,
    service_code VARCHAR(10) UNIQUE NOT NULL,
    description TEXT,
    estimated_duration INT DEFAULT 15, -- in minutes
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Queue tickets table
CREATE TABLE queue_tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_number VARCHAR(20) UNIQUE NOT NULL,
    service_id INT NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    student_id VARCHAR(50),
    contact_number VARCHAR(20),
    status ENUM('waiting', 'called', 'serving', 'completed', 'skipped', 'cancelled') DEFAULT 'waiting',
    priority ENUM('normal', 'priority', 'emergency') DEFAULT 'normal',
    notes TEXT,
    called_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, full_name, email, role) VALUES 
('admin', 'admin123', 'System Administrator', 'admin@registrar.edu', 'admin');

-- Insert default services
INSERT INTO services (service_name, service_code, description, estimated_duration) VALUES 
('Transcript of Records (TOR)', 'TOR', 'Request for official transcript of records', 20),
('Enrollment', 'ENR', 'Student enrollment and registration', 30),
('ID Validation', 'IDV', 'Student ID validation and replacement', 15),
('Certificate Request', 'CER', 'Request for various certificates', 25),
('Grade Inquiry', 'GRI', 'Grade verification and inquiry', 10);

-- Create indexes for better performance
CREATE INDEX idx_queue_tickets_status ON queue_tickets(status);
CREATE INDEX idx_queue_tickets_service_id ON queue_tickets(service_id);
CREATE INDEX idx_queue_tickets_created_at ON queue_tickets(created_at);
CREATE INDEX idx_queue_tickets_ticket_number ON queue_tickets(ticket_number);
