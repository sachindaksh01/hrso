<?php
/**
 * Payment Gateway Configuration
 * Razorpay, CCAvenue, Paytm settings
 */

// ====================================
// RAZORPAY CONFIGURATION
// ====================================
define('RAZORPAY_ENABLED', true);
define('RAZORPAY_KEY_ID', 'rzp_test_xxxxxxxxxxxxx'); // Test key
define('RAZORPAY_SECRET', 'your_secret_key_here');
define('RAZORPAY_WEBHOOK_SECRET', 'your_webhook_secret');

// Razorpay Options
define('RAZORPAY_OPTIONS', serialize([
    'name' => SITE_NAME,
    'description' => 'Membership Registration',
    'currency' => 'INR',
    'theme' => [
        'color' => '#F37254'
    ],
    'modal' => [
        'confirm_close' => true
    ]
]));

// ====================================
// CCAVENUE CONFIGURATION
// ====================================
define('CCAVENUE_ENABLED', false);
define('CCAVENUE_MERCHANT_ID', '');
define('CCAVENUE_ACCESS_CODE', '');
define('CCAVENUE_WORKING_KEY', '');
define('CCAVENUE_REDIRECT_URL', SITE_URL . 'payment-success.php');
define('CCAVENUE_CANCEL_URL', SITE_URL . 'payment-failed.php');

// ====================================
// PAYTM CONFIGURATION
// ====================================
define('PAYTM_ENABLED', false);
define('PAYTM_MERCHANT_ID', '');
define('PAYTM_MERCHANT_KEY', '');
define('PAYTM_WEBSITE', 'WEBSTAGING'); // WEBSTAGING for test, DEFAULT for production
define('PAYTM_INDUSTRY_TYPE', 'Retail');
define('PAYTM_CHANNEL_ID', 'WEB');

// ====================================
// MANUAL PAYMENT (Bank Transfer)
// ====================================
define('MANUAL_PAYMENT_ENABLED', true);
define('MANUAL_PAYMENT_INSTRUCTIONS', 
    'Please transfer the amount to our bank account and upload the payment receipt.'
);

?>
