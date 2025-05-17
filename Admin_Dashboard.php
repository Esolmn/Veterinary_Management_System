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

<div class="container d-flex justify-content-center align-items-center" style="height: 80vh;">
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
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin') : ?>
                <div class="col-md-4 d-flex justify-content-center mb-3">
                    <div class="card shadow rounded-4 p-3" style="width: 250px; height: 180px;">
                        <div class="card-title text-center">
                            <svg class="mt-4" xmlns="http://www.w3.org/2000/svg" width="80" height="50" fill="purple" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                            </svg>
                            <p class="text-center mt-3">Total Admins</p>
                            <p class="text-center"><?php echo $countAdmins; ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="col-md-4 d-flex justify-content-center mb-3">
                    <div class="card shadow rounded-4 p-3" style="width: 250px; height: 180px;">
                        <div class="card-title text-center">
                            <svg class="mt-4" xmlns="http://www.w3.org/2000/svg" width="80" height="50" fill="red" class="bi bi-people-fill" viewBox="0 0 16 16">
                                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                            </svg>
                            <p class="text-center mt-3">Total Pet Owners</p>
                            <p class="text-center"><?php echo $countPetOwners; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 d-flex justify-content-center mb-3">
                    <div class="card shadow rounded-4 p-3" style="width: 250px; height: 180px;">
                        <div class="card-title text-center">
                            <svg class="mt-4" xmlns="http://www.w3.org/2000/svg" width="80" height="50" fill="orange" class="bi bi-people-fill" viewBox="0 0 512 512">
                                <path d="M226.5 92.9c14.3 42.9-.3 86.2-32.6 96.8s-70.1-15.6-84.4-58.5s.3-86.2 32.6-96.8s70.1 15.6 84.4 58.5zM100.4 198.6c18.9 32.4 14.3 70.1-10.2 84.1s-59.7-.9-78.5-33.3S-2.7 179.3 21.8 165.3s59.7 .9 78.5 33.3zM69.2 401.2C121.6 259.9 214.7 224 256 224s134.4 35.9 186.8 177.2c3.6 9.7 5.2 20.1 5.2 30.5l0 1.6c0 25.8-20.9 46.7-46.7 46.7c-11.5 0-22.9-1.4-34-4.2l-88-22c-15.3-3.8-31.3-3.8-46.6 0l-88 22c-11.1 2.8-22.5 4.2-34 4.2C84.9 480 64 459.1 64 433.3l0-1.6c0-10.4 1.6-20.8 5.2-30.5zM421.8 282.7c-24.5-14-29.1-51.7-10.2-84.1s54-47.3 78.5-33.3s29.1 51.7 10.2 84.1s-54 47.3-78.5 33.3zM310.1 189.7c-32.3-10.6-46.9-53.9-32.6-96.8s52.1-69.1 84.4-58.5s46.9 53.9 32.6 96.8s-52.1 69.1-84.4 58.5z"/>
                            </svg>
                            <p class="text-center mt-3">Total Pets</p>
                            <p class="text-center"><?php echo $countPets; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-4 d-flex justify-content-center">
                    <div class="card shadow rounded-4 p-3" style="width: 250px; height: 180px;">
                        <div class="card-title text-center">
                            <svg class="mt-4" xmlns="http://www.w3.org/2000/svg" width="80" height="50" fill="blue" class="bi bi-person-fill-check" viewBox="0 0 16 16">
                                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"/>
                            </svg>
                            <p class="text-center mt-3">Pending Appointments</p>
                            <p class="text-center"><?php echo $countPending; ?></p>
                        </div>
                    </div>
                </div>
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
            window.location.href = 'index.php';
        });
    </script>";
        exit();
    }

    include 'layout/footer.php'; 
?>