<?php

require_once '../database/Database.php';
require_once '../models/User.php';
require '../plugins/fpdf/fpdf.php';

$database = new Database();
$db = $database->getConnection();
User::setConnection($db);

if(isset($_GET['status'])){
    $status = $_GET['status'];
} else {
    $status = 'all'; 
}

if ($status === 'active') {
    $users = array_filter(User::allPetOwners(), function($user) {
        return $user->status === 'active';
    });
} elseif ($status === 'inactive') {
    $users = array_filter(User::allPetOwners(), function($user) {
        return $user->status === 'inactive';
    });
} else {
    $users = User::allPetOwners(); 
}

$pdf = new FPDF();
$pdf ->AddPage();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Users Report', 'B', 0, 'C');
$pdf->Ln(20);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 10, 'ID', 1, 0, 'C');
$pdf->Cell(60, 10, 'Full Name', 1, 0, 'C');  
$pdf->Cell(50, 10, 'Email', 1, 0, 'C');
$pdf->Cell(30, 10, 'Role', 1, 0, 'C');
$pdf->Cell(30, 10, 'Status', 1, 0, 'C');
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
if(count($users) > 0) {
    $i = 1;

    foreach ($users as $user) {
        $pdf->Cell(20, 10, $i++, 1, 0, 'C');
        $pdf->Cell(60, 10, $user->name, 1, 0, 'C');
        $pdf->Cell(50, 10, $user->email, 1, 0, 'C');
        $pdf->Cell(30, 10, $user->role, 1, 0, 'C');
        $pdf->Cell(30, 10, $user->status, 1, 0, 'C');
        $pdf->Ln();
    }
}
else{
    $pdf->Cell(40, 10, 'No users found', 1, 0, 'C');
}


if(isset($_GET['action'])){
    $action = $_GET['action'];
} else{
    $action = 'I';
}

if($status == "active"){
    $filename = "active_users.pdf";
}else if($status == "inactive"){
    $filename = "inactive_users.pdf";
}else{
    $filename = "all_users.pdf";
}

$pdf->Output($action, $filename);