<?php 
    session_start();
    require_once  '../database/Database.php';
    require_once '../models/User.php'; 
    require_once '../models/Pet.php';

    $db = new Database();
    $conn = $db->getConnection();

    User::setConnection($conn);
    Pet::setConnection($conn);
    $user = User::find($_POST['user_id']);
?>

<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['name']) || empty($_POST['user_id'])) {
        if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            header('Location: index.php');
        } else {
            header('Location: ../Pet_Owner_Dashboard/index.php');
        }
        exit();
    }
        include '../layout/header.php';

        if($user->status != 'active') {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'User is inactive and cannot add pet',
                    icon: 'error',
                    showConfirmButton: 'Ok',
                }).then(function() {
                    window.location.href = 'index.php';
                });
            </script>";
            exit();
        }

        $pet_names = Pet::where('user_id', '=', $_POST['user_id']);
        
        foreach($pet_names as $pet_name) {
            if($pet_name->name == $_POST['name']) {
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Pet name already exists',
                        icon: 'error',
                        showConfirmButton: 'Ok',
                    }).then(function() {
                        window.location.href = 'create.php';
                    });
                </script>";
                exit();
            }
        }

        $data = [
            'user_id' => $_POST['user_id'],
            'name' => $_POST['name'],
            'breed' => $_POST['breed'],
            'specie' => $_POST['specie'],
            'gender' => $_POST['gender'],
            'birthdate' => $_POST['birthdate'],
            'status' => $_POST['status']
        ];

        $pet = Pet::create($data);

        if($pet) {
            if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
                echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Pet has been added',
                        icon: 'success',
                        showConfirmButton: 'Ok',
                    }).then(function() {
                        window.location.href = 'index.php';
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Pet has been added',
                        icon: 'success',
                        showConfirmButton: 'Ok',
                    }).then(function() {
                        window.location.href = '../Pet_Owner_Dashboard/index.php';
                    });
                </script>";
            }
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to add pet',
                    icon: 'error',
                    showConfirmButton: 'Ok',
                }).then(function() {
                    window.location.href = 'create.php';
                });
            </script>";
        }
?>

<?php include '../layout/footer.php'; ?>