<?php 
    require_once '../../database/Database.php'; 
    require_once '../../models/User.php';

    session_start(); //same as sa update nung pet owners management

    $db = new Database();
    $conn = $db->getConnection();
    User::setConnection($conn);
    $id = $_GET['id'];
    $user = User::find($id);

    if(!$user) {
        header('Location: index.php');
        exit();
    }

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
?>

<?php

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $existingUser = User::authByEmail($_POST['email']);

        if($existingUser && $existingUser->id != $user->id){ // check kung may kaumukhang email sa ibang user
            $_SESSION['email_error'] = 'Email already exists';
            header('Location: edit.php?id='.$id);
            exit();
        }
        else {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'role' => $_POST['role'],
                'status' => $_POST['status'],
                'updated_at' => date('Y-m-d H:i:s')
            ]; 

            $updateUser = $user->update($data);

            include '../../layout/header.php';

            if($updateUser) {
                echo "<script>
                        Swal.fire({
                            title: 'Success!',
                            text: 'User record has been updated.',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then(function() {
                            window.location = 'index.php';
                        });
                    </script>";
            }
            else{
                echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to update user record, try again.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        }).then(function() {
                            window.location = 'update.php';
                        });
                    </script>";
            }
        }
    }
?>

<?php include '../../layout/footer.php'; ?>