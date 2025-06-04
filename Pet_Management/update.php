<?php 
    session_start();
    require_once '../database/Database.php'; 
    require_once '../models/User.php';

    $database = new Database();
    $db = $database->getConnection();
    User::setConnection($db);

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin' && $_SESSION['role'] !== 'admin') {

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
    $pet = Pet::find($_GET['id']);

    if (!$pet) {
        header('Location: index.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['name']) || empty($_POST['user_id'])) {
        if(isset($_SESSION['role']) && ($_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin')) {
            header('Location: index.php');
        } else {
            header('Location: ../Pet_Owner_Dashboard/index.php');
        }
        exit();
    }

    $data = [
        'user_id' => $_POST['user_id'],
        'name' => $_POST['name'],
        'breed' => $_POST['breed'],
        'specie' => $_POST['specie'],
        'gender' => $_POST['gender'],
        'birthdate' => $_POST['birthdate'],
        'status' => $_POST['status'],
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    $updatePet = $pet->update($data);

    include '../layout/header.php';

    if($updatePet) {
        echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Pet has been updated',
                    icon: 'success',
                    showConfirmButton: 'Ok',
                }).then(function() {
                    window.location.href = 'index.php';
                });
            </script>";
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to update pet',
                    icon: 'error',
                    showConfirmButton: 'Ok',
                }).then(function() {
                    window.location.href = 'edit.php?id=".$pet->id."';
                });
            </script>";
    }

    include '../layout/footer.php';

?>
