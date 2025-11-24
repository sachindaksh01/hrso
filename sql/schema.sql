-- ====================================
-- HRSO Membership Management System
-- Complete Database Schema
-- ====================================

DROP DATABASE IF EXISTS hrso_membership;
CREATE DATABASE hrso_membership CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hrso_membership;

-- Table 1: Levels
CREATE TABLE levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level_name VARCHAR(50) NOT NULL UNIQUE,
    level_code VARCHAR(10) NOT NULL UNIQUE,
    priority INT NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_priority (priority)
) ENGINE=InnoDB;

INSERT INTO levels (level_name, level_code, priority) VALUES
('National', 'NAT', 1),
('State', 'STA', 2),
('District', 'DIS', 3),
('Division', 'DIV', 4),
('City', 'CIT', 5),
('Zonal', 'ZON', 6),
('Tehsil', 'TEH', 7),
('Block', 'BLK', 8),
('Circle', 'CIR', 9),
('Rural', 'RUR', 10),
('Panchayat', 'PAN', 11);

-- Table 2: Designations
CREATE TABLE designations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    designation_name VARCHAR(100) NOT NULL,
    designation_code VARCHAR(20) NOT NULL UNIQUE,
    priority INT NOT NULL,
    is_unique_per_area TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_priority (priority)
) ENGINE=InnoDB;

INSERT INTO designations (designation_name, designation_code, priority, is_unique_per_area) VALUES
('President', 'PRES', 1, 1),
('Vice President', 'VPRES', 2, 0),
('General Secretary', 'GSEC', 3, 1),
('Secretary', 'SEC', 4, 0),
('Joint Secretary', 'JSEC', 5, 0),
('Treasurer', 'TREAS', 6, 1),
('Organizing Secretary', 'OSEC', 7, 0),
('Cultural Secretary', 'CSEC', 8, 0),
('Sports Secretary', 'SSEC', 9, 0),
('Media Incharge', 'MEDIA', 10, 0),
('IT Incharge', 'IT', 11, 0),
('Legal Advisor', 'LEGAL', 12, 0),
('Coordinator', 'COORD', 13, 0),
('Executive Member', 'EXEC', 14, 0),
('Member', 'MEM', 15, 0);

-- Table 3: States
CREATE TABLE states (
    id INT AUTO_INCREMENT PRIMARY KEY,
    state_name VARCHAR(100) NOT NULL UNIQUE,
    state_code VARCHAR(10) NOT NULL UNIQUE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_code (state_code)
) ENGINE=InnoDB;

INSERT INTO states (state_name, state_code) VALUES
('Andhra Pradesh', 'AP'), ('Arunachal Pradesh', 'AR'), ('Assam', 'AS'),
('Bihar', 'BR'), ('Chhattisgarh', 'CG'), ('Goa', 'GA'),
('Gujarat', 'GJ'), ('Haryana', 'HR'), ('Himachal Pradesh', 'HP'),
('Jharkhand', 'JH'), ('Karnataka', 'KA'), ('Kerala', 'KL'),
('Madhya Pradesh', 'MP'), ('Maharashtra', 'MH'), ('Manipur', 'MN'),
('Meghalaya', 'ML'), ('Mizoram', 'MZ'), ('Nagaland', 'NL'),
('Odisha', 'OD'), ('Punjab', 'PB'), ('Rajasthan', 'RJ'),
('Sikkim', 'SK'), ('Tamil Nadu', 'TN'), ('Telangana', 'TS'),
('Tripura', 'TR'), ('Uttar Pradesh', 'UP'), ('Uttarakhand', 'UK'),
('West Bengal', 'WB'), ('Delhi', 'DL'), ('Jammu and Kashmir', 'JK'),
('Ladakh', 'LA'), ('Puducherry', 'PY'), ('Chandigarh', 'CH'),
('Dadra and Nagar Haveli and Daman and Diu', 'DD'),
('Lakshadweep', 'LD'), ('Andaman and Nicobar Islands', 'AN');

-- Table 4: Districts
CREATE TABLE districts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    state_id INT NOT NULL,
    district_name VARCHAR(100) NOT NULL,
    district_code VARCHAR(10),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE CASCADE,
    INDEX idx_state (state_id)
) ENGINE=InnoDB;

-- Table 5: Membership Plans
CREATE TABLE membership_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level_id INT NOT NULL,
    designation_id INT NOT NULL,
    donation_amount DECIMAL(10,2) NOT NULL,
    validity_years INT DEFAULT 1,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (level_id) REFERENCES levels(id) ON DELETE CASCADE,
    FOREIGN KEY (designation_id) REFERENCES designations(id) ON DELETE CASCADE,
    UNIQUE KEY unique_plan (level_id, designation_id)
) ENGINE=InnoDB;

INSERT INTO membership_plans (level_id, designation_id, donation_amount, validity_years) VALUES
(1, 1, 10000.00, 1), (1, 2, 8000.00, 1), (1, 3, 7000.00, 1),
(2, 1, 5000.00, 1), (2, 2, 4000.00, 1), (2, 15, 2000.00, 1),
(3, 1, 3000.00, 1), (3, 2, 2500.00, 1), (3, 15, 1000.00, 1);

-- Table 6: Admin Users
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15),
    role_type ENUM('super_admin', 'national_admin', 'state_admin', 'district_admin') NOT NULL,
    level_id INT NULL,
    state_id INT NULL,
    district_id INT NULL,
    is_active TINYINT(1) DEFAULT 1,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (level_id) REFERENCES levels(id) ON DELETE SET NULL,
    FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE SET NULL,
    FOREIGN KEY (district_id) REFERENCES districts(id) ON DELETE SET NULL,
    INDEX idx_role (role_type)
) ENGINE=InnoDB;

