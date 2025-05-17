<?php

require_once '../database/Database.php';
require_once '../models/Product.php';
require_once '../models/Treatment.php';
require '../plugins/fpdf/fpdf.php';

$database = new Database();
$db = $database->getConnection();
Product::setConnection($db);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $product = Product::find($id);
} else {
    die('Product ID not provided.');
}

if (!$product) {
    die('Product not found.');
}

$pdf = new FPDF();
$pdf ->AddPage();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Product Assignment Report', 'B', 0, 'C');
$pdf->Ln(20);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 10, 'ID', 1, 0, 'C');
$pdf->Cell(30, 10, 'Product', 1, 0, 'C');
$pdf->Cell(30, 10, 'Treatment ID', 1, 0, 'C');
$pdf->Cell(30, 10, 'Cost Price', 1, 0, 'C');
$pdf->Cell(40, 10, 'Retail Price', 1, 0, 'C');
$pdf->Cell(30, 10, 'Quantity', 1, 0, 'C');
$pdf->Cell(20, 10, 'Cost', 1, 0, 'C');
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 10, $product->id, 1, 0, 'C');
$pdf->Cell(30, 10, $product->product_name, 1, 0, 'C');
$pdf->Cell(30, 10, $product->treatment_id, 1, 0, 'C');
$pdf->Cell(30, 10, $product->cost_price, 1, 0, 'C');
$pdf->Cell(40, 10, $product->retail_price, 1, 0, 'C');
$pdf->Cell(30, 10, $product->quantity, 1, 0, 'C');
$pdf->Cell(20, 10, $product->cost, 1, 0, 'C');
$pdf->Ln(20);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Treatment Allotment Preview', 'B', 0, 'C');
$pdf->Ln(20);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 10, 'ID', 1, 0, 'C');
$pdf->Cell(40, 10, 'Treatment Name', 1, 0, 'C');  
$pdf->Cell(20, 10, 'Pet ID', 1, 0, 'C');
$pdf->Cell(30, 10, 'Diagnosis', 1, 0, 'C');
$pdf->Cell(40, 10, 'Description', 1, 0, 'C');
$pdf->Cell(30, 10, 'Date', 1, 0, 'C');
$pdf->Cell(20, 10, 'Doctor Fee', 1, 0, 'C');
$pdf->Ln();

$treatments = $product->treatments();
$pdf->SetFont('Arial','', 10);

if(count($treatments) > 0) {

    foreach ($treatments as $treatment) {
        
        $pdf->Cell(10, 10, $treatment->id, 1, 0, 'C');
        $pdf->Cell(40, 10, $treatment->treatment_name, 1, 0, 'C');
        $pdf->Cell(20, 10, $treatment->pet_id, 1, 0, 'C');
        $pdf->Cell(30, 10, $treatment->diagnosis, 1, 0, 'C');
        $pdf->Cell(40, 10, $treatment->description, 1, 0, 'C');
        $pdf->Cell(30, 10, $treatment->date, 1, 0, 'C');
        $pdf->Cell(20, 10, $treatment->doctor_fee, 1, 0, 'C');
        $pdf->Ln();
    }
}

else{
    $pdf->Cell(190, 10, 'No products found', 1, 0, 'C');
    }


if(isset($_GET['action'])){
    $action = $_GET['action'];
} else{
    $action = 'I';
}

$filename = 'product_report_' . $product->id . '.pdf';

$pdf->Output($action, $filename);