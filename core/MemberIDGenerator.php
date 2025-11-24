<?php
/**
 * Member ID Generator
 * Generate unique member IDs
 */

class MemberIDGenerator {
    private $db;
    private $prefix = 'HRSO';
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Generate Member ID
     * Format: HRSO-{LevelCode}-{StateCode}-{DistrictCode}-{Year}-{Serial}
     */
    public function generate($level_id, $state_id = null, $district_id = null) {
        // Get level code
        $level = $this->db->fetch("SELECT level_code FROM levels WHERE id = ?", [$level_id]);
        $level_code = $level['level_code'];
        
        // Get state code
        if ($state_id) {
            $state = $this->db->fetch("SELECT state_code FROM states WHERE id = ?", [$state_id]);
            $state_code = $state['state_code'];
        } else {
            $state_code = 'IND'; // National level
        }
        
        // Get district code (first 3 letters)
        $district_code = '';
        if ($district_id) {
            $district = $this->db->fetch("SELECT district_name FROM districts WHERE id = ?", [$district_id]);
            $district_code = '-' . strtoupper(substr($district['district_name'], 0, 3));
        }
        
        // Current year
        $year = date('Y');
        
        // Get next serial number
        $pattern = "{$this->prefix}-{$level_code}-{$state_code}%{$year}%";
        $sql = "SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(member_id, '-', -1) AS UNSIGNED)), 0) + 1 as next_serial
                FROM members 
                WHERE member_id LIKE ?";
        
        $result = $this->db->fetch($sql, [$pattern]);
        $serial = $result['next_serial'];
        
        // Format member ID
        $member_id = sprintf(
            "%s-%s-%s%s-%s-%04d",
            $this->prefix,
            $level_code,
            $state_code,
            $district_code,
            $year,
            $serial
        );
        
        return $member_id;
    }
}
?>
