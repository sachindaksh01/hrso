USE hrso_membership;

-- Permissions Table
CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    permission_name VARCHAR(100) NOT NULL UNIQUE,
    permission_slug VARCHAR(100) NOT NULL UNIQUE,
    module VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO permissions (permission_name, permission_slug, module, description) VALUES
('View All Members', 'view_all_members', 'members', 'Can view all members'),
('View Own Area Members', 'view_own_area_members', 'members', 'Can view members in assigned area'),
('Approve Members', 'approve_members', 'members', 'Can approve pending members'),
('Reject Members', 'reject_members', 'members', 'Can reject members'),
('Edit Members', 'edit_members', 'members', 'Can edit member details'),
('Delete Members', 'delete_members', 'members', 'Can delete members'),
('Generate ID Cards', 'generate_id_cards', 'members', 'Can generate ID cards'),
('Manage Designations', 'manage_designations', 'designations', 'Add/Edit/Delete designations'),
('View Payments', 'view_payments', 'payments', 'View payment records'),
('Manage Settings', 'manage_settings', 'settings', 'Edit system settings'),
('View Reports', 'view_reports', 'reports', 'View analytics'),
('Manage Admins', 'manage_admins', 'admins', 'Add/Edit admin users');

-- Role Permissions Mapping
CREATE TABLE role_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_type ENUM('super_admin', 'national_admin', 'state_admin', 'district_admin') NOT NULL,
    permission_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_role_permission (role_type, permission_id)
) ENGINE=InnoDB;

-- Super Admin: All permissions
INSERT INTO role_permissions (role_type, permission_id)
SELECT 'super_admin', id FROM permissions;

-- National Admin
INSERT INTO role_permissions (role_type, permission_id)
SELECT 'national_admin', id FROM permissions 
WHERE permission_slug IN ('view_all_members', 'approve_members', 'reject_members', 
'edit_members', 'generate_id_cards', 'view_payments', 'view_reports');

-- State Admin
INSERT INTO role_permissions (role_type, permission_id)
SELECT 'state_admin', id FROM permissions 
WHERE permission_slug IN ('view_own_area_members', 'approve_members', 'reject_members', 
'edit_members', 'generate_id_cards', 'view_reports');

-- District Admin
INSERT INTO role_permissions (role_type, permission_id)
SELECT 'district_admin', id FROM permissions 
WHERE permission_slug IN ('view_own_area_members', 'approve_members', 'generate_id_cards');
