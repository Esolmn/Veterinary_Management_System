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

    if(!$user) {
        header('Location: ../../index.php');
        exit();
    }

    $new_password = User::resetPassword($user->id);

    include '../../layout/header.php';

    if($new_password) {
        echo "<script>
            Swal.fire({
                title: 'Password has been reset successfully.',
                text: 'New password: $new_password',
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
                text: 'Failed to reset password.',
                icon: 'error',
                showConfirmButton: true,
            }).then(function() {
                window.location.href = 'index.php';
            });
        </script>";
    }

    include '../../layout/footer.php';
?>