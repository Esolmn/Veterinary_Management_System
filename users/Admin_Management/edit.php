<?php 
    include '../../layout/header.php';
    require_once '../../database/Database.php';
    require_once '../../models/User.php';

    $db = new Database();
    $conn = $db->getConnection();
    User::setConnection($conn); 

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin') {
            //display website
?>

<?php 
    $user = User::find($_GET['id']);
    if(!$user) {
        header('Location: index.php');
        exit();
    }
?>

<div class="container-xxl d-flex justify-content-center align-items-center mt-4">
    <div class="card shadow rounded-4 p-4 mt-5" style="width: 700px;">
        <div class="card-title text-center">
            <h1><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="orange" class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>
            </h1>
            <h2 class="text-center fw-bold mt-3" style="color: orange;">Update Admin Account</h2>
        </div>
        <form action="update.php?id=<?=$user->id?>" method="POST">
            <div class="row gx-3 mb-4">
                <div class="col-12 mb-6">
                    <label for="name" class="mb-2">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo $user->name ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email"
                        class="form-control <?=(isset($_SESSION['email_error']) ? 'is-invalid' : '')?>" value="<?php echo $user->email ?>" required>
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
            <div class="row gx-3 mb-4">
                <div class="col-md-5">
                    <label for="role" class="mb-2">Role</label>
                    <select name="role" id="role" class="form-select" value="<?php echo $user->role ?>">
                        <option value="<?=$user->role?>"><?=$user->role ?></option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <label for="status" class="mb-2">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="active" <?= $user->status === 'active' ? 'selected' : '' ?>>active</option>
                        <option value="inactive" <?= $user->status === 'inactive' ? 'selected' : '' ?>>inactive</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-warning w-100 rounded-3 mt-3">Update Account</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <a type="button" class="btn btn-danger mt-3 w-100 rounded-3" name="sub" href="index.php">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>?>

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