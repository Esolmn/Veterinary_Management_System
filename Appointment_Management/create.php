<?php
    include '../layout/header.php';
    require_once '../database/Database.php';
    require_once '../models/AvailableDate.php';
    require_once '../models/User.php';
    require_once '../models/Pet.php';

    $db = new Database();
    $conn = $db->getConnection();

    AvailableDate::setConnection($conn);
    $availables = AvailableDate::all();

    User::setConnection($conn);
    $users = User::all();

    Pet::setConnection($conn);

    $pet_owners = User::where('role', '=', 'pet_owner');

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'pet_owner') {
        $pets = Pet::where('user_id', '=', $_SESSION['id']); //kinukuha lng pets ng pet owner
    } else {
        $pets = Pet::all(); //kinukuha lahat ng pets pag admin or superadmin
    }
?>

<div class="container-xxl d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="row w-100 justify-content-center" style="max-width: 1100px;">
        <!-- Left Card -->
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card shadow rounded-4 p-4 h-100">
                <div class="card-title text-center">
                    <h2 class="fw-bold mb-3" style="color: orange;">PET DETAILS</h2>
                </div>
                <div class="mb-3">
                    <label for="petOwner" class="form-label fw-bolder">Owner's Name</label>
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'pet_owner') : ?>
                        <input type="text" id="petOwner" class="form-control" value="<?= $_SESSION['name'] ?>" form="appointmentForm" readonly required>
                        <input type="hidden" name="user_id" id="petOwner" value="<?= $_SESSION['id']?>" form="appointmentForm">
                    <?php else : ?>
                        <select name="user_id" id="petOwner" class="form-control" form="appointmentForm" required>
                            <option selected="selected"></option>
                            <?php foreach($pet_owners as $pet_owner) : ?>
                                <option value="<?= $pet_owner->id ?>"><?= $pet_owner->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
               <div class="mb-3">
                    <label for="petName" class="form-label fw-bolder">Pet Name:</label>
                    <select name="pet_id" id="petName" class="form-control" form="appointmentForm" required>
                        <option selected="selected"></option>
                        <?php foreach($pets as $pet) : ?>
                            <option 
                                value="<?= $pet->id ?>" 
                                data-owner="<?= $pet->user_id ?>" 
                                data-breed="<?= $pet->breed ?>" 
                                data-specie="<?=$pet->specie ?>"
                                style="display:none;"
                            ><?= $pet->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="breed" class="form-label fw-bolder">Breed:</label>
                    <select name="breed" id="breed" class="form-control" form="appointmentForm" required disabled>
                        <option selected="selected"></option>
                        <?php foreach($pets as $pet) : ?>
                            <option value="<?= $pet->breed ?>" data-owner="<?= $pet->user_id ?>" style="display:none;"><?= $pet->breed ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="specie" class="form-label fw-bolder">Species:</label>
                    <select name="specie" id="specie" class="form-control" form="appointmentForm" required disabled>
                        <option selected="selected"></option>
                        <?php foreach($pets as $pet) : ?>
                            <option value="<?= $pet->specie ?>" data-owner="<?= $pet->user_id ?>" style="display:none;"><?= $pet->specie ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <!-- Right Card -->
        <div class="col-md-6">
            <div class="card shadow rounded-4 p-4 h-100">
                <div class="card-title text-center">
                    <h2 class="fw-bold mb-3" style="color: orange">APPOINTMENT DETAILS</h2>
                </div>
                <form action="store.php" method="POST" id="appointmentForm">
                    <div class="mb-3">
                        <label for="available_date" class="form-label fw-bolder">Available Date:</label>
                        <select name="aptdate_id" id="available_date" class="form-control" required>
                            <option selected="selected"></option>
                            <?php foreach($availables as $available) : ?>
                                <option value="<?= $available->id ?>"><?= $available->date ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bolder">Services Needed:</label>
                        <div class="ms-3">
                            <div>
                                <input type="checkbox" name="services_needed[]" id="grooming" value="Grooming">
                                <label for="grooming">Grooming</label>
                            </div>
                            <div>
                                <input type="checkbox" name="services_needed[]" id="consultation" value="Consultation">
                                <label for="consultation">Consultation</label>
                            </div>
                            <div>
                                <input type="checkbox" name="services_needed[]" id="surgery" value="Surgical Procedure">
                                <label for="surgery">Surgical Procedure</label>
                            </div>
                            <div>
                                <input type="checkbox" name="services_needed[]" id="dental" value="Dental">
                                <label for="dental">Dental</label>
                            </div>
                            <div>
                                <input type="checkbox" name="services_needed[]" id="laboratory" value="Laboratory">
                                <label for="laboratory">Laboratory</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bolder">Status:</label>
                        <div class="ms-3">
                            <div>
                                <input
                                    type="radio"
                                    id="pending"
                                    name="status"
                                    value="Pending"
                                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'pet_owner') echo 'checked'; ?>                                >
                                <label for="pending">Pending</label>
                            </div>
                            <div>
                                <input
                                    type="radio"
                                    id="accepted"
                                    name="status"
                                    value="Accepted"
                                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'pet_owner') echo 'disabled'; ?>
                                >
                                <label for="accepted">Accepted</label>
                            </div>
                            <div>
                                <input
                                    type="radio"
                                    id="declined"
                                    name="status"
                                    value="Declined"
                                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'pet_owner') echo 'disabled'; ?>
                                >
                                <label for="declined">Declined</label>
                            </div>
                        </div>
                    </div>

                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'pet_owner') : ?>
                    <div class="row">
                        <div class="col-12 text-center">
                            <a type="button" class="btn btn-danger" name="sub" href="../Pet_Owner_Dashboard/appointments.php">Cancel</a>
                            <button type="submit" class="btn btn-primary">Store</button>
                        </div>
                    </div>
                    <?php else : ?>
                    <div class="row">
                        <div class="col-12 text-center">
                            <a type="button" class="btn btn-danger" name="sub" href="index.php">Cancel</a>
                            <button type="submit" class="btn btn-warning">Book Appointment</button>
                        </div>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

     <div class="row justify-content-center mt-4 w-100" id="declinedReasonCard" style="display:none;">
        <div class="col-12 d-flex justify-content-center">
            <div class="card shadow rounded-4 p-4 w-100 border border-5 border-white" style="max-width:1075px; margin:0 auto;">
                <div class="card-title text-center">
                    <h4 class="fw-bold text-danger mb-3">DECLINED REASON</h4>
                </div>
                <div class="mb-3">
                    <label for="declined_reason_input" class="form-label fw-bolder">Specify Reason:</label>
                <input type="text" id="declined_reason_input" name="declined_reason" class="form-control" placeholder="Enter reason..." disabled form="appointmentForm">                </div>
            </div>
        </div>
    </div>
    </form>
</div>

<?php include '../layout/footer.php'; ?>