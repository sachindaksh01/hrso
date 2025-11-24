<?php
require_once 'config/config.php';

$username = 'superadmin';
$password = 'Admin@123';

// Get admin from database
$admin = $db->fetch("SELECT * FROM admin_users WHERE username = :username", 
    ['username' => $username]);

echo "<h2>Login Debug</h2>";

if (!$admin) {
    echo "<p style='color:red;'>❌ Admin user not found in database!</p>";
    echo "<p>Run this SQL:</p>";
    echo "<textarea style='width:100%; height:200px;'>";
    echo "INSERT INTO admin_users (username, email, password_hash, full_name, role_type, is_active) VALUES ";
    echo "('superadmin', 'admin@hrso.org', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator', 'super_admin', 1);";
    echo "</textarea>";
} else {
    echo "<p style='color:green;'>✅ Admin user found!</p>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    foreach ($admin as $key => $value) {
        echo "<tr><td>{$key}</td><td>{$value}</td></tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    echo "<h3>Password Verification:</h3>";
    
    if (password_verify($password, $admin['password_hash'])) {
        echo "<p style='color:green; font-size:20px;'>✅ PASSWORD CORRECT! Login should work!</p>";
    } else {
        echo "<p style='color:red; font-size:20px;'>❌ PASSWORD INCORRECT!</p>";
        echo "<p>Current hash in DB: {$admin['password_hash']}</p>";
        echo "<p>Run this to fix:</p>";
        echo "<textarea style='width:100%; height:100px;'>";
        echo "UPDATE admin_users SET password_hash = '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE username = 'superadmin';";
        echo "</textarea>";
    }
    
    if ($admin['is_active'] != 1) {
        echo "<p style='color:orange;'>⚠️ User is inactive! Run: UPDATE admin_users SET is_active = 1 WHERE username = 'superadmin';</p>";
    }
}

echo "<p><strong>Delete this file after debugging!</strong></p>";
?>
