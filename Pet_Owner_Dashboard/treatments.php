<?php
    include '../layout/header.php';
    require_once '../database/Database.php';
    require_once '../models/User.php';
    require_once '../models/Pet.php';
    require_once '../models/Treatment.php';
    require_once '../models/Appointment.php';
    require_once '../models/AvailableDate.php';

    $db = new Database();
    $conn = $db->getConnection();
    User::setConnection($conn);
    Pet::setConnection($conn);
    Treatment::setConnection($conn);

    $petrecords = Pet::where('user_id', '=', $_SESSION['id']); //kinukuha lng pets ng pet owner
?>  
    
<div class="container mt-5 d-flex justify-content-center align-items-center">    
    <div class="card shadow rounded-4 p-4">
        <p class="text-center fw-bold mt-2 fs-2 mb-4" style="color: orange;">Treatment Records</p>
        <div class="d-flex justify-content-between">
            <input type="text" id="customSearch" placeholder="Search Records..." class="form-control w-25 mb-3 ms-2 mb-4">
            <a href="index.php" class="btn btn-danger rounded-3 mb-3 text-end">Back</a>
        </div>
        <div class="card shadow-gradient rounded-4 p-2">
            <div style="max-height: 400px; overflow-y: auto;">
                <table id="treatmentTable" class="table">
                    <thead class="table table-hover" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th class="px-4">Pet</th>
                            <th class="px-4">Treatment</th>
                            <th class="px-4">Diagnosis</th>
                            <th class="px-4" style="width: 250px;">Description</th>
                            <th class="px-4">Date</th>
                            <th class="px-4" style="white-space: nowrap;">Doctor Fee</th>
                            <th class="px-4" style="white-space: nowrap;">Product Used</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($petrecords as $petrecord): 
                        $treatments = Treatment::where('pet_id', '=', $petrecord->id);    
                        foreach($treatments as $treatments):
                        ?>
                        <tr>
                            <td class="px-4"><?= $petrecord->name ?></td>
                            <td class="px-4"><?= $treatments->treatment_name ?></td>
                            <td class="px-4"><?= $treatments->diagnosis ?></td>
                            <td class="px-4"><?= $treatments->description ?></td>
                            <td class="px-4" style="white-space: nowrap;"><?= $treatments->date ?></td>
                            <td class="text-center"><?= $treatments->doctor_fee ?></td>
                            <td class="text-center">
                                <a href="treatment_product.php?id=<?= $treatments->id ?>" class="btn btn-success me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                </svg>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
    include '../layout/footer.php';
?>