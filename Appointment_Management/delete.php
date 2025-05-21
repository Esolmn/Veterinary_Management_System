<?php
    include '../layout/header.php';
    require_once '../database/Database.php';
    require_once '../models/Appointment.php';
    require_once '../models/AvailableDate.php';

    $db = new Database();
    $conn = $db->getConnection();
    Appointment::setConnection($conn);
    AvailableDate::setConnection($conn);

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
        
        $aptID = $_GET['id'];
        $apt = Appointment::find($aptID);
        $aptdate = AvailableDate::find($apt->aptdate_id);
?>

<?php
    if($apt){ // if record is found

        $aptTime = strtotime($aptdate->date .  ' 00:00:00'); // 
        $timeLimit = $aptTime + 86400; //nag add 24hrs para icompare

        if(time() >= $timeLimit){ // if late na ng 24hrs sa current time ung appointment    
            $apt->delete();
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Appointment request has been deleted.',
                        icon: 'success',
                        confirmationButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'index.php';
                    });
                </script>";
        }
        else { // if record is 24 hrs within appointment date
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Cannot delete appointment request because it is within 24 hours.',
                        icon: 'error',
                        confirmationButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'index.php';
                    });
                </script>";
        }
    }
    else { // if record is not found
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to delete appointment request.',
                        icon: 'error',
                        confirmationButtonText: 'Ok'
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

    include  '../layout/footer.php'; 
?>