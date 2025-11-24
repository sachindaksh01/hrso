<?php
require_once '../includes/auth-check.php';

$member_id = clean($_GET['id'] ?? 0);

if (!$member_id) {
    die('Invalid member ID');
}

// Get member details
$sql = "SELECT m.*, 
        d.designation_name 
        FROM members m
        JOIN designations d ON m.designation_id = d.id
        WHERE m.id = :id AND m.status = 'approved'";

$member = $db->fetch($sql, ['id' => $member_id]);

if (!$member) {
    die('Member not found or not approved');
}

try {
    require_once '../../core/PDFGenerator.php';
    
    $pdfPath = OATH_LETTER_PATH . 'oath_' . $member['member_id'] . '.pdf';
    
    $pdfGen = new PDFGenerator();
    $pdfGen->generateOathLetter($member, $pdfPath);
    
    // Save record
    $db->insert('generated_documents', [
        'member_id' => $member_id,
        'document_type' => 'oath_letter',
        'file_path' => str_replace(BASE_PATH, '', $pdfPath)
    ]);
    
    // Download PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="Oath_Letter_' . $member['member_id'] . '.pdf"');
    header('Content-Length: ' . filesize($pdfPath));
    readfile($pdfPath);
    exit;
    
} catch (Exception $e) {
    die('Error generating oath letter: ' . $e->getMessage());
}
?>
