<?php 
    require_once '../database/Database.php';
    require_once '../models/Treatment.php';

    $database = new Database();
    $db = $database->getConnection();
  
    Treatment::setConnection($db);
    $treatments = Treatment::find($_GET['id']);

    Pet::setConnection($db);
    $pets = Pet::all();
  
    if (!$treatments) {
        header('Location: index.php');
        exit();
    }

    include '../layout/header.php';

    if (isset($_SESSION['role']) && ($_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin')) {
            //display website
?>

<div class="container-xxl p-4 mt-3 d-flex justify-content-center">
    <div class="row g-4" style="min-height: 75%;">
        <div class="col-md-4 d-flex h-75">
            <div class="card shadow rounded-4 p-4">
                <div class="card-title text-center">
                    <h1>
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="purple" class="bi bi-capsule" viewBox="0 0 16 16">
                            <path d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z"/>
                        </svg>
                    </h1>
                    <h2 class="fw-bold mt-3" style="color: orange;">Update Treatment</h2>
                </div>

                <form action="update.php?id=<?=$treatments->id?>" method="POST" class="mt-4">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="treatment_name" class="form-label">Treatment Name</label>
                            <input type="text" id="treatment_name" name="treatment_name" class="form-control" value="<?= $treatments->treatment_name ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="diagnosis" class="form-label">Diagnosis</label>
                            <input type="text" id="diagnosis" name="diagnosis" class="form-control" value="<?= $treatments->diagnosis ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" id="description" name="description" class="form-control" value="<?= $treatments->description ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" id="date" name="date" class="form-control" min="<?= date('Y-m-d') ?>" value="<?= $treatments->date ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="pet_id" class="form-label">Treatment Recipient</label>
                            <select id="pet_id" name="pet_id" class="form-select" required>
                                <option value="" disabled>Select a Pet</option>
                                <?php foreach ($pets as $pet): ?>
                                    <option value="<?=$pet->id?>" <?= $pet->id == $treatments->pet_id ? 'selected' : '' ?>>
                                        <?=$pet->id?> - <?=$pet->name?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="doctor_fee" class="form-label">Doctor Fee</label>
                            <input type="number" id="doctor_fee" name="doctor_fee" class="form-control" step="0.01" min="0" value="<?= $treatments->doctor_fee ?>" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between gap-2">
                        <button type="submit" class="btn custom-purple-btn w-100 rounded-3">Update</button>
                        <a href="index.php" class="btn btn-danger w-100 rounded-3">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-8 d-flex">
            <div class="card shadow rounded-4 p-4 h-75">
                <div class="card-title text-center">
                    <h1>
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="purple" class="bi bi-clipboard2-fill" viewBox="0 0 16 16">
                            <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z"/>
                            <path d="M3.5 1h.585A1.5 1.5 0 0 0 4 1.5V2a1.5 1.5 0 0 0 1.5 1.5h5A1.5 1.5 0 0 0 12 2v-.5q-.001-.264-.085-.5h.585A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1"/>
                        </svg>
                    </h1>
                    <h2 class="fw-bold mt-3" style="color: orange;">List of Pets for Reference</h2>
                </div>
                <div class="table-responsive">
                    <table id="vetTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Pet ID</th>
                                <th>Owner ID</th>
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
                                foreach ($pets as $pet):
                            ?>
                            <tr>
                                <td><?=$pet->id?></td>
                                <td><?=$pet->user_id?></td>
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


<?php
        } else {
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
        }


    include '../layout/footer.php';
?>