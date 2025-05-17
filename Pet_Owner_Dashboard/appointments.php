<?php
    include '../layout/header.php';
    require_once '../database/Database.php';
    require_once '../models/User.php';
    require_once '../models/Pet.php';
    require_once '../models/Appointment.php';
    require_once '../models/AvailableDate.php';

    $db = new Database();
    $conn = $db->getConnection();
    User::setConnection($conn);
    Pet::setConnection($conn);
    Appointment::setConnection($conn);
    AvailableDate::setConnection($conn);

    $appointments = Appointment::where('user_id', '=', $_SESSION['id']);

?>

<div class="container mt-4 d-flex justify-content-center align-items-center">
    <div class="card shadow rounded-4 p-4">
        <p class="text-center fw-bold mt-2 fs-2" style="color: orange;">Appointment Status</p>
        <div class="mb-3 d-flex justify-content-between">
            <a href="../Appointment_Management/create.php" class="btn custom-purple-btn rounded-3 mb-2 text-start">Schedule Appointment</a>
            <a href="index.php" class="btn btn-danger rounded-3 mb-2 text-end">Back</a>
        </div>
        <div class="card shadow rounded-4 p-4" style="min-width: 800px;">
            <table class="table table-hover align-middle fs-5">
            <thead>
                <tr>
                    <th class="text-center" scope="col">Pet Name</th>
                    <th class="text-center" scope="col">Appointment Date</th>
                    <th class="text-center" scope="col">Status</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php foreach ($appointments as $appt) : 
                    $petname = Pet::find($appt->pet_id);
                    $apptdate = AvailableDate::find($appt->aptdate_id);
                ?>
                <tr>
                    <td class="text-center"><?= $petname->name ?></td>
                    <td class="text-center"><?= $apptdate->date ?></td>
                    <td class="text-center"><?= $appt->status ?>
                        <?php if($appt->status == 'Declined' && $appt->declined_reason != null) : ?>
                            <br><small class="text-danger fst-italic"><?= 'Reason: '.$appt->declined_reason ?></small>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>