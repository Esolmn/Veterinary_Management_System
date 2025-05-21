<?php 
    session_start();
    require_once '../database/Database.php';
    require_once '../models/Pet.php';
    require_once '../models/User.php';

    $db = new Database();
    $conn = $db->getConnection();
    User::setConnection($conn); 

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
        //display website
?>

<?php

    $pet = Pet::find($_GET['id']);

    if(!$pet) {
        header('Location: index.php');
        exit();
    }

    include '../layout/header.php';

    $pet_owner = User::find($pet->user_id); //pangkuha ng pet owner
    $owner_status = $pet_owner && $pet_owner->status == 'inactive'; //check if the pet owner is inactive

    if (!$pet_owner || $owner_status ) { //check if walang pet owner or inactive ung pet owner
        $pet->delete();

        echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Pet Detail has been deleted',
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
            text: 'Failed to delete pet detail, Pet owner is still active',
            icon: 'error',
            showConfirmButton: true,
        }).then(function() {
            window.location.href = 'index.php';
        });
        </script>";
    }

?>
<?php include '../layout/footer.php'; ?>

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
    }
?>