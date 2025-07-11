<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

require_once 'vendor/autoload.php';
require_once 'config.php';

use setasign\Fpdi\Fpdi;

$userId = $_SESSION['id'];
$result = $conn->query("SELECT * FROM users WHERE id = $userId");
$student = $result->fetch_assoc();

// Create new FPDI instance
$pdf = new Fpdi();

$pdf->setSourceFile('cardTemplate.pdf');
$templateId = $pdf->importPage(1);
$size = $pdf->getTemplateSize($templateId);

$pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
$pdf->useTemplate($templateId);

// === Student Photo ===
$imagePath = UPLOAD_DIR . $student["image_path"];
if (file_exists($imagePath)) {
    $pdf->Image($imagePath, 32, 38.5, 23, 28); // Same placement as original (path to image,x,y,w,h)
}

// === Add School Name ===
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(32, 38);
$pdf->MultiCell(86, 5, 'My School Name', 0, 'C');

// === Student Data ===
$pdf->SetFont('Arial', '', 7);

// ID
$pdf->SetXY(80, 46);
$pdf->MultiCell(40, 5, $student['id'], 0, 'L');

// Name
$pdf->SetXY(80, 49.7);
$pdf->MultiCell(40, 5, $student['name'], 0, 'L');

// Mobile Number
$pdf->SetXY(80, 54.2);
$pdf->MultiCell(40, 5, $student['mobileNumber'], 0, 'L');

// Email
$pdf->SetXY(80, 58.5);
$pdf->MultiCell(40, 5, $student['email'], 0, 'L');

// Output the PDF
$pdf->Output('I', 'student_card.pdf');
?>
