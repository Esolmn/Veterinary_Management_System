<?php 
    session_start();
    require_once  '..\database/Database.php'; 
    require_once '..\models\Pet.php';
    require_once '..\models\User.php';

    $db = new Database();
    $conn = $db->getConnection();

    Pet::setConnection($conn);
    $pet = Pet::find($_GET['id']);

    if(!$pet) {
        header('Location: index.php');
        exit();
    }

    include '../layout/header.php';

    User::setConnection($conn);
    $pet_owners = User::where('role', '=', 'pet_owner');
    $current_owner = User::find($pet->user_id);

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
    //display website
?>

<div class="container-xxl d-flex justify-content-center align-items-center">
    <div class="card shadow-gradient rounded-4 p-4 mt-5" style="width: 700px;">
        <div class="card-title text-center">
            <h1>
                <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="orange" viewBox="0 0 512 512">
                    <path d="M226.5 92.9c14.3 42.9-.3 86.2-32.6 96.8s-70.1-15.6-84.4-58.5s.3-86.2 32.6-96.8s70.1 15.6 84.4 58.5zM100.4 198.6c18.9 32.4 14.3 70.1-10.2 84.1s-59.7-.9-78.5-33.3S-2.7 179.3 21.8 165.3s59.7 .9 78.5 33.3zM69.2 401.2C121.6 259.9 214.7 224 256 224s134.4 35.9 186.8 177.2c3.6 9.7 5.2 20.1 5.2 30.5l0 1.6c0 25.8-20.9 46.7-46.7 46.7c-11.5 0-22.9-1.4-34-4.2l-88-22c-15.3-3.8-31.3-3.8-46.6 0l-88 22c-11.1 2.8-22.5 4.2-34 4.2C84.9 480 64 459.1 64 433.3l0-1.6c0-10.4 1.6-20.8 5.2-30.5zM421.8 282.7c-24.5-14-29.1-51.7-10.2-84.1s54-47.3 78.5-33.3s29.1 51.7 10.2 84.1s-54 47.3-78.5 33.3zM310.1 189.7c-32.3-10.6-46.9-53.9-32.6-96.8s52.1-69.1 84.4-58.5s46.9 53.9 32.6 96.8s-52.1 69.1-84.4 58.5z"/>
                </svg>
            </h1>
            <h2 class="text-center fw-bold mt-3" style="color: orange;">Edit Pet</h2>
        </div>
        <form action="update.php?id=<?=$pet->id?>" method="POST">

            <div class="row gx-3 mb-4">
                <input type="hidden" name="id" value="<?=$pet->id ?>">

                <div class="col-12 mb-6">
                    <label for="user_id" class="mb-2">Pet Owner</label>
                    <select class="form-select" id="user_id" name="user_id" required>
                        <option value="<?=$current_owner->id?>"><?=$current_owner->name ?></option>
                        <?php
                        foreach ($pet_owners as $pet_owner) {
                            if ($pet_owner->id == $current_owner->id) {
                                continue; // Skip the current owner
                            }
                            echo '<option value="' . $pet_owner->id . '">' . $pet_owner->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <label for="name" class="mb-2">Pet Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?=$pet->name ?>" required>
                </div>
            </div>

            <div class="row gx-3 mb-4">
                <div class="col-md-5">
                    <label for="birthdate" class="mb-2">Birthdate</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?=$pet->birthdate ?>" required>
                </div>
                <div class="col-7 mb-3">
                    <label for="breed" class="mb-2">Breed</label>
                    <input type="text" class="form-control" id="breed" name="breed" value="<?=$pet->breed ?>" required>
                </div>
            </div>
                        
            <div class="row gx-3 mb-4">
                <div class="col-6 mb-3">
                            <label for="specie" class="mb-2">Specie</label>
                            <input type="text" class="form-control" id="specie" name="specie" value="<?=$pet->specie?>"   required>
                </div>

                <div class="col-3 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="male"<?= $pet->gender === 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= $pet->gender === 'female' ? 'selected' : '' ?>>Female</option>
                        </select>

                </div> 

                <div class="col-md-3">
                    <label for="status" class="mb-2">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="active" <?= $pet->status === 'active' ? 'selected' : '' ?>>active</option>
                        <option value="inactive" <?= $pet->status === 'inactive' ? 'selected' : '' ?>>inactive</option>
                    </select>
                </div>
            </div>  
            
            <div class="row">
                <div class="col-6">
                    <button type="submit" class="btn custom-purple-btn w-100 rounded-3">Update Pet</button>
                </div>
                <div class="col-6">
                    <a type="button" class="btn btn-danger w-100 rounded-3" name="sub" href="index.php">Cancel</a>
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
            window.location.href = '../index.php';
        });
    </script>";
        exit();
    }

    include  '../layout/footer.php'; 
?>