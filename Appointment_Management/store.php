<?php
    session_start();
    require_once '../database/Database.php';
    require_once '../models/Appointment.php';
    
    $db = new Database();
    $conn = $db->getConnection();
    Appointment::setConnection($conn);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
        header('Location: create.php');
        exit();
    }

    $services_temp = $_POST['services_needed'];
    $services = implode(", ", $services_temp);

    $apt = Appointment::create([
        'user_id' => $_POST['user_id'],
        'pet_id' => $_POST['pet_id'],
        'aptdate_id' => $_POST['aptdate_id'],
        'status' => $_POST['status'],
        'services_needed' => $services
    ]);

    include '../layout/header.php';

    if($apt) {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'pet_owner') {
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Appointment has been booked.',
                    icon: 'success',
                    confirmationButtonText: 'Ok'
                }).then(function() {
                    window.location = '../Pet_Owner_Dashboard/appointments.php';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Appointment has been booked.',
                    icon: 'success',
                    confirmationButtonText: 'Ok'
                }).then(function() {
                    window.location = 'index.php';
                });
            </script>";
        }
    }
    else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Failed to book appointment, try again.',
                icon: 'error',
                confirmationButtonText: 'Ok'
            }).then(function() {
                window.location = 'create.php';
            });
        </script>";
    }
?>

<?php include '../layout/footer.php'; ?>