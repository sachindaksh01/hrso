<?php
/**
 * QR Code Generator
 * Uses phpqrcode library
 */

// Download: composer require phpqrcode/phpqrcode
// Or manual: https://sourceforge.net/projects/phpqrcode/

require_once __DIR__ . '/../vendor/phpqrcode/qrlib.php';

class QRCodeGenerator {
    
    /**
     * Generate QR Code for Member ID
     */
    public static function generateMemberQR($member_id, $outputPath) {
        // Create verification URL
        $verifyUrl = SITE_URL . 'public/member-verify.php?id=' . urlencode($member_id);
        
        // Generate QR Code
        // Parameters: data, filename, error correction level, size, margin
        QRcode::png($verifyUrl, $outputPath, QR_ECLEVEL_H, 8, 2);
        
        return file_exists($outputPath);
    }
    
    /**
     * Generate QR Code with custom data
     */
    public static function generate($data, $outputPath, $size = 8) {
        QRcode::png($data, $outputPath, QR_ECLEVEL_H, $size, 2);
        return file_exists($outputPath);
    }
    
    /**
     * Generate QR Code and return as base64
     */
    public static function generateBase64($data) {
        ob_start();
        QRcode::png($data, false, QR_ECLEVEL_H, 8, 2);
        $imageString = ob_get_contents();
        ob_end_clean();
        
        return 'data:image/png;base64,' . base64_encode($imageString);
    }
}
?>
