<?php 

    include '../layout/header.php';
    require_once '../database/Database.php';
    require_once '../models/Treatment.php';

    $database = new Database();
    $db = $database->getConnection();

    Treatment::setConnection($db);

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            //display website
?>

<?php

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $data = [
            'treatment_name' => $_POST['treatment_name'],
            'diagnosis' => $_POST['diagnosis'],
            'description' => $_POST['description'],
            'date' => $_POST['date'],
            'pet_id' => $_POST['pet_id'],
            'doctor_fee' => $_POST['doctor_fee']
        ];

        $treatment = Treatment::create($data);

        if($treatment){
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Treatment record has been created.',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'index.php';
                    });
                </script>";
        }
      
        else{
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to create treatment record, try again.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'create.php';
                    });
                </script>";
        }
    }
    else {
        echo "<script>
            Swal.fire({
                title: 'Warning!',
                text: 'Request method not allowed.',
                icon: 'warning',
                showConfirmButton: true,
            }).then(function() {
                window.location.href = 'create.php';
            });
        </script>";
        exit();
    }
    
?>

<?php
    } else{
        echo "<script>
        Swal.fire({
            title: 'Warning!',
            text: 'You cannot access this page.',
            icon: 'warning',
            showConfirmButton: true,
        }).then(function() {
            window.location.href = '../index.php';
        });
    </script>";
        exit();
    }

    include '../layout/footer.php';
?>