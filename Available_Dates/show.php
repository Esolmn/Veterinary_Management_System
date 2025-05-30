<?php 
    session_start();
    require_once '../database/Database.php';
    require_once '../models/Model.php';
    require_once '../models/AvailableDate.php';
    require_once '../models/Appointment.php';
    require_once '../models/User.php';
    require_once '../models/Pet.php';

    $db = new Database();
    $conn = $db->getConnection();
    AvailableDate::setConnection($conn);
    User::setConnection($conn);
    Pet::setConnection($conn);

    $id = $_GET['id'];
    $availableDate = AvailableDate::find($id);

    if (!$availableDate) {
        header('Location: index.php');
        exit();
    }

    include '../layout/header.php';

    $appointments = $availableDate->approved_appointments();

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
        //display website

?>

<div class="container mt-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="card w-100 shadow rounded-4 p-4 mb-4 mb-md-0">
                <div class="card-header bg-white border-0 pb-0">
                    <h2 class="fw-bolder text-center mt-3 mb-4" style="color: orange">
                        Approved Appointments for <strong style="color: black"><?= date('F j, Y', strtotime($availableDate->date)) ?></strong>
                    </h2>
                </div>
                <div class="card shadow-gradient rounded-4 p-4 mt-3 mb-3">
                    <?php if (empty($appointments)): ?>
                        <p class="text-center mb-0">No approved appointments for this date.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <div style="max-height: 400px; overflow-y: auto;">
                                <table class="table rounded-4 text-center overflow-hidden">
                                    <thead class="table-hover sticky-top bg-white">
                                        <tr>
                                            <th>Pet Owner</th>
                                            <th>Pet Name</th>
                                            <th>Services Needed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($appointments as $appt): ?>
                                            <?php 
                                                $user = User::find($appt->user_id);
                                                $pet = Pet::find($appt->pet_id);
                                            ?>
                                            <tr>
                                                <td><?= $user?->name ?? 'Unknown' ?></td>
                                                <td><?= $pet?->name ?? 'Unknown' ?></td>
                                                <td><?= $appt->services_needed ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="d-flex justify-content-center mt-2">
                    <a class="btn btn-danger rounded-3 w-25" href="index.php">Back</a>
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

    include  '../layout/footer.php'; 
?>