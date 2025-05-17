<?php
    include '../../layout/header.php';

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin') {
            //display website
?>

<div class="container-xxl d-flex justify-content-center align-items-center mt-4">
    <div class="card shadow rounded-5 p-4" style="width: 700px;">
        <div class="card-title text-center">
            <h1>
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="orange" class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>
            </h1>
            <h2 class="fw-bold mt-3" style="color: orange;">Create Account</h2>
        </div>

        <form action="store.php" method="POST" class="mt-4">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email"
                        class="form-control <?=(isset($_SESSION['email_error']) ? 'is-invalid' : '')?>" required>
                    <?php if(isset($_SESSION['email_error'])) : ?>
                        <div class="invalid-feedback">
                            <?= $_SESSION['email_error'] ?>
                        </div>
                    <?php 
                        endif;
                        unset($_SESSION['email_error']);
                    ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password"
                        class="form-control <?=(isset($_SESSION['password_error']) ? 'is-invalid' : '')?>" required>
                    <?php if(isset($_SESSION['password_error'])) :  ?>
                        <div class="invalid-feedback">
                            <?= $_SESSION['password_error'] ?>
                        </div>
                    <?php endif; ?>
                    <?php 
                        unset($_SESSION['password_error']);
                    ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-select">
                        <option value="admin">Admin</option>    
                        <option value="pet_owner">Pet Owner</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 mt-3">Create Account</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <a type="button" class="btn btn-danger mt-3 w-100 rounded-3 " name="sub" href="index.php">Cancel</a>
                </div>
            </div>
        </form>
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
            window.location.href = '../../index.php';
        });
    </script>";
        exit();
    }


    include '../../layout/footer.php';
?>
