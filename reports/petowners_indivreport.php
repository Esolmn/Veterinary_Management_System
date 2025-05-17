<?php

require_once '../database/Database.php';
require_once '../models/User.php';
require '../plugins/fpdf/fpdf.php';

$database = new Database();
$db = $database->getConnection();
User::setConnection($db);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user = User::find($id);
} else {
    die('User ID not provided.');
}

if (!$user) {
    die('User not found.');
}

$pdf = new FPDF();
$pdf ->AddPage();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Pet Owner Report', 'B', 0, 'C');
$pdf->Ln(20);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 10, 'ID', 1, 0, 'C');
$pdf->Cell(60, 10, 'Name', 1, 0, 'C');
$pdf->Cell(50, 10, 'Email', 1, 0, 'C');
$pdf->Cell(30, 10, 'Role', 1, 0, 'C');
$pdf->Cell(30, 10, 'Status', 1, 0, 'C');
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 10, $user->id, 1, 0, 'C');
$pdf->Cell(60, 10, $user->name, 1, 0, 'C');
$pdf->Cell(50, 10, $user->email, 1, 0, 'C');
$pdf->Cell(30, 10, $user->role, 1, 0, 'C');
$pdf->Cell(30, 10, $user->status, 1, 0, 'C');
$pdf->Ln(20);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Listed Pets', 'B', 0, 'C');
$pdf->Ln(20);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, 10, 'Pet Name', 1, 0, 'C');  
$pdf->Cell(30, 10, 'Breed', 1, 0, 'C');
$pdf->Cell(35, 10, 'Species', 1, 0, 'C');
$pdf->Cell(20, 10, 'Gender', 1, 0, 'C');
$pdf->Cell(30, 10, 'Birthdate', 1, 0, 'C');
$pdf->Cell(20, 10, 'Status', 1, 0, 'C');
$pdf->Ln();

$pets = Pet::where('user_id', '=', $id);
$pdf->SetFont('Arial','', 10);

if(count($pets) > 0) {

    foreach ($pets as $pet) {
        $pdf->Cell(35, 10, $pet->name, 1, 0, 'C');
        $pdf->Cell(30, 10, $pet->breed, 1, 0, 'C');
        $pdf->Cell(35, 10, $pet->specie, 1, 0, 'C');
        $pdf->Cell(20, 10, $pet->gender, 1, 0, 'C');
        $pdf->Cell(30, 10, $pet->birthdate, 1, 0, 'C');
        $pdf->Cell(20, 10, $pet->status, 1, 1, 'C');
        $pdf->Ln();
    }
}
else{
    $pdf->Cell(190, 10, 'No data available...', 1, 0, 'C');
}

if(isset($_GET['action'])){
    $action = $_GET['action'];
} else{
    $action = 'I';
}

$filename = 'pet_owner_report_' . $user->id . '.pdf';

$pdf->Output($action, $filename);