<?php
/**
 * PDF Generator for ID Cards and Certificates
 * Uses TCPDF library
 */

// Download TCPDF: composer require tecnickcom/tcpdf
// Or manual: https://github.com/tecnickcom/TCPDF

require_once __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php';

class PDFGenerator {
    private $pdf;
    
    public function __construct() {
        $this->pdf = new TCPDF('L', 'mm', array(85.6, 53.98), true, 'UTF-8', false);
        
        // Set document properties
        $this->pdf->SetCreator(SITE_NAME);
        $this->pdf->SetAuthor(SITE_NAME);
        $this->pdf->SetTitle('Member ID Card');
        
        // Remove default header/footer
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        
        // Set margins to 0
        $this->pdf->SetMargins(0, 0, 0);
        $this->pdf->SetAutoPageBreak(false, 0);
    }
    
    /**
     * Generate Member ID Card
     */
    public function generateIDCard($member, $qrCodePath, $outputPath) {
        // Add a page
        $this->pdf->AddPage();
        
        // Background (if you have a template image)
        // $this->pdf->Image(BASE_PATH . 'assets/images/id-card-template.jpg', 0, 0, 85.6, 53.98);
        
        // Or create simple background
        $this->pdf->SetFillColor(102, 126, 234); // Primary color
        $this->pdf->Rect(0, 0, 85.6, 53.98, 'F');
        
        // White card area
        $this->pdf->SetFillColor(255, 255, 255);
        $this->pdf->Rect(2, 2, 81.6, 49.98, 'F');
        
        // Header
        $this->pdf->SetFillColor(102, 126, 234);
        $this->pdf->Rect(2, 2, 81.6, 10, 'F');
        
        $this->pdf->SetFont('helvetica', 'B', 14);
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->SetXY(5, 4);
        $this->pdf->Cell(0, 6, SITE_NAME, 0, 0, 'C');
        
        // Photo
        if ($member['photo_path'] && file_exists(BASE_PATH . $member['photo_path'])) {
            $this->pdf->Image(BASE_PATH . $member['photo_path'], 5, 14, 20, 25, '', '', '', false, 300);
        } else {
            // Placeholder
            $this->pdf->SetFillColor(200, 200, 200);
            $this->pdf->Rect(5, 14, 20, 25, 'F');
        }
        
        // Member Details
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetXY(27, 14);
        $this->pdf->Cell(0, 5, $member['full_name'], 0, 0, 'L');
        
        $this->pdf->SetFont('helvetica', '', 8);
        $this->pdf->SetTextColor(100, 100, 100);
        $this->pdf->SetXY(27, 20);
        $this->pdf->Cell(0, 4, $member['designation_name'], 0, 0, 'L');
        
        $this->pdf->SetXY(27, 24);
        $this->pdf->Cell(0, 4, 'Member ID: ' . $member['member_id'], 0, 0, 'L');
        
        $this->pdf->SetXY(27, 28);
        $this->pdf->Cell(0, 4, 'Valid Till: ' . date('d M Y', strtotime($member['membership_expiry_date'])), 0, 0, 'L');
        
        $this->pdf->SetXY(27, 32);
        $this->pdf->Cell(0, 4, 'Mobile: ' . $member['mobile'], 0, 0, 'L');
        
        // QR Code
        if (file_exists($qrCodePath)) {
            $this->pdf->Image($qrCodePath, 60, 14, 20, 20, '', '', '', false, 300);
        }
        
        // Footer
        $this->pdf->SetFont('helvetica', 'I', 6);
        $this->pdf->SetTextColor(150, 150, 150);
        $this->pdf->SetXY(5, 46);
        $this->pdf->Cell(0, 3, 'This is a computer-generated ID card. Verify at ' . SITE_URL, 0, 0, 'C');
        
        // Save PDF
        $this->pdf->Output($outputPath, 'F');
        
        return true;
    }
    
    /**
     * Generate Oath Letter
     */
    public function generateOathLetter($member, $outputPath) {
        $this->pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetMargins(20, 20, 20);
        
        $this->pdf->AddPage();
        
        // Letterhead
        $this->pdf->SetFont('helvetica', 'B', 18);
        $this->pdf->SetTextColor(102, 126, 234);
        $this->pdf->Cell(0, 10, SITE_NAME, 0, 1, 'C');
        
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 5, 'Registered Social Welfare Organization', 0, 1, 'C');
        $this->pdf->Ln(5);
        
        // Title
        $this->pdf->SetFont('helvetica', 'B', 14);
        $this->pdf->Cell(0, 10, 'MEMBERSHIP OATH CERTIFICATE', 0, 1, 'C');
        $this->pdf->Ln(5);
        
        // Content
        $this->pdf->SetFont('helvetica', '', 11);
        
        $html = <<<EOT
        <p>This is to certify that</p>
        <p style="text-align:center; font-size:14px;"><strong>{$member['full_name']}</strong></p>
        <p style="text-align:center;">Member ID: <strong>{$member['member_id']}</strong></p>
        
        <p style="margin-top:15px;">has been enrolled as a member of {SITE_NAME} and has taken the following oath:</p>
        
        <div style="background:#f0f0f0; padding:15px; margin:15px 0; border-left:4px solid #667eea;">
        <p style="font-style:italic; line-height:1.6;">
        "I solemnly pledge to uphold the values and objectives of this organization. 
        I will work towards the betterment of society, serve the community with dedication, 
        and contribute to social welfare initiatives to the best of my abilities. 
        I shall maintain integrity, honesty, and transparency in all my actions as a member."
        </p>
        </div>
        
        <p style="margin-top:20px;">Membership Valid From: <strong>{date('d M Y', strtotime($member['membership_start_date']))}</strong></p>
        <p>Membership Valid Till: <strong>{date('d M Y', strtotime($member['membership_expiry_date']))}</strong></p>
        
        <p style="margin-top:30px;">Issued on: <strong>{date('d M Y')}</strong></p>
EOT;
        
        $this->pdf->writeHTML($html, true, false, true, false, '');
        
        $this->pdf->Ln(20);
        
        // Signature
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(0, 5, '____________________', 0, 1, 'R');
        $this->pdf->Cell(0, 5, 'Authorized Signatory', 0, 1, 'R');
        $this->pdf->SetFont('helvetica', '', 9);
        $this->pdf->Cell(0, 5, SITE_NAME, 0, 1, 'R');
        
        // Save
        $this->pdf->Output($outputPath, 'F');
        
        return true;
    }
}
?>
