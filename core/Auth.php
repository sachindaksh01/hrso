<?php
/**
 * Authentication Class
 * Handle admin login/logout
 */

class Auth {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    // Login function
    public function login($username, $password) {
        $sql = "SELECT * FROM admin_users WHERE username = :username AND is_active = 1";
        $admin = $this->db->fetch($sql, ['username' => $username]);
        
        if ($admin && $this->verifyAdminPassword($password, $admin)) {
            // Regenerate session ID (security)
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_role'] = $admin['role_type'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_level_id'] = $admin['level_id'];
            $_SESSION['admin_state_id'] = $admin['state_id'];
            $_SESSION['admin_district_id'] = $admin['district_id'];
            
            // Update last login
            $this->db->update(
                'admin_users',
                ['last_login' => date('Y-m-d H:i:s')],
                'id = ' . $admin['id']
            );

            $this->logLoginAttempt($username, true, 'login_success');
            
            return true;
        }

        $this->logLoginAttempt(
            $username,
            false,
            $admin ? 'password_mismatch' : 'user_not_found'
        );
        
        return false;
    }
    
    // Logout
    public function logout() {
        session_unset();
        session_destroy();
        return true;
    }
    
    // Check if logged in
    public function isLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    // Get current admin
    public function getCurrentAdmin() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['admin_id'],
            'username' => $_SESSION['admin_username'],
            'role' => $_SESSION['admin_role'],
            'name' => $_SESSION['admin_name'],
            'level_id' => $_SESSION['admin_level_id'],
            'state_id' => $_SESSION['admin_state_id'],
            'district_id' => $_SESSION['admin_district_id']
        ];
    }
    
    // Check permission (FIXED VERSION)
    public function hasPermission($permission_slug) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        // SUPER ADMIN HAS ALL PERMISSIONS
        if ($_SESSION['admin_role'] === 'super_admin') {
            return true;
        }
        
        // Check database for other roles
        $sql = "SELECT COUNT(*) as count 
                FROM role_permissions rp
                JOIN permissions p ON rp.permission_id = p.id
                WHERE rp.role_type = :role AND p.permission_slug = :permission";
        
        $result = $this->db->fetch($sql, [
            'role' => $_SESSION['admin_role'],
            'permission' => $permission_slug
        ]);
        
        return $result['count'] > 0;
    }
    
    // Require login (redirect if not logged in)
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: ' . SITE_URL . 'admin/login.php');
            exit;
        }
    }
    
    // Require permission (FIXED VERSION)
    public function requirePermission($permission_slug) {
        $this->requireLogin();
        
        // Super admin bypass
        if ($_SESSION['admin_role'] === 'super_admin') {
            return true;
        }
        
        if (!$this->hasPermission($permission_slug)) {
            http_response_code(403);
            die('Access Denied: You do not have permission to access this page.');
        }
    }

    /**
     * Verify password and transparently upgrade legacy/plain-text entries
     */
    private function verifyAdminPassword($password, array $admin) {
        $storedHash = $admin['password_hash'] ?? '';

        if ($this->isModernHash($storedHash)) {
            if (!password_verify($password, $storedHash)) {
                return false;
            }

            if (password_needs_rehash($storedHash, PASSWORD_DEFAULT)) {
                $this->updatePasswordHash($admin['id'], password_hash($password, PASSWORD_DEFAULT));
            }

            return true;
        }

        // Plain-text or legacy hash fallback (if password edited manually in DB)
        if ($storedHash !== '' && hash_equals($storedHash, $password)) {
            $this->updatePasswordHash($admin['id'], password_hash($password, PASSWORD_DEFAULT));
            return true;
        }

        if ($this->isMd5Hash($storedHash) && hash_equals(strtolower($storedHash), md5($password))) {
            $this->updatePasswordHash($admin['id'], password_hash($password, PASSWORD_DEFAULT));
            return true;
        }

        if ($this->isSha1Hash($storedHash) && hash_equals(strtolower($storedHash), sha1($password))) {
            $this->updatePasswordHash($admin['id'], password_hash($password, PASSWORD_DEFAULT));
            return true;
        }

        return false;
    }

    private function isModernHash($hash) {
        if (!is_string($hash) || $hash === '') {
            return false;
        }

        return preg_match('/^\$(2[ayb]|argon2i|argon2id)\$/', $hash) === 1;
    }

    private function updatePasswordHash($adminId, $newHash) {
        $this->db->update(
            'admin_users',
            ['password_hash' => $newHash],
            'id = ' . $adminId
        );
    }

    private function isMd5Hash($hash) {
        return is_string($hash) && preg_match('/^[a-f0-9]{32}$/i', $hash);
    }

    private function isSha1Hash($hash) {
        return is_string($hash) && preg_match('/^[a-f0-9]{40}$/i', $hash);
    }

    private function logLoginAttempt($username, $success, $reason = '') {
        if (!defined('LOG_PATH')) {
            return;
        }

        $logFile = LOG_PATH . 'auth.log';
        $dir = dirname($logFile);
        if (!file_exists($dir)) {
            @mkdir($dir, 0755, true);
        }

        $entry = sprintf(
            "[%s] username=%s success=%s reason=%s ip=%s\n",
            date('Y-m-d H:i:s'),
            $username,
            $success ? '1' : '0',
            $reason,
            $_SERVER['REMOTE_ADDR'] ?? 'CLI'
        );

        @file_put_contents($logFile, $entry, FILE_APPEND);
    }
    
    /**
     * Check if user is super admin
     */
    public function isSuperAdmin() {
        return $this->isLoggedIn() && $_SESSION['admin_role'] === 'super_admin';
    }
    
    /**
     * Check if user has specific role
     */
    public function hasRole($role) {
        return $this->isLoggedIn() && $_SESSION['admin_role'] === $role;
    }
}
?>
