<?php
require_once '../includes/auth-check.php';

// Install: composer require phpoffice/phpspreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once '../../vendor/autoload.php';

// Get members data
$sql = "SELECT 
        m.member_id,
        m.full_name,
        m.gender,
        m.mobile,
        m.email,
        d.designation_name,
        l.level_name,
        s.state_name,
        m.status,
        m.membership_start_date,
        m.membership_expiry_date,
        m.payment_amount
        FROM members m
        JOIN designations d ON m.designation_id = d.id
        JOIN levels l ON m.level_id = l.id
        LEFT JOIN states s ON m.state_id = s.id
        WHERE m.status = 'approved'
        ORDER BY m.created_at DESC";

$members = $db->fetchAll($sql);

// Create spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers
$sheet->setCellValue('A1', 'Member ID');
$sheet->setCellValue('B1', 'Full Name');
$sheet->setCellValue('C1', 'Gender');
$sheet->setCellValue('D1', 'Mobile');
$sheet->setCellValue('E1', 'Email');
$sheet->setCellValue('F1', 'Designation');
$sheet->setCellValue('G1', 'Level');
$sheet->setCellValue('H1', 'State');
$sheet->setCellValue('I1', 'Status');
$sheet->setCellValue('J1', 'Start Date');
$sheet->setCellValue('K1', 'Expiry Date');
$sheet->setCellValue('L1', 'Amount Paid');

// Style headers
$sheet->getStyle('A1:L1')->getFont()->setBold(true);
$sheet->getStyle('A1:L1')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF667EEA');
$sheet->getStyle('A1:L1')->getFont()->getColor()->setARGB('FFFFFFFF');

// Add data
$row = 2;
foreach ($members as $member) {
    $sheet->setCellValue('A' . $row, $member['member_id']);
    $sheet->setCellValue('B' . $row, $member['full_name']);
    $sheet->setCellValue('C' . $row, $member['gender']);
    $sheet->setCellValue('D' . $row, $member['mobile']);
    $sheet->setCellValue('E' . $row, $member['email']);
    $sheet->setCellValue('F' . $row, $member['designation_name']);
    $sheet->setCellValue('G' . $row, $member['level_name']);
    $sheet->setCellValue('H' . $row, $member['state_name']);
    $sheet->setCellValue('I' . $row, $member['status']);
    $sheet->setCellValue('J' . $row, $member['membership_start_date']);
    $sheet->setCellValue('K' . $row, $member['membership_expiry_date']);
    $sheet->setCellValue('L' . $row, $member['payment_amount']);
    $row++;
}

// Auto-size columns
foreach (range('A', 'L') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Create writer
$writer = new Xlsx($spreadsheet);

// Send to browser
$filename = 'Members_Export_' . date('Y-m-d_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>
