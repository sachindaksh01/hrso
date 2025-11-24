<?php
/**
 * Email Notification System
 * Uses PHPMailer for SMTP
 */

// Install: composer require phpmailer/phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class Mailer {
    private $mail;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        if (SMTP_ENABLED) {
            $this->mail->isSMTP();
            $this->mail->Host = SMTP_HOST;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = SMTP_USERNAME;
            $this->mail->Password = SMTP_PASSWORD;
            $this->mail->SMTPSecure = SMTP_ENCRYPTION;
            $this->mail->Port = SMTP_PORT;
        }
        
        $this->mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $this->mail->CharSet = 'UTF-8';
    }
    
    /**
     * Send Welcome Email to New Member
     */
    public function sendWelcomeEmail($member) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($member['email'], $member['full_name']);
            
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Welcome to ' . SITE_NAME;
            
            $body = $this->getTemplate('welcome', [
                'name' => $member['full_name'],
                'member_id' => $member['member_id'],
                'designation' => $member['designation_name'],
                'expiry_date' => formatDate($member['membership_expiry_date'])
            ]);
            
            $this->mail->Body = $body;
            
            return $this->mail->send();
        } catch (Exception $e) {
            error_log('Email Error: ' . $this->mail->ErrorInfo);
            return false;
        }
    }
    
    /**
     * Send Expiry Alert Email
     */
    public function sendExpiryAlert($member, $daysLeft) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($member['email'], $member['full_name']);
            
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Membership Expiring Soon - ' . SITE_NAME;
            
            $body = $this->getTemplate('expiry_alert', [
                'name' => $member['full_name'],
                'member_id' => $member['member_id'],
                'days_left' => $daysLeft,
                'expiry_date' => formatDate($member['membership_expiry_date']),
                'renewal_link' => SITE_URL . 'public/renewal.php?id=' . $member['member_id']
            ]);
            
            $this->mail->Body = $body;
            
            return $this->mail->send();
        } catch (Exception $e) {
            error_log('Email Error: ' . $this->mail->ErrorInfo);
            return false;
        }
    }
    
    /**
     * Send Approval Email
     */
    public function sendApprovalEmail($member) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($member['email'], $member['full_name']);
            
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Membership Approved - ' . SITE_NAME;
            
            $body = $this->getTemplate('approval', [
                'name' => $member['full_name'],
                'member_id' => $member['member_id'],
                'start_date' => formatDate($member['membership_start_date']),
                'expiry_date' => formatDate($member['membership_expiry_date'])
            ]);
            
            $this->mail->Body = $body;
            
            return $this->mail->send();
        } catch (Exception $e) {
            error_log('Email Error: ' . $this->mail->ErrorInfo);
            return false;
        }
    }
    
    /**
     * Get Email Template
     */
    private function getTemplate($type, $data) {
        $templates = [
            'welcome' => "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <div style='background: linear-gradient(135deg, #667eea, #764ba2); padding: 30px; text-align: center;'>
                        <h1 style='color: white; margin: 0;'>" . SITE_NAME . "</h1>
                    </div>
                    <div style='padding: 30px; background: #f9f9f9;'>
                        <h2>Welcome, {name}!</h2>
                        <p>Congratulations! Your membership application has been approved.</p>
                        <div style='background: white; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                            <p><strong>Member ID:</strong> {member_id}</p>
                            <p><strong>Designation:</strong> {designation}</p>
                            <p><strong>Valid Until:</strong> {expiry_date}</p>
                        </div>
                        <p>You will receive your ID card and certificate shortly.</p>
                        <p>Thank you for joining us!</p>
                    </div>
                    <div style='background: #333; color: white; padding: 20px; text-align: center; font-size: 12px;'>
                        <p>&copy; " . date('Y') . " " . SITE_NAME . ". All rights reserved.</p>
                    </div>
                </div>
            ",
            
            'expiry_alert' => "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <div style='background: linear-gradient(135deg, #f093fb, #f5576c); padding: 30px; text-align: center;'>
                        <h1 style='color: white; margin: 0;'>Membership Expiring Soon</h1>
                    </div>
                    <div style='padding: 30px; background: #f9f9f9;'>
                        <h2>Dear {name},</h2>
                        <p>Your membership is expiring in <strong>{days_left} days</strong>.</p>
                        <div style='background: white; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                            <p><strong>Member ID:</strong> {member_id}</p>
                            <p><strong>Expiry Date:</strong> {expiry_date}</p>
                        </div>
                        <p>To continue enjoying membership benefits, please renew now:</p>
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='{renewal_link}' style='background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 15px 30px; text-decoration: none; border-radius: 50px; display: inline-block;'>
                                Renew Membership
                            </a>
                        </div>
                    </div>
                    <div style='background: #333; color: white; padding: 20px; text-align: center; font-size: 12px;'>
                        <p>&copy; " . date('Y') . " " . SITE_NAME . "</p>
                    </div>
                </div>
            ",
            
            'approval' => "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <div style='background: linear-gradient(135deg, #43e97b, #38f9d7); padding: 30px; text-align: center;'>
                        <h1 style='color: white; margin: 0;'>Membership Approved!</h1>
                    </div>
                    <div style='padding: 30px; background: #f9f9f9;'>
                        <h2>Congratulations {name}!</h2>
                        <p>Your membership application has been approved.</p>
                        <div style='background: white; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                            <p><strong>Member ID:</strong> {member_id}</p>
                            <p><strong>Start Date:</strong> {start_date}</p>
                            <p><strong>Valid Until:</strong> {expiry_date}</p>
                        </div>
                        <p>Welcome to our community!</p>
                    </div>
                </div>
            "
        ];
        
        $template = $templates[$type] ?? '';
        
        // Replace placeholders
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        
        return $template;
    }
}
?>
