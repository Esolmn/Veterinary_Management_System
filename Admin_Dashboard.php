<?php
    include 'layout/header.php';
    require_once 'database/Database.php';
    require_once 'models/User.php';
    require_once 'models/Pet.php';
    require_once 'models/Treatment.php';
    require_once 'models/Product.php';
    require_once 'models/Appointment.php';

    $db = new Database();
    $conn = $db->getConnection();
    User::setConnection($conn);
    Pet::setConnection($conn);
    Treatment::setConnection($conn);
    Product::setConnection($conn);
    Appointment::setConnection($conn);

    $countAdmins = User::countByRole('admin');
    $countPetOwners = User::countByRole('pet_owner');
    $countPets = Pet::countPet();
    $countPending = Appointment::countByStatus('pending');
    

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
        //display website

?>

<div class="container d-flex justify-content-center align-items-center" style="height: <?= ($_SESSION['role'] == 'admin') ? '60vh' : '75vh'; ?>;">
    <div class="row w-100 justify-content-center gy-4">
        <div class="col-lg-4 col-md-6">
            <div class="card shadow rounded-4 p-4">
                <div class="card shadow rounded-4 p-4 mb-4">
                    <p class="text-center fw-bold mt-2" style="font-size: 1.5rem;">
                        <span style="color: orange;">Welcome,</span> <?= $_SESSION['name'] . '!'; ?>
                    </p>
                </div>
                <p class="text-center mt-3" style="font-size: 1.0rem;">Role</p>
                <div class="card shadow rounded-4 p-4">
                    <p class="text-center fw-bold mt-2" style="font-size: 1.5rem;">                
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="orange" class="bi bi-people-fill" viewBox="0 0 512 512">
                            <path d="M226.5 92.9c14.3 42.9-.3 86.2-32.6 96.8s-70.1-15.6-84.4-58.5s.3-86.2 32.6-96.8s70.1 15.6 84.4 58.5zM100.4 198.6c18.9 32.4 14.3 70.1-10.2 84.1s-59.7-.9-78.5-33.3S-2.7 179.3 21.8 165.3s59.7 .9 78.5 33.3zM69.2 401.2C121.6 259.9 214.7 224 256 224s134.4 35.9 186.8 177.2c3.6 9.7 5.2 20.1 5.2 30.5l0 1.6c0 25.8-20.9 46.7-46.7 46.7c-11.5 0-22.9-1.4-34-4.2l-88-22c-15.3-3.8-31.3-3.8-46.6 0l-88 22c-11.1 2.8-22.5 4.2-34 4.2C84.9 480 64 459.1 64 433.3l0-1.6c0-10.4 1.6-20.8 5.2-30.5zM421.8 282.7c-24.5-14-29.1-51.7-10.2-84.1s54-47.3 78.5-33.3s29.1 51.7 10.2 84.1s-54 47.3-78.5 33.3zM310.1 189.7c-32.3-10.6-46.9-53.9-32.6-96.8s52.1-69.1 84.4-58.5s46.9 53.9 32.6 96.8s-52.1 69.1-84.4 58.5z"/>
                        </svg>
                        <?= '&nbsp :  &nbsp' . $_SESSION['role']; ?>
                    </p>
                </div>
            </div>
        </div>

    <div class="col-lg-8 col-md-12">
        <div class="card shadow rounded-4 p-4">
            <p class="text-center fw-bold" style="font-size: 1.5rem; color: orange;">Statistics</p>
            <div class="row justify-content-center">
                <div class="col-md-4 d-flex justify-content-center mb-3">
                    <div class="card shadow rounded-4 p-3" style="width: 250px; height: 180px;">
                        <div class="card-title text-center">
                            <svg class="mt-3" xmlns="http://www.w3.org/2000/svg" width="80" height="50" fill="blue" class="bi bi-people-fill" viewBox="0 0 16 16">
                                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                            </svg>
                            <p class="text-center mt-3">Total Pet Owners</p>
                            <p class="text-center fw-bold"><?php echo $countPetOwners; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 d-flex justify-content-center mb-3">
                    <div class="card shadow rounded-4 p-3" style="width: 250px; height: 180px;">
                        <div class="card-title text-center">
                            <svg class="mt-3" xmlns="http://www.w3.org/2000/svg" width="80" height="50" fill="orange" class="bi bi-people-fill" viewBox="0 0 512 512">
                                <path d="M226.5 92.9c14.3 42.9-.3 86.2-32.6 96.8s-70.1-15.6-84.4-58.5s.3-86.2 32.6-96.8s70.1 15.6 84.4 58.5zM100.4 198.6c18.9 32.4 14.3 70.1-10.2 84.1s-59.7-.9-78.5-33.3S-2.7 179.3 21.8 165.3s59.7 .9 78.5 33.3zM69.2 401.2C121.6 259.9 214.7 224 256 224s134.4 35.9 186.8 177.2c3.6 9.7 5.2 20.1 5.2 30.5l0 1.6c0 25.8-20.9 46.7-46.7 46.7c-11.5 0-22.9-1.4-34-4.2l-88-22c-15.3-3.8-31.3-3.8-46.6 0l-88 22c-11.1 2.8-22.5 4.2-34 4.2C84.9 480 64 459.1 64 433.3l0-1.6c0-10.4 1.6-20.8 5.2-30.5zM421.8 282.7c-24.5-14-29.1-51.7-10.2-84.1s54-47.3 78.5-33.3s29.1 51.7 10.2 84.1s-54 47.3-78.5 33.3zM310.1 189.7c-32.3-10.6-46.9-53.9-32.6-96.8s52.1-69.1 84.4-58.5s46.9 53.9 32.6 96.8s-52.1 69.1-84.4 58.5z"/>
                            </svg>
                            <p class="text-center mt-3">Total Pets</p>
                            <p class="text-center fw-bold"><?php echo $countPets; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 d-flex justify-content-center">
                    <div class="card shadow rounded-4 p-3" style="width: 250px; height: 180px;">
                        <div class="card-title text-center">
                            <svg class="mt-3" xmlns="http://www.w3.org/2000/svg" width="80" height="50" fill="red" class="bi bi-people-fill" viewBox="0 0 448 512">
                                <path d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48 64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16z"/>
                            </svg>
                            <p class="text-center mt-3">Pending Appointments</p>
                            <p class="text-center fw-bold"><?php echo $countPending; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        

            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin') : ?>
            <div class="row justify-content-center">
                <div class="col-md-4 d-flex justify-content-center">
                    <div class="card shadow rounded-4 p-3" style="width: 250px; height: 180px;">
                        <div class="card-title text-center">
                            <svg class="mt-4" xmlns="http://www.w3.org/2000/svg" width="80" height="40" fill="purple" class="bi bi-people-fill" viewBox="0 0 512 512">
                                <path d="M78.6 5C69.1-2.4 55.6-1.5 47 7L7 47c-8.5 8.5-9.4 22-2.1 31.6l80 104c4.5 5.9 11.6 9.4 19 9.4l54.1 0 109 109c-14.7 29-10 65.4 14.3 89.6l112 112c12.5 12.5 32.8 12.5 45.3 0l64-64c12.5-12.5 12.5-32.8 0-45.3l-112-112c-24.2-24.2-60.6-29-89.6-14.3l-109-109 0-54.1c0-7.5-3.5-14.5-9.4-19L78.6 5zM19.9 396.1C7.2 408.8 0 426.1 0 444.1C0 481.6 30.4 512 67.9 512c18 0 35.3-7.2 48-19.9L233.7 374.3c-7.8-20.9-9-43.6-3.6-65.1l-61.7-61.7L19.9 396.1zM512 144c0-10.5-1.1-20.7-3.2-30.5c-2.4-11.2-16.1-14.1-24.2-6l-63.9 63.9c-3 3-7.1 4.7-11.3 4.7L352 176c-8.8 0-16-7.2-16-16l0-57.4c0-4.2 1.7-8.3 4.7-11.3l63.9-63.9c8.1-8.1 5.2-21.8-6-24.2C388.7 1.1 378.5 0 368 0C288.5 0 224 64.5 224 144l0 .8 85.3 85.3c36-9.1 75.8 .5 104 28.7L429 274.5c49-23 83-72.8 83-130.5zM56 432a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z"/>
                            </svg>
                            <p class="text-center mt-3">Total Admins</p>
                            <p class="text-center fw-bold"><?php echo $countAdmins; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
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
            window.location.href = 'index.php';
        });
    </script>";
        exit();
    }

    include 'layout/footer.php'; 
?>