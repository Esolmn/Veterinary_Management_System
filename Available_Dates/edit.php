<?php
    include '../layout/header.php';
    require_once '../database/Database.php';
    require_once '../models/AvailableDate.php';
    require_once '../models/Appointment.php';

    $db = new Database();
    $conn = $db->getConnection();
    AvailableDate::setConnection($conn);

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
        //display website
        $id = $_GET['id'];
        $available_dates = AvailableDate::find($id);
?>

<div class="container-xxl d-flex justify-content-center align-items-center mt-5">
    <div class="card shadow rounded-4 p-4" style="width: 700px;">
        <div class="card-title text-center">
            <h1 class="mb-5 mt-3" style="color: orange;">Update Date</h1>
        </div>
        <form action="update.php?id=<?=$available_dates->id?>" method="POST">
            <div class="col-12 mb-3">
                <label for="available_date" class="form-label">Date</label>
                <input type="date" id="available_date" name="available_date"
                    class="form-control <?=(isset($_SESSION['date_error']) ? 'is-invalid' : '')?>" min="<?= date('Y-m-d') ?>" value="<?php echo $available_dates->date ?>" required>
                <?php if(isset($_SESSION['date_error'])) : ?>
                    <div class="invalid-feedback">
                        <?= $_SESSION['date_error'] ?>
                    </div>
                <?php 
                    endif;
                    unset($_SESSION['date_error']);
                ?>
            </div>
            <div class="row mt-5">
                <div class="col-6">
                    <a href="index.php" class="btn btn-danger w-100 rounded-3 mt-3">Cancel</a>
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 mt-3">Update</button>
                </div>
            </div>
        </form>
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