<?php
/**
 * Expiry Alert Cron Job
 * Run daily: 0 9 * * * /usr/bin/php /path/to/hrso/cron/check-expiry.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Mailer.php';

// Get members expiring in next 30 days
$alertDays = MEMBERSHIP_EXPIRY_ALERT_DAYS;

$sql = "SELECT m.*, d.designation_name
        FROM members m
        JOIN designations d ON m.designation_id = d.id
        WHERE m.status = 'approved' 
        AND m.membership_expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :days DAY)
        AND m.email IS NOT NULL";

$expiringMembers = $db->fetchAll($sql, ['days' => $alertDays]);

$mailer = new Mailer();
$sentCount = 0;

foreach ($expiringMembers as $member) {
    $daysLeft = floor((strtotime($member['membership_expiry_date']) - time()) / 86400);
    
    // Send alert at 30, 15, 7, 3, 1 days
    if (in_array($daysLeft, [30, 15, 7, 3, 1])) {
        if ($mailer->sendExpiryAlert($member, $daysLeft)) {
            $sentCount++;
            
            // Log notification
            error_log("Expiry alert sent to: {$member['email']} ({$daysLeft} days left)");
        }
    }
}

// Update expired memberships
$db->query("UPDATE members SET status = 'expired' WHERE membership_expiry_date < CURDATE() AND status = 'approved'");

echo "Cron job completed. Alerts sent: {$sentCount}\n";
?>
