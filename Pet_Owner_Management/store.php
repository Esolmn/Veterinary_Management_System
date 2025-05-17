<?php 
    require_once '../database/Database.php';
    require_once '../models/User.php';

    session_start();

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
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
        $user = User::authByEmail($_POST['email']);

        if($user){
            $_SESSION['email_error'] = 'Email already exists';
            header('Location: create.php');
            exit();
        }

        else{
            if(!User::confirmPassword($_POST['password'], $_POST['confirm_password'])){
                $_SESSION['password_error'] = 'Password does not match';
                header('Location: create.php');
                exit();
            }

            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => $_POST['role'],
                'status' => $_POST['status'],
            ];

            $user = User::create($data);

            include '../layout/header.php';

            if($user){
                echo "<script>
                        Swal.fire({
                            title: 'Success!',
                            text: 'User record has been created.',
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
                            text: 'Failed to create user record, try again.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        }).then(function() {
                            window.location = 'create.php';
                        });
                    </script>";
            }
        }
    }

    include '../layout/footer.php';
?>
