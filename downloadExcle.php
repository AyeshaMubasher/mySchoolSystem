<?php
require 'vendor/autoload.php';
require_once 'config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;

$result = $conn->query("SELECT * FROM users");

// Create spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Add headers
$sheet->setCellValue('A1', 'Name');
$sheet->setCellValue('B1', 'Email');
$sheet->setCellValue('C1', 'Mobile Number');
$sheet->setCellValue('D1', 'Iamge');

$headerStyle = [
    'font' => [
        'bold' => true,
    ],
];

$sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

// Add rows
$row = 2;
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['name']);
    $sheet->setCellValue('B' . $row, $data['email']);
    $sheet->setCellValue('C' . $row, $data['mobileNumber']);


    $imagePath = UPLOAD_DIR . $data['image_path'];

    if(file_exists($imagePath)){
        $drawing = new Drawing();
        $drawing->setPath($imagePath);
        $drawing->setCoordinates('D'.$row); 
        $drawing->setHeight(60);
        $drawing->setWorksheet($sheet);

        $sheet->getRowDimension($row)->setRowHeight(47); //set raw size according to image 
    }

    $row++;
}

// Adjust cells according to data 
$sheet->getColumnDimension('D')->setWidth(9); // set column size according to image
foreach (range('A', 'C') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

//Define table
$endRow = $row -1;
$tableRange = "A1:D$endRow";

//Create an Excle-style table
$table = new Table($tableRange);
$tableStyle = new TableStyle();
$tableStyle->setShowRowStripes(true);
$table->setStyle($tableStyle);

$sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

$sheet->getStyle('A1:D1')->getFont()->getColor()->setRGB('FFFFFF');

$sheet->getStyle('A1:D1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('203864');

$dataRange = "A2:D$endRow";
$sheet->getStyle($dataRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('dbeeff'); 

// Save the file
$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Users_Data.xlsx"');
$writer->save('php://output');
exit;

?>