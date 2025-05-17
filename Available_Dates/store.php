<?php
    session_start();
    require_once '../database/Database.php';
    require_once '../models/AvailableDate.php';

    $db = new Database();
    $conn = $db->getConnection();
    AvailableDate::setConnection($conn);

    if (isset($_SESSION['role']) && ($_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin')) {
?>

<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['available_date'])) {
        header('Location: index.php');
        exit();
    }

        $check_date = AvailableDate::where('date', '=', $_POST['available_date']);

        if(count($check_date) > 0) {
            $_SESSION['date_error'] = 'Date already exists';
            header('Location: create.php');
            exit();
        }

        $data = [
            'date' => $_POST['available_date'],
        ];

        $availableDate = AvailableDate::create($data);
        
        include '../layout/header.php';

        if($availableDate) {
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Available date has been added',
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
                        text: 'Failed to add available date',
                        icon: 'error',
                        showConfirmButton: true,
                    }).then(function() {
                        window.location.href = 'create.php';
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