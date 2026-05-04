-- Database schema for Data Table project
-- Created: 2024

-- Create database
CREATE DATABASE IF NOT EXISTS blk7;
USE blk7;

-- Create users table with improved structure
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    position VARCHAR(100) NOT NULL,
    office VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    start_date DATE NOT NULL,
    salary DECIMAL(10,2) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data based on the existing data
INSERT INTO users (name, password, position, office, age, start_date, salary, email, phone) VALUES
('Airi Satou', 'password123', 'Accountant', 'Tokyo', 33, '2008-11-28', 162700.00, 'airi.satou@company.com', '+81-3-1234-5678'),
('Angelica Ramos', 'password123', 'Chief Executive Officer (CEO)', 'London', 47, '2009-10-09', 1200000.00, 'angelica.ramos@company.com', '+44-20-1234-5678'),
('Ashton Cox', 'password123', 'Junior Technical Author', 'San Francisco', 47, '2009-01-12', 86000.00, 'ashton.cox@company.com', '+1-415-123-4567'),
('Bradley Greer', 'password123', 'Software Engineer', 'London', 41, '2012-10-13', 132000.00, 'bradley.greer@company.com', '+44-20-2345-6789'),
('Brenden Wagner', 'password123', 'Software Engineer', 'San Francisco', 28, '2011-06-07', 206850.00, 'brenden.wagner@company.com', '+1-415-234-5678'),
('Brielle Williamson', 'password123', 'Integration Specialist', 'New York', 61, '2012-12-02', 372000.00, 'brielle.williamson@company.com', '+1-212-345-6789'),
('Bruno Nash', 'password123', 'Software Engineer', 'London', 38, '2011-05-03', 163500.00, 'bruno.nash@company.com', '+44-20-3456-7890'),
('Caesar Vance', 'password123', 'Pre-Sales Support', 'New York', 21, '2011-12-12', 106450.00, 'caesar.vance@company.com', '+1-212-456-7890'),
('Cara Stevens', 'password123', 'Sales Assistant', 'New York', 46, '2011-12-06', 145600.00, 'cara.stevens@company.com', '+1-212-567-8901'),
('Cedric Kelly', 'password123', 'Senior Javascript Developer', 'Edinburgh', 22, '2012-03-29', 433060.00, 'cedric.kelly@company.com', '+44-131-456-7890'),
('Charde Marshall', 'password123', 'Regional Director', 'San Francisco', 36, '2008-10-16', 470600.00, 'charde.marshall@company.com', '+1-415-345-6789'),
('Colleen Hurst', 'password123', 'Javascript Developer', 'San Francisco', 39, '2009-09-15', 205500.00, 'colleen.hurst@company.com', '+1-415-456-7890'),
('Dai Rios', 'password123', 'Personnel Lead', 'Edinburgh', 35, '2012-09-26', 217500.00, 'dai.rios@company.com', '+44-131-567-8901'),
('Donna Snider', 'password123', 'Customer Support', 'New York', 27, '2011-01-25', 112000.00, 'donna.snider@company.com', '+1-212-678-9012'),
('Doris Wilder', 'password123', 'Sales Assistant', 'Sydney', 23, '2010-09-20', 85600.00, 'doris.wilder@company.com', '+61-2-1234-5678'),
('Finn Camacho', 'password123', 'Support Engineer', 'San Francisco', 47, '2009-07-07', 87500.00, 'finn.camacho@company.com', '+1-415-567-8901'),
('Fiona Green', 'password123', 'Chief Operating Officer (COO)', 'San Francisco', 48, '2010-03-11', 850000.00, 'fiona.green@company.com', '+1-415-678-9012'),
('Garrett Winters', 'password123', 'Accountant', 'Tokyo', 63, '2011-07-25', 170750.00, 'garrett.winters@company.com', '+81-3-2345-6789'),
('Gavin Cortez', 'password123', 'Team Leader', 'San Francisco', 22, '2008-10-26', 235500.00, 'gavin.cortez@company.com', '+1-415-789-0123'),
('Gavin Joyce', 'password123', 'Developer', 'Edinburgh', 42, '2010-12-22', 92575.00, 'gavin.joyce@company.com', '+44-131-678-9012'),
('Gloria Little', 'password123', 'Systems Administrator', 'New York', 59, '2009-04-10', 237500.00, 'gloria.little@company.com', '+1-212-789-0123'),
('Haley Kennedy', 'password123', 'Senior Marketing Designer', 'London', 43, '2012-12-18', 313500.00, 'haley.kennedy@company.com', '+44-20-4567-8901'),
('Hermione Butler', 'password123', 'Regional Director', 'London', 47, '2011-03-21', 356250.00, 'hermione.butler@company.com', '+44-20-5678-9012'),
('Herrod Chandler', 'password123', 'Sales Assistant', 'San Francisco', 59, '2012-08-06', 137500.00, 'herrod.chandler@company.com', '+1-415-890-1234'),
('Hope Fuentes', 'password123', 'Secretary', 'San Francisco', 41, '2010-02-12', 109850.00, 'hope.fuentes@company.com', '+1-415-901-2345'),
('Howard Hatfield', 'password123', 'Office Manager', 'San Francisco', 51, '2008-12-16', 164500.00, 'howard.hatfield@company.com', '+1-415-012-3456'),
('Jackson Bradshaw', 'password123', 'Director', 'New York', 65, '2008-09-26', 645750.00, 'jackson.bradshaw@company.com', '+1-212-123-4567'),
('Jena Gaines', 'password123', 'Office Manager', 'London', 30, '2008-12-19', 90560.00, 'jena.gaines@company.com', '+44-20-6789-0123'),
('Jenette Caldwell', 'password123', 'Development Lead', 'New York', 30, '2011-09-03', 345000.00, 'jenette.caldwell@company.com', '+1-212-234-5678'),
('Jennifer Acosta', 'password123', 'Junior Javascript Developer', 'Edinburgh', 43, '2013-02-01', 75650.00, 'jennifer.acosta@company.com', '+44-131-789-0123'),
('Jennifer Chang', 'password123', 'Regional Director', 'Singapore', 28, '2010-11-14', 357650.00, 'jennifer.chang@company.com', '+65-6123-4567'),
('Jonas Alexander', 'password123', 'Developer', 'San Francisco', 30, '2010-07-14', 86500.00, 'jonas.alexander@company.com', '+1-415-123-4567'),
('Lael Greer', 'password123', 'Systems Administrator', 'London', 21, '2009-02-27', 103500.00, 'lael.greer@company.com', '+44-20-7890-1234'),
('Martena Mccray', 'password123', 'Post-Sales support', 'Edinburgh', 46, '2011-03-09', 324050.00, 'martena.mccray@company.com', '+44-131-890-1234'),
('Michael Bruce', 'password123', 'Javascript Developer', 'Singapore', 29, '2011-06-27', 183000.00, 'michael.bruce@company.com', '+65-7234-5678'),
('Michael Silva', 'password123', 'Marketing Designer', 'London', 66, '2012-11-27', 198500.00, 'michael.silva@company.com', '+44-20-8901-2345'),
('Michelle House', 'password123', 'Integration Specialist', 'Sydney', 37, '2011-06-02', 95400.00, 'michelle.house@company.com', '+61-2-2345-6789'),
('Olivia Liang', 'password123', 'Support Engineer', 'Singapore', 64, '2011-02-03', 234500.00, 'olivia.liang@company.com', '+65-8345-6789'),
('Paul Byrd', 'password123', 'Chief Financial Officer (CFO)', 'New York', 64, '2010-06-09', 725000.00, 'paul.byrd@company.com', '+1-212-345-6789'),
('Prescott Bartlett', 'password123', 'Technical Author', 'London', 27, '2011-05-07', 145000.00, 'prescott.bartlett@company.com', '+44-20-9012-3456'),
('Quinn Flynn', 'password123', 'Support Lead', 'Edinburgh', 22, '2013-03-03', 342000.00, 'quinn.flynn@company.com', '+44-131-901-2345'),
('Rhona Davidson', 'password123', 'Integration Specialist', 'Tokyo', 55, '2010-10-14', 327900.00, 'rhona.davidson@company.com', '+81-3-3456-7890'),
('Sakura Yamamoto', 'password123', 'Support Engineer', 'Tokyo', 37, '2009-08-19', 139575.00, 'sakura.yamamoto@company.com', '+81-3-4567-8901'),
('Serge Baldwin', 'password123', 'Data Coordinator', 'Singapore', 64, '2012-04-09', 138575.00, 'serge.baldwin@company.com', '+65-9456-7890'),
('Shad Decker', 'password123', 'Regional Director', 'Edinburgh', 51, '2008-11-13', 183000.00, 'shad.decker@company.com', '+44-131-012-3456'),
('Shou Itou', 'password123', 'Regional Marketing', 'Tokyo', 20, '2011-08-14', 163000.00, 'shou.itou@company.com', '+81-3-5678-9012'),
('Sonya Frost', 'password123', 'Software Engineer', 'Edinburgh', 23, '2008-12-13', 103600.00, 'sonya.frost@company.com', '+44-131-123-4567'),
('Suki Burks', 'password123', 'Developer', 'London', 53, '2009-10-22', 114500.00, 'suki.burks@company.com', '+44-20-0123-4567'),
('Tatyana Fitzpatrick', 'password123', 'Regional Director', 'London', 19, '2010-03-17', 385750.00, 'tatyana.fitzpatrick@company.com', '+44-20-1234-5678'),
('Thor Walton', 'password123', 'Developer', 'New York', 61, '2013-08-11', 98540.00, 'thor.walton@company.com', '+1-212-456-7890'),
('Tiger Nixon', 'password123', 'System Architect', 'Edinburgh', 61, '2011-04-25', 320800.00, 'tiger.nixon@company.com', '+44-131-234-5678'),
('Timothy Mooney', 'password123', 'Office Manager', 'London', 37, '2008-12-11', 136200.00, 'timothy.mooney@company.com', '+44-20-2345-6789'),
('Unity Butler', 'password123', 'Marketing Designer', 'San Francisco', 47, '2009-12-09', 85675.00, 'unity.butler@company.com', '+1-415-345-6789'),
('Vivian Harrell', 'password123', 'Financial Controller', 'San Francisco', 62, '2009-02-14', 452500.00, 'vivian.harrell@company.com', '+1-415-456-7890'),
('Yuri Berry', 'password123', 'Chief Marketing Officer (CMO)', 'New York', 40, '2009-06-25', 675000.00, 'yuri.berry@company.com', '+1-212-567-8901'),
('Zenaida Frank', 'password123', 'Software Engineer', 'New York', 63, '2010-01-04', 125250.00, 'zenaida.frank@company.com', '+1-212-678-9012'),
('Zorita Serrano', 'password123', 'Software Engineer', 'San Francisco', 56, '2012-06-01', 115000.00, 'zorita.serrano@company.com', '+1-415-567-8901');

-- Create indexes for better performance
CREATE INDEX idx_name ON users(name);
CREATE INDEX idx_position ON users(position);
CREATE INDEX idx_office ON users(office);
CREATE INDEX idx_status ON users(status);

-- Create a view for dashboard statistics
CREATE VIEW dashboard_stats AS
SELECT 
    COUNT(*) as total_users,
    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_users,
    AVG(salary) as avg_salary,
    MAX(salary) as max_salary,
    MIN(salary) as min_salary,
    SUM(salary) as total_salary
FROM users;

