<?php 
    require_once '../database/Database.php';
    require_once '../models/Treatment.php';

    $database = new Database();
    $db = $database->getConnection();
    Treatment::setConnection($db);

    $id = $_GET['id'];
    $treatment = Treatment::find($id);
    if(!$treatment){
        header('Location: index.php');
        exit();
    }
    
    include  '../layout/header.php';


    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            //display website
?>

<?php
    $destroytreatment = $treatment->delete($id);

    if($destroytreatment){
        echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Treatment record has been deleted',
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
                    text: 'Failed to delete treatment record, try again.',
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