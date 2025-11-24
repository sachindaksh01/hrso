<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Dashboard';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Admin Panel</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/admin.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 60px;
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            border-left-color: var(--primary-color);
            padding-left: 25px;
        }
        
        .sidebar-menu a i {
            width: 25px;
            margin-right: 10px;
            font-size: 16px;
        }
        
        /* Section Headers */
        .sidebar-section-header {
            padding: 10px 20px;
            color: rgba(255,255,255,0.5);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-top: 10px;
        }
        
        /* Badge in sidebar */
        .sidebar-menu .badge {
            font-size: 10px;
            padding: 3px 6px;
            border-radius: 10px;
            margin-left: auto;
        }
        
        /* Top Navbar */
        .top-navbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 999;
        }
        
        .navbar-title {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 30px;
            min-height: calc(100vh - var(--header-height));
        }
        
        /* Cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
            margin: 5px 0 0 0;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-user-shield fa-2x mb-2"></i>
            <h4><?php echo SITE_NAME; ?></h4>
            <small>Admin Panel</small>
        </div>
        
        <div class="sidebar-menu">
            <!-- Dashboard -->
            <a href="<?php echo SITE_URL; ?>admin/index.php" 
               class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['PHP_SELF'], 'admin/index.php') !== false ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
            
            <!-- Members Section -->
            <div class="sidebar-section-header">
                <i class="fas fa-users me-2"></i>MEMBERS MANAGEMENT
            </div>
            
            <!-- All Members -->
            <a href="<?php echo SITE_URL; ?>admin/members/all.php" 
               class="<?php echo basename($_SERVER['PHP_SELF']) == 'all.php' ? 'active' : ''; ?>">
                <i class="fas fa-list"></i>
                <span>All Members</span>
            </a>
            
            <!-- Add New Member -->
            <a href="<?php echo SITE_URL; ?>admin/members/add.php" 
               class="<?php echo basename($_SERVER['PHP_SELF']) == 'add.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-plus"></i>
                <span>Add New Member</span>
            </a>
            
            <!-- Renewal Members -->
            <a href="<?php echo SITE_URL; ?>admin/members/renewal.php" 
               class="<?php echo basename($_SERVER['PHP_SELF']) == 'renewal.php' ? 'active' : ''; ?>">
                <i class="fas fa-sync-alt"></i>
                <span>Renewal Members</span>
                <?php 
                $renewalCount = $db->fetch("
                    SELECT COUNT(*) as count FROM members 
                    WHERE status = 'approved' 
                    AND DATEDIFF(membership_expiry_date, CURDATE()) BETWEEN 0 AND 30
                ")['count'];
                if ($renewalCount > 0):
                ?>
                    <span class="badge bg-warning"><?php echo $renewalCount; ?></span>
                <?php endif; ?>
            </a>
            
            <!-- Expired Members -->
            <a href="<?php echo SITE_URL; ?>admin/members/expired.php" 
               class="<?php echo basename($_SERVER['PHP_SELF']) == 'expired.php' ? 'active' : ''; ?>">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Expired Members</span>
                <?php 
                $expiredCount = $db->fetch("
                    SELECT COUNT(*) as count FROM members 
                    WHERE status = 'approved' 
                    AND membership_expiry_date < CURDATE()
                ")['count'];
                if ($expiredCount > 0):
                ?>
                    <span class="badge bg-danger"><?php echo $expiredCount; ?></span>
                <?php endif; ?>
            </a>
            
            <!-- Pending Requests -->
            <a href="<?php echo SITE_URL; ?>admin/members/pending.php" 
               class="<?php echo basename($_SERVER['PHP_SELF']) == 'pending.php' ? 'active' : ''; ?>">
                <i class="fas fa-clock"></i>
                <span>Pending Requests</span>
                <?php 
                $pendingCount = $db->fetch("SELECT COUNT(*) as count FROM members WHERE status = 'pending'")['count'];
                if ($pendingCount > 0):
                ?>
                    <span class="badge bg-info"><?php echo $pendingCount; ?></span>
                <?php endif; ?>
            </a>
            
            <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
            
            <!-- Other Sections -->
            <div class="sidebar-section-header">
                <i class="fas fa-cog me-2"></i>OTHER MODULES
            </div>
            
            <a href="<?php echo SITE_URL; ?>admin/payments/index.php"
               class="<?php echo strpos($_SERVER['PHP_SELF'], '/payments/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-money-bill-wave"></i>
                <span>Payments</span>
            </a>
            
            <a href="<?php echo SITE_URL; ?>admin/designations/index.php"
               class="<?php echo strpos($_SERVER['PHP_SELF'], '/designations/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-briefcase"></i>
                <span>Designations</span>
            </a>
            
            <a href="<?php echo SITE_URL; ?>admin/reports/dashboard.php"
               class="<?php echo strpos($_SERVER['PHP_SELF'], '/reports/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            
            <?php if ($currentAdmin['role'] === 'super_admin'): ?>
            <a href="<?php echo SITE_URL; ?>admin/settings/general.php"
               class="<?php echo strpos($_SERVER['PHP_SELF'], '/settings/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            
            <a href="<?php echo SITE_URL; ?>admin/admins/index.php"
               class="<?php echo strpos($_SERVER['PHP_SELF'], '/admins/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-user-shield"></i>
                <span>Admin Users</span>
            </a>
            <?php endif; ?>
            
            <hr style="border-color: rgba(255,255,255,0.1); margin: 20px 0;">
            
            <a href="<?php echo SITE_URL; ?>admin/logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
    
    <!-- Top Navbar -->
    <div class="top-navbar">
        <div class="navbar-title">
            <i class="fas fa-bars me-2" style="cursor:pointer;" onclick="toggleSidebar()"></i>
            <?php echo $pageTitle; ?>
        </div>
        
        <div class="navbar-right">
            <div class="dropdown">
                <div class="admin-profile" data-bs-toggle="dropdown">
                    <div class="admin-avatar">
                        <?php echo strtoupper(substr($currentAdmin['name'], 0, 1)); ?>
                    </div>
                    <div>
                        <strong><?php echo $currentAdmin['name']; ?></strong>
                        <br>
                        <small class="text-muted"><?php echo ucfirst(str_replace('_', ' ', $currentAdmin['role'])); ?></small>
                    </div>
                    <i class="fas fa-chevron-down ms-2"></i>
                </div>
                
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?php echo SITE_URL; ?>admin/logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
