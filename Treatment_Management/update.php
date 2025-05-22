<?php 
    session_start();
    require_once '../database/Database.php';
    require_once '../models/Treatment.php';

    $database = new Database();
    $db = $database->getConnection();

    Treatment::setConnection($db); 

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            //display website

?>
<?php
    $id = $_GET['id'];
    $treatment = Treatment::find($id);

    if(!$treatment){
        header('Location: index.php');
        exit();
    }

    include  '../layout/header.php';
     
    if($treatment){

        $data = [
            'treatment_name' => $_POST['treatment_name'],
            'diagnosis' => $_POST['diagnosis'],
            'description' => $_POST['description'],
            'date' => $_POST['date'],
            'pet_id' => $_POST['pet_id'],
            'doctor_fee' => $_POST['doctor_fee'],
            'updated_at' => date('Y-m-d H:i:s')
        ];

         $updateTreatment = $treatment->update($data);

            if($updateTreatment){
                echo "<script>
                        Swal.fire({
                            title: 'Success!',
                            text: 'Treatment record has been updated.',
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
                            text: 'Failed to update treatment record, try again.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        }).then(function() {
                            window.location = 'edit.php?id=$id';
                        });
                    </script>";
            }
     } 
     else{
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Treatment not found.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = 'index.php';
                });
            </script>";
     }
    
?>

<?php
    } else{
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

    include '../layout/footer.php';
?>