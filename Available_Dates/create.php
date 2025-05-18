<?php 
    include '../layout/header.php';

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
        //display website
?>

<div class="container-xxl d-flex justify-content-center align-items-center mt-5">
    <div class="card shadow-gradient rounded-4 p-4" style="width: 700px;">
        <div class="card-title text-center">
            <h1 class="mb-5 mt-3" style="color: orange;">Add Available Dates</h1>
        </div>
        <form action="store.php" method="POST">
            <div>
                <label for="available_date" class="form-label">Date</label>
                <input type="date" id="available_date" name="available_date"
                    class="form-control <?=(isset($_SESSION['date_error']) ? 'is-invalid' : '')?>" min="<?= date('Y-m-d') ?>" required>
                <?php if(isset($_SESSION['date_error'])) :  ?>
                    <div class="invalid-feedback">
                        <?= $_SESSION['date_error'] ?>
                    </div>
                <?php endif; ?>
                <?php 
                    unset($_SESSION['date_error']);
                ?>
            </div>
            <div class="row">   
                <div class="col-6">
                    <button type="submit" class="btn custom-purple-btn w-100 rounded-3 mt-4">Create</button>
                </div>
                <div class="col-6">
                    <a href="index.php" class="btn btn-danger w-100 rounded-3 mt-4">Cancel</a>
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