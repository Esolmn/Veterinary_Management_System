<?php
    require_once '../../database/Database.php';
    require_once '../../models/User.php';

    session_start();  

    $db = new Database();
    $conn = $db->getConnection();
    User::setConnection($conn);

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $existingUser = User::authByEmail($_POST['email']);

    if ($existingUser) {
        $_SESSION['email_error'] = 'Email already exists';
        header('Location: create.php');
        exit();
    }

    if (!User::confirmPassword($_POST['password'], $_POST['confirm_password'])) {
        $_SESSION['password_error'] = 'Password does not match';
        header('Location: create.php');
        exit();
    }

    $data = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'role' => $_POST['role'],
        'status' => $_POST['status']
    ];

    $user = User::create($data);

    include '../../layout/header.php';

    if ($user) {
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Account has been created',
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
                text: 'Account was not created',
                icon: 'error',
                showConfirmButton: true,
            }).then(function() {
                window.location.href = 'create.php';
            });
        </script>";
    }

    include '../../layout/footer.php';
}
?>