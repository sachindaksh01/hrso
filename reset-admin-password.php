<?php
/**
 * Temporary helper to reset any admin password.
 * Usage: open http://localhost/hrso/reset-admin-password.php
 * DELETE THIS FILE AFTER USE.
 */

require_once __DIR__ . '/config/config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $newPassword = $_POST['password'] ?? '';

    if ($username === '' || $newPassword === '') {
        $message = ['type' => 'error', 'text' => 'Username and password are required'];
    } else {
        $admin = $db->fetch(
            "SELECT id FROM admin_users WHERE username = :username",
            ['username' => $username]
        );

        if (!$admin) {
            $message = ['type' => 'error', 'text' => "User '{$username}' not found"];
        } else {
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $db->update(
                'admin_users',
                [
                    'password_hash' => $hash,
                    'is_active' => 1,
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                'id = :id',
                ['id' => $admin['id']]
            );

            $message = [
                'type' => 'success',
                'text' => "Password for '{$username}' reset. New password: {$newPassword}"
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Admin Password</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; background:#f4f4f4; }
        .container { max-width: 500px; margin: auto; background:white; padding:25px; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,.1); }
        label { display:block; margin-bottom:6px; font-weight:bold; }
        input[type=text], input[type=password] { width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:6px; }
        button { padding:12px 20px; border:none; background:#007bff; color:white; border-radius:6px; cursor:pointer; width:100%; font-size:16px; }
        .msg { padding:12px; border-radius:6px; margin-bottom:15px; }
        .msg.success { background:#d4edda; color:#155724; }
        .msg.error { background:#f8d7da; color:#721c24; }
        .warning { margin-top:20px; color:#b02a37; font-size:14px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Admin Password</h2>
        <?php if ($message): ?>
            <div class="msg <?php echo $message['type']; ?>">
                <?php echo htmlspecialchars($message['text']); ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" placeholder="e.g. superadmin" required>

            <label>New Password</label>
            <input type="password" name="password" placeholder="Enter new password" required minlength="8">

            <button type="submit">Reset Password</button>
        </form>

        <p class="warning">
            Delete this file after use. Keeping it on the server is a security risk.
        </p>
    </div>
</body>
</html>

