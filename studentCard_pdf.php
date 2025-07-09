<?php

session_start();
if(isset($_SESSION['id'])){
    $userId = $_SESSION['id'];
}
else{
    header("Location: index.php");
    exit();
}

require('fpdf/fpdf.php'); 

// Connect to DB
require_once 'config.php';

// Fetch one user record

$result = $conn->query("SELECT * FROM users WHERE id = $userId ");
$student = $result->fetch_assoc();

$pdf = new FPDF('P','mm',[90, 60]); // ID card size
$pdf->AddPage();

// Colors
$headerColor = [40, 64, 163]; // Blue
$textColor = [0, 0, 0];
$nameColor = [40, 64, 163];

// === Header ===
$pdf->SetFillColor(...$headerColor);
$pdf->Rect(0, 0, 90, 15, 'F'); // Blue header
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetXY(20, 5);
$pdf->Cell(60, 5, 'My School', 0, 1, 'L');

// === Student Info ===
$pdf->SetTextColor(...$textColor);
$pdf->SetFont('Arial', '', 9);

$y = 20;
$pdf->SetXY(5, $y);
$pdf->Cell(30, 5, 'User ID :', 0, 0);
$pdf->Cell(40, 5, $student['id'], 0, 1);

$pdf->SetX(5);
$pdf->Cell(30, 5, 'Name :', 0, 0);
$pdf->Cell(40, 5, $student['name'], 0, 1);

$pdf->SetX(5);
$pdf->Cell(30, 5, 'Phone :', 0, 0);
$pdf->Cell(40, 5, $student['mobileNumber'], 0, 1);

$pdf->SetX(5);
$pdf->Cell(30, 5, 'Email :', 0, 0);
$pdf->Cell(40, 5, $student['email'], 0, 1);

/*
// === Profile Image ===
$imagePath = $student['photo']; // e.g., 'uploads/photo1.jpg'
if (file_exists($imagePath)) {
    $pdf->Image($imagePath, 60, 20, 20, 25); // X, Y, W, H
} else {
    $pdf->Rect(60, 20, 20, 25); // Placeholder box
    $pdf->SetXY(60, 33);
    $pdf->SetFont('Arial', 'I', 7);
    $pdf->Cell(20, 5, 'No Image', 0, 0, 'C');
}

// === Name at Bottom ===
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(...$nameColor);
$pdf->SetXY(5, 50);
$pdf->Cell(80, 8, $student['name'], 0, 1, 'C');
*/
$pdf->Output();
?>
