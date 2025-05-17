<?php 
    include '../../layout/header.php';
    require_once '../../database/Database.php';
    require_once '../../models/User.php';

    $db = new Database();
    $conn = $db->getConnection();
    User::setConnection($conn); 

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin') {
            //display website
?>

<?php

    $user = User::find($_GET['id']);

    if(!$user) {
        header('Location: index.php');
        exit();
    }

    if ($user->delete()) {
        echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Account has been deleted',
            icon: 'success',
            showConfirmButton: true,
        }).then(function() {
            window.location.href = 'index.php';
        });
        </script>";
    } else {
        echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Failed to delete account',
            icon: 'error',
            showConfirmButton: true,
        }).then(function() {
            window.location.href = 'index.php';
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
            window.location.href = '../../index.php';
        });
    </script>";
        exit();
    }

    include '../../layout/footer.php';
?>