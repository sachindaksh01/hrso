<?php
/**
 * Database Class with Auto-Setup
 * Automatically creates database and tables if not exists
 */

class Database {
    private $host = DB_HOST;
    private $dbname = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $charset = DB_CHARSET;
    
    private $pdo;
    private $error;
    
    public function __construct() {
        // Step 1: Create database if not exists
        if (AUTO_CREATE_DATABASE) {
            $this->createDatabaseIfNotExists();
        }
        
        // Step 2: Connect to database
        $this->connect();
        
        // Step 3: Create tables if not exists
        if (AUTO_CREATE_TABLES) {
            $this->createTablesIfNotExists();
        }
    }
    
    /**
     * Create database if not exists
     */
    private function createDatabaseIfNotExists() {
        try {
            // Connect without database name
            $dsn = "mysql:host={$this->host};charset={$this->charset}";
            $options = unserialize(PDO_OPTIONS);
            $tempPdo = new PDO($dsn, $this->username, $this->password, $options);
            
            // Check if database exists
            $stmt = $tempPdo->query("SHOW DATABASES LIKE '{$this->dbname}'");
            
            if ($stmt->rowCount() == 0) {
                // Database doesn't exist, create it
                $sql = "CREATE DATABASE IF NOT EXISTS `{$this->dbname}` 
                        CHARACTER SET utf8mb4 
                        COLLATE utf8mb4_unicode_ci";
                $tempPdo->exec($sql);
                
                if (ENVIRONMENT === 'development') {
                    echo "<div style='padding:10px; background:#d4edda; color:#155724; margin:10px; border-radius:5px;'>
                          ✅ Database '{$this->dbname}' created successfully!
                          </div>";
                }
            }
            
            $tempPdo = null; // Close connection
            
        } catch (PDOException $e) {
            $this->handleConnectionError($e);
        }
    }
    
