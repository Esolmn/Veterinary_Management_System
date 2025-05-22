<?php
    session_start();
    require_once '../../database/Database.php';
    require_once '../../models/User.php';

    $db = new Database();
    $conn = $db->getConnection();
    User::setConnection($conn); 

    if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'superadmin' && $_SESSION['role'] !== 'admin')) {
        include '../../layout/header.php';
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
?>

<?php

    $user = User::find($_GET['id']);
    $newStatus = $_GET['status'];//kinukuha ung pinasang status sa URL

    if(!$user) {
        header('Location: index.php');
        exit();
    }

    include '../../layout/header.php';

    if($user->updateStatus($newStatus)) {
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Pet Owner Account has been " . $newStatus . "d',
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
                text: 'Failed to update Pet Owner account status.',
                icon: 'error',
                showConfirmButton: true,
            }).then(function() {
                window.location.href = 'index.php';
            });
        </script>";
    }

    include '../../layout/footer.php';
?>
