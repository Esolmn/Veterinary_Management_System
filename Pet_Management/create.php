<?php 
    include '../layout/header.php';
    require_once  '../database/Database.php';
    require_once '../models/User.php'; 
    require_once '../models/Pet.php';

    $db = new Database();
    $conn = $db->getConnection();

    Pet::setConnection($conn);
    $pets = Pet::all();
    User::setConnection($conn);
    $users = User::all();
    //hi guys did you see this! aj toh

    $pet_owners = User::where('role', '=', 'pet_owner');

?>

<div class="container-xxl d-flex justify-content-center align-items-center mt-4">
    <div class="card shadow rounded-5 p-4" style="width: 700px;">
        <div class="card-title text-center">
            <h1>
                <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="orange" viewBox="0 0 512 512">
                    <path d="M226.5 92.9c14.3 42.9-.3 86.2-32.6 96.8s-70.1-15.6-84.4-58.5s.3-86.2 32.6-96.8s70.1 15.6 84.4 58.5zM100.4 198.6c18.9 32.4 14.3 70.1-10.2 84.1s-59.7-.9-78.5-33.3S-2.7 179.3 21.8 165.3s59.7 .9 78.5 33.3zM69.2 401.2C121.6 259.9 214.7 224 256 224s134.4 35.9 186.8 177.2c3.6 9.7 5.2 20.1 5.2 30.5l0 1.6c0 25.8-20.9 46.7-46.7 46.7c-11.5 0-22.9-1.4-34-4.2l-88-22c-15.3-3.8-31.3-3.8-46.6 0l-88 22c-11.1 2.8-22.5 4.2-34 4.2C84.9 480 64 459.1 64 433.3l0-1.6c0-10.4 1.6-20.8 5.2-30.5zM421.8 282.7c-24.5-14-29.1-51.7-10.2-84.1s54-47.3 78.5-33.3s29.1 51.7 10.2 84.1s-54 47.3-78.5 33.3zM310.1 189.7c-32.3-10.6-46.9-53.9-32.6-96.8s52.1-69.1 84.4-58.5s46.9 53.9 32.6 96.8s-52.1 69.1-84.4 58.5z"/>
                </svg>
            </h1>
            <h2 class="fw-bold mt-3" style="color: orange;">Create Pet</h2>
        </div>

        <form action="store.php" method="POST" class="mt-4">
            <div class="row mb-3">
                <div class="col-12 mb-6">
                <label for="user_id" class="form-label">Pet Owner</label>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'pet_owner'): ?>
                    <input type="hidden" name="user_id" id="user_id" value="<?= $_SESSION['id'] ?>">
                    <input type="text" class="form-control" value="<?= $_SESSION['name'] ?>" readonly>
                <?php else: ?>
                    <select class="form-select" id="user_id" name="user_id" required>
                        <option value="" disabled selected>Select a pet owner</option>
                        <?php foreach ($pet_owners as $pet_owner): 
                            if($pet_owner->status == 'inactive') continue;
                            ?>
                            <option value="<?= $pet_owner->id ?>"><?= $pet_owner->name ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <label for="name" class="form-label">Pet Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>

            <div class="row gx-3 mb-4">
                <div class="col-md-5">
                    <label for="birthdate" class="form-label">Birthdate</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                </div>
                <div class="col-7 mb-3">
                    <label for="breed" class="form-label">Breed</label>
                    <input type="text" class="form-control" id="breed" name="breed" required>
                </div>
            </div>
                        
            <div class="row gx-3 mb-4">
                <div class="col-6 mb-3">
                            <label for="specie" class="form-label">Specie</label>
                            <input type="text" class="form-control" id="specie" name="specie" required>
                </div>

                <div class="col-3 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>

                </div> 

                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="active">active</option>
                        <option value="inactive">inactive</option>
                    </select>
                </div>
            </div>  
            
            <div class="row">
                <div class="col-6">
                    <button type="submit" class="btn btn-warning w-100 rounded-3">Add Pet</button>
                </div>
                <div class="col-6">
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'pet_owner'): ?>
                                <a type="button" class="btn btn-danger w-100 rounded-3" name="sub" href="../Pet_Owner_Dashboard/index.php">Cancel</a>
                    <?php else : ?>
                                <a type="button" class="btn btn-danger w-100 rounded-3" name="sub" href="index.php">Cancel</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>


<?php 

    include  '../layout/footer.php'; 
?>