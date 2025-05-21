<?php 
    session_start();
    require_once '../database/Database.php';
    require_once '../models/AvailableDate.php';

    $db = new Database();
    $conn = $db->getConnection();
    AvailableDate::setConnection($conn); 

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            //display website
?>

<?php
    $availableDate = AvailableDate::find($_GET['id']);

    if (!$availableDate) {
        header('Location: index.php');
        exit();
    }

    include '../layout/header.php';

    $hasAppointment = AvailableDate::hasAppointments($availableDate->id); //pagcheck kung may appointment ung available date
    
    if($availableDate) {

        if($hasAppointment) {
            echo "<script>
                    Swal.fire({
                        title: 'Warning!',
                        text: 'You cannot delete this date because it has an appointment.',
                        icon: 'warning',
                        showConfirmButton: true,
                    }).then(function() {
                        window.location.href = 'index.php';
                    });
                </script>";
            exit();
        }

        $deleteDate = $availableDate->delete();

        if($deleteDate) {
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Available date has been deleted.',
                        icon: 'success',
                        showConfirmButton: true,
                    }).then(function() {
                        window.location.href = 'index.php';
                    });
                </script>";
        }
        else {
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to delete available date.',
                        icon: 'error',
                        showConfirmButton: true,
                    }).then(function() {
                        window.location.href = 'index.php';
                    });
                </script>";
        }
    }
    else {
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Available date not found.',
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
            window.location.href = '../index.php';
        });
    </script>";
        exit();
    }

    include  '../layout/footer.php'; 
?>