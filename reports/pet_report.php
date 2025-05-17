<?php

require_once '../database/Database.php';
require_once '../models/User.php';
require_once '../models/Pet.php';
require_once '../models/Treatment.php';
require '../plugins/fpdf/fpdf.php';

$database = new Database();
$db = $database->getConnection();
Pet::setConnection($db);
User::setConnection($db);
Treatment::setConnection($db);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $pet = Pet::find($id);
} else {
    die('Pet ID not provided.');
}

if (!$pet) {
    die('Pet not found.');
}

$pdf = new FPDF();
$pdf ->AddPage();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Pet Report', 'B', 0, 'C');
$pdf->Ln(20);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 10, 'Name', 1, 0, 'C');
$pdf->Cell(30, 10, 'Owner', 1, 0, 'C');
$pdf->Cell(30, 10, 'Birthdate', 1, 0, 'C');
$pdf->Cell(30, 10, 'Breed', 1, 0, 'C');
$pdf->Cell(30, 10, 'Species', 1, 0, 'C');
$pdf->Cell(20, 10, 'Gender', 1, 0, 'C');
$pdf->Cell(20, 10, 'Status', 1, 0, 'C');
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 10, $pet->name, 1, 0, 'C');
$owner = User::find($pet->user_id);
$owner_first_name = $owner ? explode(' ', trim($owner->name))[0] : 'Unknown';
$pdf->Cell(30, 10, $owner_first_name, 1, 0, 'C');
$pdf->Cell(30, 10, $pet->birthdate, 1, 0, 'C');
$pdf->Cell(30, 10, $pet->breed, 1, 0, 'C');
$pdf->Cell(30, 10, $pet->specie, 1, 0, 'C');
$pdf->Cell(20, 10, $pet->gender, 1, 0, 'C');
$pdf->Cell(20, 10, $pet->status, 1, 0, 'C');
$pdf->Ln(20);



$treatments = Treatment::where('pet_id', '=', $pet->id);
$pdf->SetFont('Arial','', 10);

if(count($treatments) > 0){

    foreach ($treatments as $treatment) {

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Treatment ID: ' . $treatment->id, 'B', 0, 'C');
        $pdf->Ln(20);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(114, 10, 'Treatment', 1, 0, 'C');
        $pdf->Cell(38, 10, 'Date', 1, 0, 'C');
        $pdf->Cell(38, 10, 'Doctor Fee', 1, 0, 'C');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(114, 10, $treatment->treatment_name, 1, 0, 'C');
        $pdf->Cell(38, 10, $treatment->date, 1, 0, 'C');
        $pdf->Cell(38, 10, $treatment->doctor_fee, 1, 0, 'C');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 10, 'Diagnosis:', 1, 0, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(150, 10, $treatment->diagnosis, 1, 'C');

        $pdf->SetFont('Arial', 'B', 10);
        $descHeaderWidth = 40;
        $descCellWidth = 150;
        $descLineHeight = 10;

        // Calculate the number of lines for the description
        $nbDescLines = $pdf->GetStringWidth($treatment->description) > 0
            ? ceil($pdf->GetStringWidth($treatment->description) / $descCellWidth)
            : 1;

        // For better accuracy, use this helper (if available in your FPDF version):
        if (method_exists($pdf, 'NbLines')) {
            $nbDescLines = $pdf->$nbDescLines($descCellWidth, $treatment->description);
        }
        $descHeight = max(1, $nbDescLines) * $descLineHeight;

        // Save current X/Y
        $x = $pdf->GetX();
        $y = $pdf->GetY();

        // Print the header cell with the calculated height
        $pdf->Cell($descHeaderWidth, $descHeight, 'Description:', 1, 0, 'C');

        // Print the description with MultiCell
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell($descCellWidth, $descLineHeight, $treatment->description, 1, 'C');

        $pdf->Ln(5);

        $products = Treatment::find($treatment->id)->products();
        if(count($products) > 0) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 10, 'Products Assigned', 1, 0, 'C');
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 10, 'Product ID', 1, 0, 'C');
            $pdf->Cell(65, 10, 'Product Name', 1, 0, 'C');
            $pdf->Cell(47.5, 10, 'Quantity', 1, 0, 'C');
            $pdf->Cell(47.5, 10, 'Cost', 1, 0, 'C');
            $pdf->Ln();

            foreach ($products as $product) {
                $pdf->SetFont('Arial','', 8);
                $pdf->Cell(30, 10, $product->id, 1, 0, 'C');
                $pdf->Cell(65, 10, $product->product_name, 1, 0, 'C');
                $pdf->Cell(47.5, 10, $product->quantity, 1, 0,'C');
                $pdf->Cell(47.5, 10, $product->cost,1 ,0,'C');
                $pdf->Ln();
            }
        } else {
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(190 ,10 ,'No products assigned...',1 ,0 ,'C');
        }

        $pdf->Ln(10);
    }
} else {
    $pdf->Cell(190, 10, 'No treatments available...', 1, 0, 'C');
}

if(isset($_GET['action'])){
    $action = $_GET['action'];
} else{
    $action = 'I';
}

$filename = 'pet_report_' . $pet->id . '.pdf';

$pdf->Output($action, $filename);