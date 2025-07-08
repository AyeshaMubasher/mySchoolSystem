<?php
require('fpdf/fpdf.php'); 
require_once 'config.php';

// Create FPDF object
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Title
$pdf->Cell(0, 10, 'Registered Users', 0, 1, 'C');
$pdf->Ln(10);

// Table header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Name', 1);
$pdf->Cell(70, 10, 'Email', 1);
$pdf->Cell(50, 10, 'Mobile Number', 1);
$pdf->Ln();

// Fetch users
$result = $conn->query("SELECT name, email, mobileNumber FROM users");

$pdf->SetFont('Arial', '', 12);

while ($row = $result->fetch_assoc()) {
    $pdf->Cell(60, 10, $row['name'], 1);
    $pdf->Cell(70, 10, $row['email'], 1);
    $pdf->Cell(50, 10, $row['mobileNumber'], 1);
    $pdf->Ln();
}

// Output PDF
$pdf->Output('D', 'users.pdf'); // 'D' forces download
?>
