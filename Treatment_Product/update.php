<?php 

    include '..\layout\header.php'; 
    require_once '..\database\Database.php';
    require_once '..\models\Product.php';

    $database = new Database();
    $db = $database->getConnection();

    Product::setConnection($db);  

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            //display website
            $id = $_GET['id'];
            $product = Product::find($id);
?>

<?php

    if($product) {

        $data = [
            'product_name' => $_POST['product_name'],
            'treatment_id' => $_POST['treatment_id'],
            'cost_price' => $_POST['cost_price'],
            'retail_price' => $_POST['retail_price'],
            'quantity' => $_POST['quantity'],
            'cost' => ($_POST['retail_price'] * $_POST['quantity']),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $updateProduct = $product->update($data);

        if($updateProduct) {
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Product record has been updated.',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'index.php';
                    });
                </script>";
        }

        else {
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to update product record, try again.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'index.php';
                    });
                </script>";
        }

    } else {
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Product not found.',
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
    include '..\layout\footer.php'; 
?>