-- Default super admin (Password: Admin@123)
INSERT INTO admin_users (username, email, password_hash, full_name, role_type) VALUES
('superadmin', 'admin@hrso.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator', 'super_admin');

-- Table 7: Members
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    father_husband_name VARCHAR(100) NOT NULL,
    relation_type ENUM('Father', 'Husband', 'C/o') DEFAULT 'Father',
    date_of_birth DATE NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    email VARCHAR(100),
    govt_id_type ENUM('Aadhar', 'PAN', 'Driving License', 'Passport', 'Voter ID') NOT NULL,
    govt_id_number VARCHAR(50) NOT NULL,
    designation_id INT NOT NULL,
    level_id INT NOT NULL,
    state_id INT NULL,
    district_id INT NULL,
    tehsil VARCHAR(100),
    block VARCHAR(100),
    city VARCHAR(100),
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    pincode VARCHAR(10) NOT NULL,
    photo_path VARCHAR(255),
    signature_path VARCHAR(255),
    id_proof_path VARCHAR(255),
    membership_plan_id INT NOT NULL,
    validity_years INT DEFAULT 1,
    membership_start_date DATE NULL,
    membership_expiry_date DATE NULL,
    status ENUM('pending', 'approved', 'rejected', 'expired') DEFAULT 'pending',
    rejection_reason TEXT NULL,
    payment_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    payment_transaction_id VARCHAR(100),
    payment_date TIMESTAMP NULL,
    approved_by INT NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (designation_id) REFERENCES designations(id),
    FOREIGN KEY (level_id) REFERENCES levels(id),
    FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE SET NULL,
    FOREIGN KEY (district_id) REFERENCES districts(id) ON DELETE SET NULL,
    FOREIGN KEY (membership_plan_id) REFERENCES membership_plans(id),
    FOREIGN KEY (approved_by) REFERENCES admin_users(id) ON DELETE SET NULL,
    INDEX idx_member_id (member_id),
    INDEX idx_status (status),
    INDEX idx_mobile (mobile)
) ENGINE=InnoDB;

-- Table 8: Payments
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    payment_type ENUM('registration', 'renewal', 'donation') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    transaction_id VARCHAR(100),
    payment_gateway VARCHAR(50),
    payment_status ENUM('pending', 'success', 'failed', 'refunded') DEFAULT 'pending',
    gateway_response TEXT,
    payment_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    INDEX idx_transaction (transaction_id)
) ENGINE=InnoDB;

-- Table 9: Audit Logs
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NULL,
    table_name VARCHAR(50) NOT NULL,
    record_id INT NOT NULL,
    action_type ENUM('create', 'update', 'delete', 'approve', 'reject') NOT NULL,
    old_data TEXT,
    new_data TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE SET NULL,
    INDEX idx_admin (admin_id)
) ENGINE=InnoDB;

-- Table 10: Donations
CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    email VARCHAR(100),
    amount DECIMAL(10,2) NOT NULL,
    purpose VARCHAR(255),
    payment_transaction_id VARCHAR(100),
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    payment_date TIMESTAMP NULL,
    receipt_number VARCHAR(50) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table 11: Settings
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'json', 'boolean') DEFAULT 'text',
    description VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'HRSO India', 'text', 'Organization Name'),
('expiry_alert_days', '30', 'number', 'Days before expiry to send alert'),
('member_id_prefix', 'HRSO', 'text', 'Member ID Prefix'),
('bank_name', 'Punjab National Bank', 'text', 'Bank Name'),
('bank_account_number', '7983002100001067', 'text', 'Account Number'),
('bank_ifsc', 'PUNB0798300', 'text', 'IFSC Code');


-- Quick Schema for Testing
USE hrso_membership;

-- Levels Table
CREATE TABLE IF NOT EXISTS levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level_name VARCHAR(50) NOT NULL UNIQUE,
    level_code VARCHAR(10) NOT NULL UNIQUE,
    priority INT NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO levels (level_name, level_code, priority) VALUES
('National', 'NAT', 1),
('State', 'STA', 2),
('District', 'DIS', 3);

-- Designations Table
CREATE TABLE IF NOT EXISTS designations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    designation_name VARCHAR(100) NOT NULL,
    designation_code VARCHAR(20) NOT NULL UNIQUE,
    priority INT NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO designations (designation_name, designation_code, priority) VALUES
('President', 'PRES', 1),
('Vice President', 'VPRES', 2),
('Member', 'MEM', 15);

-- States Table
CREATE TABLE IF NOT EXISTS states (
    id INT AUTO_INCREMENT PRIMARY KEY,
    state_name VARCHAR(100) NOT NULL UNIQUE,
    state_code VARCHAR(10) NOT NULL UNIQUE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO states (state_name, state_code) VALUES
('Uttar Pradesh', 'UP'),
('Delhi', 'DL'),
('Maharashtra', 'MH');

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role_type ENUM('super_admin', 'national_admin', 'state_admin', 'district_admin') NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin (Password: Admin@123)
INSERT INTO admin_users (username, email, password_hash, full_name, role_type) VALUES
('superadmin', 'admin@hrso.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator', 'super_admin');

-- Members Table (simplified for testing)
CREATE TABLE IF NOT EXISTS members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'expired') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
