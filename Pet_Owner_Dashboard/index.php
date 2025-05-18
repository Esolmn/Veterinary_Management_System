<?php
    include '../layout/header.php';
    require_once '../database/Database.php';
    require_once '../models/User.php';
    require_once '../models/Pet.php';
    require_once '../models/Treatment.php';
    require_once '../models/Product.php';
    require_once '../models/Appointment.php';
    require_once '../models/AvailableDate.php';

    $db = new Database();
    $conn = $db->getConnection();
    User::setConnection($conn);
    Pet::setConnection($conn);
    Treatment::setConnection($conn);
    Product::setConnection($conn);
    Appointment::setConnection($conn);
    AvailableDate::setConnection($conn);

    $petrecords = Pet::where('user_id', '=', $_SESSION['id']); //kinukuha lng pets ng pet owner
    $petCount = 0;
    $activePets = 0;
    $inactivePets = 0;

    foreach ($petrecords as $pet) {
        $petCount++;
        if($pet->status == 'active') {
            $activePets++;
        } else
        {
            $inactivePets++;
        }
    }

?>

<div class="container d-flex justify-content-center align-items-center mt-5" style="min-height: 50vh;">
    <div class="row w-100 justify-content-center gy-4">
        <div class="col-lg-4 col-md-6 d-flex flex-column gap-4">
            <div class="card shadow rounded-4 p-4">
                <div class="card shadow-gradient rounded-4 p-4 mb-4">
                    <p class="text-center fw-bold mt-2" style="font-size: 1.5rem;">
                        <span style="color: orange;">Welcome,</span> <?= $_SESSION['name'] . '!'; ?>
                    </p>
                </div>
                <p class="text-center mt-3" style="font-size: 1.0rem;">Total Pets Registered</p>
                <div class="card shadow-gradient rounded-4 p-4">
                    <p class="text-center fw-bold mt-2" style="font-size: 1.5rem;">                
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="orange" class="bi bi-people-fill" viewBox="0 0 512 512">
                            <path d="M226.5 92.9c14.3 42.9-.3 86.2-32.6 96.8s-70.1-15.6-84.4-58.5s.3-86.2 32.6-96.8s70.1 15.6 84.4 58.5zM100.4 198.6c18.9 32.4 14.3 70.1-10.2 84.1s-59.7-.9-78.5-33.3S-2.7 179.3 21.8 165.3s59.7 .9 78.5 33.3zM69.2 401.2C121.6 259.9 214.7 224 256 224s134.4 35.9 186.8 177.2c3.6 9.7 5.2 20.1 5.2 30.5l0 1.6c0 25.8-20.9 46.7-46.7 46.7c-11.5 0-22.9-1.4-34-4.2l-88-22c-15.3-3.8-31.3-3.8-46.6 0l-88 22c-11.1 2.8-22.5 4.2-34 4.2C84.9 480 64 459.1 64 433.3l0-1.6c0-10.4 1.6-20.8 5.2-30.5zM421.8 282.7c-24.5-14-29.1-51.7-10.2-84.1s54-47.3 78.5-33.3s29.1 51.7 10.2 84.1s-54 47.3-78.5 33.3zM310.1 189.7c-32.3-10.6-46.9-53.9-32.6-96.8s52.1-69.1 84.4-58.5s46.9 53.9 32.6 96.8s-52.1 69.1-84.4 58.5z"/>
                        </svg>
                        <span>&nbsp;&nbsp;: &nbsp;</span>
                        <span style="color: orchid;" id="petCount" data-count="<?= $petCount; ?>">0</span>
                    </p>
                </div>
            </div>
            <div class="card shadow rounded-4 p-4 text-center">
                <p class="fw-bold" style="color: orange; font-size: 1.5rem;">Pets Status</p>
                <div class="card shadow-gradient rounded-4 p-4">
                    <div class="row">
                        <div class="col-6">
                            <p>Active</p>
                            <p class="fw-bold" style="color: orange;" id="activePets" data-count="<?= $activePets; ?>">0</p>
                        </div>
                        <div class="col-6">
                            <p>Inactive</p>
                            <p class="fw-bold" style="color: orchid;" id="inactivePets" data-count="<?= $inactivePets; ?>">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-12">
            <div class="card shadow rounded-4 p-4" style="height: 608px;">
                <p class="text-center fw-bold mt-2 fs-2" style="color: orange;">Pets Details</p>
                <a href="../Pet_Management/create.php" class="custom-purple-btn btn mb-4 w-25">Add Pet</a>
                <div class="card shadow-gradient rounded-4 p-2">
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="table">
                            <thead class="table-hover sticky-top bg-white">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Breed</th>
                                    <th scope="col">Specie</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Birthdate</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php foreach($petrecords as $pet): ?>
                                    <tr>
                                        <td><?= $pet->name ?></td>
                                        <td><?= $pet->breed ?></td>
                                        <td><?= $pet->specie ?></td>
                                        <td><?= $pet->gender ?></td>
                                        <td><?= $pet->birthdate ?></td>
                                        <td><?= $pet->status ?></td>
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


<?php include '../layout/footer.php'; ?>