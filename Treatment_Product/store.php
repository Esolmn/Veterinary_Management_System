<?php 
    include '../layout/header.php';
    require_once '../database/Database.php';
    require_once '../models/Treatment.php';

    $database = new Database();
    $db = $database->getConnection();

    Product::setConnection($db);

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            //display website
?>

<?php  

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $data = [
            'product_name' => $_POST['product_name'],
            'treatment_id' => $_POST['treatment_id'],
            'cost_price' => $_POST['cost_price'],
            'retail_price' => $_POST['retail_price'],
            'quantity' => $_POST['quantity'],
            'cost' => ($_POST['retail_price'] * $_POST['quantity'])
        ];

        $product = Product::create($data);

        if($product){
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Product record has been created.',
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
                        text: 'Failed to create product record, try again.',
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