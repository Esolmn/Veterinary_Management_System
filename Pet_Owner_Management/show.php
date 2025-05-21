<?php 
    session_start();
    require_once '../database/Database.php';
    require_once '../models/User.php';

    $database = new Database();
    $db = $database->getConnection();

    User::setConnection($db);
    $id = $_GET['id'];
    $user = User::find($id);

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            //display website
        if(!$user){
            header('Location: index.php');
            exit();
        }

        include '../layout/header.php';
?>

<div class="container-xxl p-4 mt-3">
    <div class="row gx-4 gy-4">
        <div class="col-md-4 d-flex justify-content-center">
            <div class="card w-100 shadow rounded-4 p-4 mb-4 mb-md-0">
                <div class="card-header bg-white border-0 pb-0">
                    <h2 class="text-center fw-bolder mb-3" style="color: orange;">Pet Owner Details</h2>
                </div>
                <div class="card-body pt-3">
                    <div class="row gx-3 mb-2">
                        <div class="col-md-5 fw-bolder">User ID:</div>
                        <div class="col-md-7"><?=$user->id?></div>
                    </div>
                    <div class="row gx-3 mb-2">
                        <div class="col-md-5 fw-bolder">Full Name:</div>
                        <div class="col-md-7"><?=$user->name?></div>
                    </div>
                    <div class="row gx-3 mb-2">
                        <div class="col-md-5 fw-bolder">Email:</div>
                        <div class="col-md-7"><?=$user->email?></div>
                    </div>
                    <div class="row gx-3 mb-2">
                        <div class="col-md-5 fw-bolder">Assigned Role:</div>
                        <div class="col-md-7"><?=$user->role?></div>
                    </div>
                    <div class="row gx-3 mb-2">
                        <div class="col-md-5 fw-bolder">Account Status:</div>
                        <div class="col-md-7"><?=$user->status?></div>
                    </div>
                </div>
              <div class="card-footer bg-white border-0 d-flex justify-content-center align-items-center gap-2 flex-wrap pt-3">
                    <div class="row align-items-stretch g-2">
                        <div class="col d-flex justify-content-center align-items-stretch">
                            <a href="../reports/petowners_indivreport.php?id=<?=$user->id?>" target="_blank" class="btn btn-success w-100 h-100 d-flex align-items-center justify-content-center">View</a>
                        </div>
                        <div class="col d-flex justify-content-center align-items-stretch">
                            <a href="../reports/petowners_indivreport.php?action=D&id=<?=$user->id?>" target="_blank" class="btn btn-warning w-100 h-100 d-flex align-items-center justify-content-center">Download</a>
                        </div>
                        <div class="col d-flex justify-content-end align-items-stretch">
                            <a class="btn btn-danger w-100 h-100 d-flex align-items-center justify-content-center" href="index.php">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow rounded-4 w-100 bg-white p-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h2 class="text-center fw-bolder mb-3" style="color: orange">List of Added Pets</h2>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table id="vetTable" class="table table-striped table-hover table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Pet Name</th>
                                    <th>Breed</th>
                                    <th>Species</th>
                                    <th>Gender</th>
                                    <th>Birthdate</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 1;
                                    $userPets = Pet::where('user_id', '=', $id);
                                    foreach($userPets as $pet) :
                                ?>
                                <tr>
                                    <td><?=$i++?></td>
                                    <td><?=$pet->name?></td>
                                    <td><?=$pet->breed?></td>
                                    <td><?=$pet->specie?></td>
                                    <td><?=$pet->gender?></td>
                                    <td><?=$pet->birthdate?></td>
                                    <td><?=$pet->status?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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