    /**
     * Connect to database
     */
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $options = unserialize(PDO_OPTIONS);
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch (PDOException $e) {
            $this->handleConnectionError($e);
        }
    }
    
    /**
     * Create tables if not exists
     */
    private function createTablesIfNotExists() {
        try {
            // Check if tables exist
            $stmt = $this->pdo->query("SHOW TABLES");
            $existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // If no tables exist, create schema
            if (count($existingTables) == 0) {
                $this->createSchema();
                
                if (ENVIRONMENT === 'development') {
                    echo "<div style='padding:10px; background:#d4edda; color:#155724; margin:10px; border-radius:5px;'>
                          ✅ Database tables created successfully!
                          </div>";
                }
            }
            
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
        }
    }
    
    /**
     * Create complete database schema
     */
    private function createSchema() {
        // SQL Schema
        $schema = "
        -- Levels Table
        CREATE TABLE IF NOT EXISTS levels (
            id INT AUTO_INCREMENT PRIMARY KEY,
            level_name VARCHAR(50) NOT NULL UNIQUE,
            level_code VARCHAR(10) NOT NULL UNIQUE,
            priority INT NOT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_priority (priority)
        ) ENGINE=InnoDB;
        
        -- Designations Table
        CREATE TABLE IF NOT EXISTS designations (
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
        
        -- States Table
        CREATE TABLE IF NOT EXISTS states (
            id INT AUTO_INCREMENT PRIMARY KEY,
            state_name VARCHAR(100) NOT NULL UNIQUE,
            state_code VARCHAR(10) NOT NULL UNIQUE,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_code (state_code)
        ) ENGINE=InnoDB;
        
        -- Districts Table
        CREATE TABLE IF NOT EXISTS districts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            state_id INT NOT NULL,
            district_name VARCHAR(100) NOT NULL,
            district_code VARCHAR(10),
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE CASCADE,
            INDEX idx_state (state_id)
        ) ENGINE=InnoDB;
        
        -- Membership Plans Table
        CREATE TABLE IF NOT EXISTS membership_plans (
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
        
        -- Admin Users Table
        CREATE TABLE IF NOT EXISTS admin_users (
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
        
        -- Members Table
        CREATE TABLE IF NOT EXISTS members (
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
        
        -- Payments Table
        CREATE TABLE IF NOT EXISTS payments (
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
        
        -- Audit Logs Table
        CREATE TABLE IF NOT EXISTS audit_logs (
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
        
        -- Donations Table
        CREATE TABLE IF NOT EXISTS donations (
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
        
        -- Settings Table
        CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT,
            setting_type ENUM('text', 'number', 'json', 'boolean') DEFAULT 'text',
            description VARCHAR(255),
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
        
        -- Permissions Table
        CREATE TABLE IF NOT EXISTS permissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            permission_name VARCHAR(100) NOT NULL UNIQUE,
            permission_slug VARCHAR(100) NOT NULL UNIQUE,
            module VARCHAR(50) NOT NULL,
            description VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
        
        -- Role Permissions Table
        CREATE TABLE IF NOT EXISTS role_permissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            role_type ENUM('super_admin', 'national_admin', 'state_admin', 'district_admin') NOT NULL,
            permission_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
            UNIQUE KEY unique_role_permission (role_type, permission_id)
        ) ENGINE=InnoDB;
        ";
        
        // Execute schema
        $this->pdo->exec($schema);
        
        // Insert default data
        $this->insertDefaultData();
    }
    
    /**
     * Insert default data
     */
    private function insertDefaultData() {
        // Check if data already exists
        $check = $this->pdo->query("SELECT COUNT(*) FROM levels")->fetchColumn();
        
        if ($check == 0) {
            // Insert levels
            $this->pdo->exec("
                INSERT INTO levels (level_name, level_code, priority) VALUES
                ('National', 'NAT', 1),
                ('State', 'STA', 2),
                ('District', 'DIS', 3),
                ('Division', 'DIV', 4),
                ('City', 'CIT', 5),
                ('Tehsil', 'TEH', 6)
            ");
            
            // Insert designations
            $this->pdo->exec("
                INSERT INTO designations (designation_name, designation_code, priority, is_unique_per_area) VALUES
                ('President', 'PRES', 1, 1),
                ('Vice President', 'VPRES', 2, 0),
                ('General Secretary', 'GSEC', 3, 1),
                ('Secretary', 'SEC', 4, 0),
                ('Treasurer', 'TREAS', 6, 1),
                ('Member', 'MEM', 15, 0)
            ");
            
            // Insert states
            $this->pdo->exec("
                INSERT INTO states (state_name, state_code) VALUES
                ('Uttar Pradesh', 'UP'),
                ('Delhi', 'DL'),
                ('Maharashtra', 'MH'),
                ('Karnataka', 'KA'),
                ('Gujarat', 'GJ')
            ");
            
            // Insert default admin (Password: Admin@123)
            $this->pdo->exec("
                INSERT INTO admin_users (username, email, password_hash, full_name, role_type) VALUES
                ('superadmin', 'admin@hrso.org', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator', 'super_admin')
            ");
            
            // Insert settings
            $this->pdo->exec("
                INSERT INTO settings (setting_key, setting_value, setting_type) VALUES
                ('site_name', 'HRSO India', 'text'),
                ('member_id_prefix', 'HRSO', 'text')
            ");
        }
    }
    
    /**
     * Handle connection error
     */
    private function handleConnectionError($e) {
        $this->error = $e->getMessage();
        $this->logError($e->getMessage());
        
        if (ENVIRONMENT === 'development') {
            $errorCode = $e->getCode();
            $errorMsg = $e->getMessage();
            
            echo "<div style='font-family:Arial; padding:20px; background:#f8d7da; color:#721c24; margin:20px; border-radius:5px;'>";
            echo "<h2>❌ Database Connection Error</h2>";
            echo "<p><strong>Error Code:</strong> {$errorCode}</p>";
            echo "<p><strong>Message:</strong> {$errorMsg}</p>";
            echo "<hr>";
            echo "<h3>Troubleshooting Steps:</h3>";
            
            if (strpos($errorMsg, 'authentication method unknown') !== false || 
                strpos($errorMsg, 'auth_gssapi_client') !== false ||
                strpos($errorMsg, '2054') !== false) {
                echo "<div style='background:#fff3cd; padding:15px; margin:10px 0; border-left:4px solid #ffc107;'>";
                echo "<strong>MySQL Authentication Error Detected!</strong><br><br>";
                echo "Run these commands in MySQL console:<br>";
                echo "ode stylele='background:#333; color:#0f0; padding:10px; display:block; margin:10px 0;'>";
                echo "mysql -u root -p<br>";
                echo "ALTER USER '{$this->username}'@'localhost' IDENTIFIED WITH mysql_native_password BY '{$this->password}';<br>";
                echo "FLUSH PRIVILEGES;<br>";
                echo "exit;";
                echo "</code>";
                echo "</div>";
            }
            
            echo "<ol>";
            echo "<li>Check if MySQL is running in XAMPP Control Panel</li>";
            echo "<li>Verify credentials in <code>config/database.php</code></li>";
            echo "<li>Check username: <strong>{$this->username}</strong></li>";
            echo "<li>If using MySQL 8+, run authentication fix command above</li>";
            echo "</ol>";
            echo "</div>";
        }
        
        die();
    }
    
    // Get PDO instance
    public function getConnection() {
        return $this->pdo;
    }
    
    // Execute query
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->logError($e->getMessage(), $sql);
            throw $e;
        }
    }
    
    // Fetch all rows
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    // Fetch single row
    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    // Insert
    public function insert($table, $data) {
        $keys = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$keys}) VALUES ({$placeholders})";
        $this->query($sql, $data);
        
        return $this->pdo->lastInsertId();
    }

    // Update
    public function update($table, $data, $whereClause, $whereParams = []) {
        if (empty($data)) {
            return 0;
        }

        $setParts = [];
        $params = [];

        foreach ($data as $column => $value) {
            $placeholder = 'set_' . $column;
            $setParts[] = "{$column} = :{$placeholder}";
            $params[$placeholder] = $value;
        }

        $sql = "UPDATE {$table} SET " . implode(', ', $setParts) . " WHERE {$whereClause}";
        $params = array_merge($params, $whereParams);

        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    // Transactions
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    public function commit() {
        return $this->pdo->commit();
    }

    public function rollback() {
        return $this->pdo->rollBack();
    }
    
    // Log errors
    private function logError($message, $sql = '') {
        $logPath = defined('LOG_PATH') ? LOG_PATH : __DIR__ . '/../logs/';
        
        if (!file_exists($logPath)) {
            @mkdir($logPath, 0755, true);
        }
        
        $logFile = $logPath . 'error.log';
        $timestamp = date('Y-m-d H:i:s');
        $entry = "[{$timestamp}] {$message}\n";
        if ($sql) $entry .= "SQL: {$sql}\n";
        $entry .= "---\n";
        
        @file_put_contents($logFile, $entry, FILE_APPEND);
    }
}
?>
