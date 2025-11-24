<?php
require_once 'config/config.php';
require_once 'core/Database.php';

echo "<h1>HRSO System Test</h1>";

// Test 1: Database Connection
echo "<h2>1. Database Connection</h2>";
try {
    $db = new Database();
    echo "✅ Database connected successfully<br>";
    
    // Test query
    $result = $db->fetch("SELECT COUNT(*) as count FROM levels");
    echo "✅ Levels table accessible (Count: {$result['count']})<br>";
    
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "<br>";
}

// Test 2: Check folders
echo "<h2>2. Folder Structure</h2>";
$folders = ['uploads', 'uploads/members', 'uploads/members/photos', 'logs', 'sql'];
foreach ($folders as $folder) {
    if (is_dir($folder)) {
        echo "✅ {$folder} exists<br>";
    } else {
        echo "❌ {$folder} missing<br>";
    }
}

// Test 3: Check config
echo "<h2>3. Configuration</h2>";
echo "Site Name: " . SITE_NAME . "<br>";
echo "Site URL: " . SITE_URL . "<br>";
echo "Environment: " . ENVIRONMENT . "<br>";

// Test 4: Session
echo "<h2>4. Session</h2>";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "✅ Session active<br>";
} else {
    echo "❌ Session not active<br>";
}

// Test 5: Member ID Generation
echo "<h2>5. Member ID Generation</h2>";
try {
    require_once 'core/MemberIDGenerator.php';
    $idGen = new MemberIDGenerator($db);
    
    $testID1 = $idGen->generate(1, null, null); // National
    echo "National Level ID: {$testID1}<br>";
    
    $testID2 = $idGen->generate(2, 26, null); // State (UP)
    echo "State Level ID: {$testID2}<br>";
    
    echo "✅ ID generation working<br>";
} catch (Exception $e) {
    echo "❌ ID Generation Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h3>✅ Phase 1 Setup Complete!</h3>";
echo "<p>Next: Delete this test file and proceed to Phase 2</p>";
?>
