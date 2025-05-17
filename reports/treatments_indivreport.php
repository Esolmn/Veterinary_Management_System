<?php

require_once '../database/Database.php';
require_once '../models/Treatment.php';
require_once '../models/Pet.php';
require '../plugins/fpdf/fpdf.php';

$database = new Database();
$db = $database->getConnection();
Treatment::setConnection($db);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $treatment = Treatment::find($id);
} else {
    die('Treatment ID not provided.');
}

if (!$treatment) {
    die('Treatment not found.');
}

Pet::setConnection($db);
$pet = Pet::find($treatment->pet_id);
$petName = $pet ? $pet->name : 'Unknown';

$pdf = new FPDF();
$pdf ->AddPage();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Treatment Report', 'B', 0, 'C');
$pdf->Ln(20);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 10, 'ID', 1, 0, 'C');
$pdf->Cell(84, 10, 'Treatment', 1, 0, 'C');
$pdf->Cell(38, 10, 'Date', 1, 0, 'C');
$pdf->Cell(38, 10, 'Doctor Fee', 1, 0, 'C');
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 10, $treatment->id, 1, 0, 'C');
$pdf->Cell(84, 10, $treatment->treatment_name, 1, 0, 'C');
$pdf->Cell(38, 10, $treatment->date, 1, 0, 'C');
$pdf->Cell(38, 10, $treatment->doctor_fee, 1, 0, 'C');
$pdf->Ln(15);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 10, 'Diagnosis:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(150, 10, $treatment->diagnosis, 0, 'L');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 10, 'Description:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(150, 10, $treatment->description, 0, 'L');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 10, 'Administered To:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(150, 10, $treatment->pet_id . ' - ' . $petName, 0, 1, 'L');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Products Assigned', 'B', 0, 'C');
$pdf->Ln(20);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 10, 'Product ID', 1, 0, 'C');
$pdf->Cell(65, 10, 'Product Name', 1, 0, 'C');  
$pdf->Cell(47.5, 10, 'Quantity', 1, 0, 'C');
$pdf->Cell(47.5, 10, 'Cost', 1, 0, 'C');
$pdf->Ln();

$products = $treatment->products();
$totalCost = 0;

$pdf->SetFont('Arial','', 10);
if(count($products) > 0) {

    foreach ($products as $product) {
        $pdf->Cell(30, 10, $product->id, 1, 0, 'C');
        $pdf->Cell(65, 10, $product->product_name, 1, 0, 'C');
        $pdf->Cell(47.5, 10, $product->quantity, 1, 0, 'C');
        $pdf->Cell(47.5, 10, $product->cost, 1, 0, 'C');
        $pdf->Ln();
        $totalCost += $product->cost;
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(95, 10, 'Total Cost (with Doctor Fee)', 1, 0, 'C');
    $pdf->Cell(95, 10, number_format($totalCost + $treatment->doctor_fee, 2), 1, 0, 'C');}
else{
    $pdf->Cell(190, 10, 'No available data...', 1, 0, 'C');
}


if(isset($_GET['action'])){
    $action = $_GET['action'];
} else{
    $action = 'I';
}

$filename = 'treatment_report_' . $treatment->id . '.pdf';

$pdf->Output($action, $filename);