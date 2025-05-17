<?php
    session_start();
    require_once '../database/Database.php';
    require_once '../models/AvailableDate.php';
    require_once '../models/User.php';
    require_once '../models/Pet.php';
    require_once '../models/Appointment.php';

    $db = new Database();
    $conn = $db->getConnection();
    AvailableDate::setConnection($conn);
    User::setConnection($conn);
    Pet::setConnection($conn);
    Appointment::setConnection($conn);

    $availables = AvailableDate::all();    
    $users = User::all();    
    $pets = Pet::all();
    
    if (isset($_SESSION['role']) && ($_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin')) {

        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header('Location: index.php');
            exit();
        }

        $aptID = $_GET['id'];
        $apt = Appointment::find($aptID);

        if (!$apt) {
            header('Location: index.php');
            exit();
        }

        include '../layout/header.php';

        $this_aptdate = AvailableDate::find($apt->aptdate_id);
        $this_user = User::find($apt->user_id);
        $this_pet = Pet::find($apt->pet_id);
        $servicesArray = explode(', ', $apt->services_needed); // Convert the string to an array

?>

<div class="container-xxl d-flex flex-column justify-content-center align-items-center" style="min-height: 90vh;">
    <div class="row w-100 justify-content-center" style="max-width: 1100px;">
        <!-- Left Card -->
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card shadow rounded-4 p-4 h-100">
                <div class="card-title text-center">
                    <h2 class="fw-bold mb-3" style="color: orange;">PET DETAILS</h2>
                </div>
                <!-- ...pet/owner fields... -->
                <div class="mb-3">
                    <label for="ownerName" class="form-label fw-bolder">Owner's Name</label>
                    <input type="text" id="ownerName" class="form-control" value="<?= $this_user->name ?>" readonly required disabled>
                </div>
                <div class="mb-3">
                    <label for="petName" class="form-label fw-bolder">Pet Name:</label>
                    <input type="text" id="petName" class="form-control" value="<?= $this_pet->name ?>" readonly required disabled>
                </div>
                <div class="mb-3">
                    <label for="breed" class="form-label fw-bolder">Breed:</label>
                    <input type="text" id="breed" class="form-control" value="<?= $this_pet->breed ?>" readonly required disabled>
                </div>
                <div class="mb-3">
                    <label for="specie" class="form-label fw-bolder">Specie:</label>
                    <input type="text" id="specie" class="form-control" value="<?= $this_pet->specie ?>" readonly required disabled>
                </div>
            </div>
        </div>
        <!-- Right Card -->
        <div class="col-md-6">
            <div class="card shadow rounded-4 p-4 h-100">
                <div class="card-title text-center">
                    <h2 class="fw-bold mb-3" style="color: orange;">APPOINTMENT DETAILS</h2>
                </div>
                <form action="update.php?id=<?=$apt->id?>" method="POST" id="appointmentForm">
                    <div class="mb-3">
                        <label for="available_date" class="form-label fw-bolder">Available Date:</label>
                        <select name="aptdate_id" id="available_date" class="form-control" disabled>
                            <option selected="selected"><?= $this_aptdate->date ?></option>
                            <?php foreach($availables as $available) : ?>
                                <option value="<?= $available->id ?>"><?= $available->date ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bolder">Services Needed:</label>
                        <div class="ms-3">
                            <div>
                                <input type="checkbox" name="services_needed[]" id="grooming" value="Grooming" 
                                    <?= in_array('Grooming', $servicesArray) ? 'checked' : '' ?> disabled>
                                <label for="grooming">Grooming</label>
                            </div>
                            <div>
                                <input type="checkbox" name="services_needed[]" id="consultation" value="Consultation" 
                                    <?= in_array('Consultation', $servicesArray) ? 'checked' : '' ?> disabled>
                                <label for="consultation">Consultation</label>
                            </div>
                            <div>
                                <input type="checkbox" name="services_needed[]" id="surgery" value="Surgical Procedure" 
                                    <?= in_array('Surgical Procedure', $servicesArray) ? 'checked' : '' ?> disabled>
                                <label for="surgery">Surgical Procedure</label>
                            </div>
                            <div>
                                <input type="checkbox" name="services_needed[]" id="dental" value="Dental" 
                                    <?= in_array('Dental', $servicesArray) ? 'checked' : '' ?> disabled>
                                <label for="dental">Dental</label>
                            </div>
                            <div>
                                <input type="checkbox" name="services_needed[]" id="laboratory" value="Laboratory" 
                                    <?= in_array('Laboratory', $servicesArray) ? 'checked' : '' ?> disabled>
                                <label for="laboratory">Laboratory</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bolder">Status:</label>
                        <div class="ms-3">
                            <div>
                                <input type="radio" id="pending" name="status" value="Pending" 
                                    <?= $apt->status === 'Pending' ? 'checked' : '' ?>>
                                <label for="pending">Pending</label>
                            </div>
                            <div>
                                <input type="radio" id="accepted" name="status" value="Accepted" 
                                    <?= $apt->status === 'Accepted' ? 'checked' : '' ?>>
                                <label for="accepted">Accepted</label>
                            </div>
                            <div>
                                <input type="radio" id="declined" name="status" value="Declined" 
                                    <?= $apt->status === 'Declined' ? 'checked' : '' ?>>
                                <label for="declined">Declined</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center gap-4 mt-3">
                        <button class="btn custom-purple-btn rounded-3">Update</button>
                        <a class="btn btn-danger rounded-3" href="index.php">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Declined Reason Card (separate, below the two cards) -->
    <div class="row justify-content-center mt-4 w-100" id="declinedReasonCard" style="<?= $apt->status === 'Declined' ? '' : 'display:none;' ?>">
        <div class="col-12 d-flex justify-content-center">
            <div class="card shadow rounded-4 p-4 w-100 border border-5 border-white" style="max-width:1075px; margin:0 auto;">
                <div class="card-title text-center">
                    <h4 class="fw-bold text-danger mb-3">DECLINED REASON</h4>
                </div>
                <div class="mb-3">
                    <label for="declined_reason" class="form-label fw-bolder">Specify Reason:</label>
                    <input type="text" id="declined_reason" name="declined_reason" placeholder="Enter reason..." class="form-control"
                        value="<?= $apt->declined_reason ?>"
                        form="appointmentForm"
                        <?= $apt->status === 'Declined' ? '' : 'disabled' ?>>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    } else{
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

    include  '../layout/footer.php'; 
?>