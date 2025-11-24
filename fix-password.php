<?php
require_once 'config/config.php';

// Generate NEW fresh hash
$password = 'Admin@123';
$newHash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Password Fix Script</h2>";
echo "<p><strong>Generating new hash for password:</strong> {$password}</p>";
echo "<hr>";

echo "<h3>New Hash Generated:</h3>";
echo "<textarea style='width:100%; height:80px; font-family:monospace;'>{$newHash}</textarea>";

echo "<hr>";
echo "<h3>SQL Query to Update:</h3>";
echo "<textarea style='width:100%; height:120px; font-family:monospace;'>";
echo "UPDATE admin_users \n";
echo "SET password_hash = '{$newHash}' \n";
echo "WHERE username = 'superadmin';";
echo "</textarea>";

echo "<hr>";
echo "<h3>Click to Auto-Update:</h3>";

// Auto-update option
if (isset($_GET['update'])) {
    try {
        $db->update('admin_users', 
            ['password_hash' => $newHash], 
            "username = 'superadmin'");
        
        echo "<div style='background:#d4edda; color:#155724; padding:20px; border-radius:5px; font-size:18px;'>";
        echo "✅ <strong>SUCCESS!</strong> Password updated successfully!<br><br>";
        echo "Now login with:<br>";
        echo "Username: <strong>superadmin</strong><br>";
        echo "Password: <strong>{$password}</strong><br><br>";
        echo "<a href='admin/login.php' style='background:#28a745; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Go to Login</a>";
        echo "</div>";
        
        echo "<hr>";
        echo "<p style='color:red; font-weight:bold;'>⚠️ DELETE THIS FILE NOW!</p>";
        
    } catch (Exception $e) {
        echo "<div style='background:#f8d7da; color:#721c24; padding:20px; border-radius:5px;'>";
        echo "❌ Error: " . $e->getMessage();
        echo "</div>";
    }
} else {
    echo "<a href='?update=1' style='background:#007bff; color:white; padding:15px 30px; text-decoration:none; border-radius:5px; font-size:18px;'>
            Click Here to Update Password Automatically
          </a>";
}

echo "<hr>";
echo "<h3>Manual Test:</h3>";
$testHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
echo "<p>Testing old hash...</p>";
if (password_verify('Admin@123', $testHash)) {
    echo "<p style='color:green;'>✅ Old hash works! (PHP version compatible)</p>";
} else {
    echo "<p style='color:red;'>❌ Old hash doesn't work (PHP version issue)</p>";
}

echo "<p>Testing new hash...</p>";
if (password_verify($password, $newHash)) {
    echo "<p style='color:green;'>✅ New hash works perfectly!</p>";
} else {
    echo "<p style='color:red;'>❌ New hash failed (something wrong with PHP)</p>";
}
?>
