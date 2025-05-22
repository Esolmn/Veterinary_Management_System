<?php
    session_start();
    require_once '../database/Database.php';
    require_once '../models/Appointment.php';

    $db = new Database();
    $conn = $db->getConnection();
    Appointment::setConnection($conn);

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
        
        $aptID = $_GET['id'];
        $apt = Appointment::find($aptID);
?>

<?php
    if(!$apt) {
        header('Location: index.php');
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $data = [
            'status' => $_POST['status'],
            'declined_reason' => $_POST['declined_reason'] ?? null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($_POST['status'] === 'Declined') {
            $data['declined_by'] = $_SESSION['role'];
        } else {
            $data['declined_by'] = null;
        }

        $apt->update($data);

        include '../layout/header.php';

        if($apt){
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Appointment status has been updated.',
                        icon: 'success',
                        confirmationButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'index.php';
                    });
                </script>";
        }
        else {
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to update appointment status, try again.',
                        icon: 'error',
                        confirmationButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'edit.php';
                    });
                </script>";
        }
    }
    else {
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Request method not allowed.',
                    icon: 'error',
                    confirmationButtonText: 'Ok'
                }).then(function() {
                    window.location = 'edit.php';
                });
            </script>";
    }

?>

<?php 
    } else{
        include '../layout/header.php';
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

    include  '../layout/footer.php'; 
?>