<?php
require_once 'config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration Test - HRSO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f4f4f4;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        h2 {
            color: #555;
            margin-top: 30px;
            border-left: 4px solid #28a745;
            padding-left: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
        .warning {
            color: #ffc107;
            font-weight: bold;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
        }
        .badge-success {
            background: #28a745;
            color: white;
        }
        .badge-error {
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 HRSO Configuration Test</h1>
        <p><strong>Test Date:</strong> <?php echo date('d M Y, h:i A'); ?></p>
        
        <!-- Site Settings -->
        <h2>1. Site Settings</h2>
        <table>
            <tr>
                <th>Setting</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Site Name</td>
                <td><?php echo SITE_NAME; ?></td>
            </tr>
            <tr>
                <td>Site URL</td>
                <td><?php echo SITE_URL; ?></td>
            </tr>
            <tr>
                <td>Environment</td>
                <td>
                    <span class="badge <?php echo ENVIRONMENT === 'development' ? 'badge-error' : 'badge-success'; ?>">
                        <?php echo strtoupper(ENVIRONMENT); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Admin Email</td>
                <td><?php echo ADMIN_EMAIL; ?></td>
            </tr>
        </table>
        
        <!-- Database Connection -->
        <h2>2. Database Connection</h2>
        <table>
            <tr>
                <th>Parameter</th>
                <th>Value</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>Database Host</td>
                <td><?php echo DB_HOST; ?></td>
                <td><span class="success">✓</span></td>
            </tr>
            <tr>
                <td>Database Name</td>
                <td><?php echo DB_NAME; ?></td>
                <td>
                    <?php
                    try {
                        $pdo = $db->getConnection();
                        echo '<span class="success">✓ Connected</span>';
                    } catch (Exception $e) {
                        echo '<span class="error">✗ Failed</span>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Database User</td>
                <td><?php echo DB_USER; ?></td>
                <td><span class="success">✓</span></td>
            </tr>
        </table>
        
        <!-- Folder Structure -->
        <h2>3. Folder Structure</h2>
        <table>
            <tr>
                <th>Folder</th>
                <th>Path</th>
                <th>Status</th>
            </tr>
            <?php
            $folders = [
                'Uploads' => UPLOAD_PATH,
                'Member Photos' => MEMBER_PHOTO_PATH,
                'Member Signatures' => MEMBER_SIGNATURE_PATH,
                'ID Proofs' => MEMBER_ID_PROOF_PATH,
                'Logs' => LOG_PATH,
                'Documents' => DOCUMENT_PATH
            ];
            
            foreach ($folders as $name => $path) {
                $exists = is_dir($path);
                $writable = is_writable($path);
                echo "<tr>";
                echo "<td>{$name}</td>";
                echo "<td>" . str_replace(BASE_PATH, '', $path) . "</td>";
                echo "<td>";
                if ($exists && $writable) {
                    echo '<span class="success">✓ OK</span>';
                } elseif ($exists) {
                    echo '<span class="warning">⚠ Not Writable</span>';
                } else {
                    echo '<span class="error">✗ Missing</span>';
                }
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        
        <!-- Constants Check -->
        <h2>4. Important Constants</h2>
        <table>
            <tr>
                <th>Constant</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Member ID Prefix</td>
                <td><?php echo MEMBER_ID_PREFIX; ?></td>
            </tr>
            <tr>
                <td>Records Per Page</td>
                <td><?php echo RECORDS_PER_PAGE; ?></td>
            </tr>
            <tr>
                <td>Session Timeout</td>
                <td><?php echo SESSION_TIMEOUT; ?> seconds (<?php echo SESSION_TIMEOUT/60; ?> min)</td>
            </tr>
            <tr>
                <td>Max Photo Size</td>
                <td><?php echo MAX_PHOTO_SIZE / 1024 / 1024; ?> MB</td>
            </tr>
            <tr>
                <td>Payment Gateway</td>
                <td><?php echo strtoupper(PAYMENT_GATEWAY); ?></td>
            </tr>
        </table>
        
        <!-- PHP Configuration -->
        <h2>5. PHP Configuration</h2>
        <table>
            <tr>
                <th>Setting</th>
                <th>Value</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>PHP Version</td>
                <td><?php echo phpversion(); ?></td>
                <td>
                    <?php
                    echo version_compare(phpversion(), '7.4.0', '>=') 
                        ? '<span class="success">✓ OK</span>' 
                        : '<span class="error">✗ Upgrade Required</span>';
                    ?>
                </td>
            </tr>
            <tr>
                <td>Session Status</td>
                <td><?php echo session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive'; ?></td>
                <td>
                    <?php
                    echo session_status() === PHP_SESSION_ACTIVE 
                        ? '<span class="success">✓ OK</span>' 
                        : '<span class="error">✗ Not Active</span>';
                    ?>
                </td>
            </tr>
            <tr>
                <td>Max Upload Size</td>
                <td><?php echo ini_get('upload_max_filesize'); ?></td>
                <td><span class="success">✓</span></td>
            </tr>
            <tr>
                <td>Max POST Size</td>
                <td><?php echo ini_get('post_max_size'); ?></td>
                <td><span class="success">✓</span></td>
            </tr>
        </table>
        
        <!-- Test Database Tables -->
        <h2>6. Database Tables</h2>
        <table>
            <tr>
                <th>Table Name</th>
                <th>Status</th>
                <th>Records</th>
            </tr>
            <?php
            $tables = ['levels', 'designations', 'states', 'membership_plans', 'admin_users', 'members'];
            foreach ($tables as $table) {
                try {
                    $result = $db->fetch("SELECT COUNT(*) as count FROM {$table}");
                    echo "<tr>";
                    echo "<td>{$table}</td>";
                    echo "<td><span class='success'>✓ Exists</span></td>";
                    echo "<td>{$result['count']}</td>";
                    echo "</tr>";
                } catch (Exception $e) {
                    echo "<tr>";
                    echo "<td>{$table}</td>";
                    echo "<td><span class='error'>✗ Missing</span></td>";
                    echo "<td>-</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
        
        <hr style="margin: 30px 0;">
        <h3 style="color: #28a745;">✅ Configuration Test Complete!</h3>
        <p><strong>Note:</strong> Delete this file (<code>test-config.php</code>) after verification for security.</p>
    </div>
</body>
</html>
