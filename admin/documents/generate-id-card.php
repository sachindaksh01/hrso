<?php
require_once '../includes/auth-check.php';

$member_id = clean($_GET['id'] ?? 0);

if (!$member_id) {
    die('Invalid member ID');
}

// Get member details
$sql = "SELECT m.*, 
        d.designation_name, 
        l.level_name 
        FROM members m
        JOIN designations d ON m.designation_id = d.id
        JOIN levels l ON m.level_id = l.id
        WHERE m.id = :id AND m.status = 'approved'";

$member = $db->fetch($sql, ['id' => $member_id]);

if (!$member) {
    die('Member not found or not approved');
}

try {
    // Generate QR Code
    require_once '../../core/QRCodeGenerator.php';
    
    $qrPath = ID_CARD_PATH . 'qr_' . $member['member_id'] . '.png';
    QRCodeGenerator::generateMemberQR($member['member_id'], $qrPath);
    
    // Generate ID Card PDF
    require_once '../../core/PDFGenerator.php';
    
    $pdfPath = ID_CARD_PATH . 'id_card_' . $member['member_id'] . '.pdf';
    
    $pdfGen = new PDFGenerator();
    $pdfGen->generateIDCard($member, $qrPath, $pdfPath);
    
    // Save record
    $db->insert('generated_documents', [
        'member_id' => $member_id,
        'document_type' => 'id_card',
        'file_path' => str_replace(BASE_PATH, '', $pdfPath)
    ]);
    
    // Download PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="ID_Card_' . $member['member_id'] . '.pdf"');
    header('Content-Length: ' . filesize($pdfPath));
    readfile($pdfPath);
    exit;
    
} catch (Exception $e) {
    die('Error generating ID card: ' . $e->getMessage());
}
?>
