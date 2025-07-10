<?php
require 'vendor/autoload.php';
require_once 'config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$result = $conn->query("SELECT name, email, mobileNumber FROM users");

// Create spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Add headers
$sheet->setCellValue('A1', 'Name');
$sheet->setCellValue('B1', 'Email');
$sheet->setCellValue('C1', 'Mobile Number');

$headerStyle = [
    'font' => [
        'bold' => true,
    ],
];

$sheet->getStyle('A1:C1')->applyFromArray($headerStyle);

// Add rows
$row = 2;
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['name']);
    $sheet->setCellValue('B' . $row, $data['email']);
    $sheet->setCellValue('C' . $row, $data['mobileNumber']);
    $row++;
}

// Save the file
$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Users_Data.xlsx"');
$writer->save('php://output');
exit;

?>