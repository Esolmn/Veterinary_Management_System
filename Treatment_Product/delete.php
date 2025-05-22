<?php 

    session_start();
    require_once '../database/Database.php';
    require_once '../models/Product.php';

    $database = new Database();
    $db = $database->getConnection();

    Product::setConnection($db);

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            //display website
        $id = $_GET['id'];
        $product = Product::find($id);
?>

<?php

    if(!$product){
        header('Location: index.php');
        exit();
    }

    include  '../layout/header.php';

    $destroyproduct = $product->delete($id);

    if($destroyproduct){
        echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Product record deleted successfully.',
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
                    text: 'Failed to delete product record, try again.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = 'index.php';
                });
            </script>";
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