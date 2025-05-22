<?php 
    require_once '../database/Database.php';
    require_once '../models/User.php';

    session_start(); //di gumagana, nadidirect cya sa blank page

    $database = new Database();
    $db = $database->getConnection();

    User::setConnection($db);

    if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'superadmin' && $_SESSION['role'] !== 'admin')) {
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
?>

<?php

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $id = $_GET['id'];
        $user = User::find($id);

        if(!$user) {
            header('Location: index.php');
            exit();
        }

        $existingUser = User::authByEmail($_POST['email']);

        if($existingUser && $existingUser->id != $user->id){ // check kung may kaumukhang email sa ibang user
            $_SESSION['email_error'] = 'Email already exists';
            header('Location: edit.php?id='.$id);
            exit();
        } else {
            
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'status' => $_POST['status'],
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        $updateUser = $user->update($data);

        include '../layout/header.php';

        if($updateUser){
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Pet Owner Account has been updated.',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'index.php';
                    });
                </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to update Pet Owner Account, try again.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                </script>";

        }
    }
    else {
        echo "<script>
            Swal.fire({
                title: 'Warning!',
                text: 'Request method not allowed.',
                icon: 'warning',
                showConfirmButton: true,
            }).then(function() {
                window.location.href = 'index.php';
            });
        </script>";
    }

    include '../layout/footer.php';

?>