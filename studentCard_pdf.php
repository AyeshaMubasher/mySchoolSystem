<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

require('fpdf/fpdf.php');
require_once 'config.php';

// Get student data
$userId = $_SESSION['id'];
$result = $conn->query("SELECT * FROM users WHERE id = $userId");
$student = $result->fetch_assoc();

// Create landscape ID card
$pdf = new FPDF('L', 'mm', [86, 58]);
//$pdf = new FPDF();
$pdf->AddPage();

//set backgroun
$pdf->SetFillColor(176, 224, 230); // Light blue background
$pdf->Rect(0, 0, 86, 58, 'F');     // Card size (L, 86 x 58 mm)

$pdf->SetDrawColor(33, 64, 154);   // Custom blue border
$pdf->SetLineWidth(0.8);           // Medium thickness
$pdf->Rect(0.5, 0.5, 85, 57, 'D'); // Slight inset border

// === COLORS ===
$headerColor = [33, 64, 154];
$textColor = [0, 0, 0];

// === HEADER ===
$pdf->SetFillColor(...$headerColor);
$pdf->Rect(0, 0, 86, 12, 'F');
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetXY(5, 3);
$pdf->Cell(70, 6, 'My School', 0, 1, 'L');

// === IMAGE ===
$imagePath = UPLOAD_DIR . $student["image_path"];
if (file_exists($imagePath)) {
    $pdf->Image($imagePath, 5, 15, 18, 24); // X, Y, W, H
} else {
    $pdf->Rect(5, 15, 18, 24);
    $pdf->SetXY(5, 27);
    $pdf->SetFont('Arial', 'I', 6);
    $pdf->Cell(18, 5, 'No Image', 0, 0, 'C');
}

// === STUDENT INFO using MultiCell ===
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(...$textColor);

$infoX = 25;
$infoY = 15;
$lineHeight = 5;
$labelWidth = 20;
$valueWidth = 40;

// User ID
$pdf->SetXY($infoX, $infoY);
$pdf->MultiCell($labelWidth, $lineHeight, "User ID :", 0, 'L');
$pdf->SetXY($infoX + $labelWidth, $infoY);
$pdf->MultiCell($valueWidth, $lineHeight, $student['id'], 0, 'L');

// Name
$pdf->SetXY($infoX, $infoY + $lineHeight);
$pdf->MultiCell($labelWidth, $lineHeight, "Name :", 0, 'L');
$pdf->SetXY($infoX + $labelWidth, $infoY + $lineHeight);
$pdf->MultiCell($valueWidth, $lineHeight, $student['name'], 0, 'L');

// Phone
$pdf->SetXY($infoX, $infoY + 2 * $lineHeight);
$pdf->MultiCell($labelWidth, $lineHeight, "Phone :", 0, 'L');
$pdf->SetXY($infoX + $labelWidth, $infoY + 2 * $lineHeight);
$pdf->MultiCell($valueWidth, $lineHeight, $student['mobileNumber'], 0, 'L');

// Email (optional - fit only if needed)
$pdf->SetXY($infoX, $infoY + 3 * $lineHeight);
$pdf->MultiCell($labelWidth, $lineHeight, "Email :", 0, 'L');
$pdf->SetXY($infoX + $labelWidth, $infoY + 3 * $lineHeight);
$pdf->MultiCell($valueWidth, $lineHeight, $student['email'], 0, 'L');

$pdf->Output(); // this is for view only 
//$pdf->Output('D', 'userCard.pdf');
?>
