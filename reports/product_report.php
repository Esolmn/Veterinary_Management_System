<?php

require_once '../database/Database.php';
require_once '../models/Product.php';
require '../plugins/fpdf/fpdf.php';

$database = new Database();
$db = $database->getConnection();
Product::setConnection($db);

$products = Product::all();

$pdf = new FPDF();
$pdf ->AddPage();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'All Products Assignments Report', 'B', 0, 'C');
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
if(count($products) > 0) {
    $i = 1;

    foreach ($products as $product) {
        $pdf->Cell(10, 10, $product->id, 1, 0, 'C');
        $pdf->Cell(30, 10, $product->product_name, 1, 0, 'C');
        $pdf->Cell(30, 10, $product->treatment_id, 1, 0, 'C');
        $pdf->Cell(30, 10, $product->cost_price, 1, 0, 'C');
        $pdf->Cell(40, 10, $product->retail_price, 1, 0, 'C');
        $pdf->Cell(30, 10, $product->quantity, 1, 0, 'C');
        $pdf->Cell(20, 10, $product->cost, 1, 0, 'C');
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

$filename = 'products.pdf';

$pdf->Output($action, $filename);