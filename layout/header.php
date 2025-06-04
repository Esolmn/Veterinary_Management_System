<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start([
            'cookie_lifetime' => 86400 // 1 day
        ]);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="/Veterinary_Management_System/layout/Asset/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/Veterinary_Management_System/layout/Asset/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/Veterinary_Management_System/layout/Asset/favicon-16x16.png">
    <link rel="manifest" href="/Veterinary_Management_System/layout/Asset/site.webmanifest">
    <title>VetCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.2.2/af-2.7.0/b-3.2.2/b-colvis-3.2.2/b-html5-3.2.2/b-print-3.2.2/cr-2.0.4/date-1.5.5/fc-5.0.4/fh-4.0.1/kt-2.12.1/r-3.0.4/rg-1.5.1/rr-1.5.0/sc-2.4.3/sb-1.8.2/sp-2.3.3/sl-3.0.0/sr-1.4.1/datatables.min.css" rel="stylesheet" integrity="sha384-6gM1RUmcWWtU9mNI98EhVNlLX1LDErxSDu2o/YRIeXq34o77tQYTXLzJ/JLBNkNV" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&family=Varela&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/Veterinary_Management_System/layout/Asset/style.css">

    
</head>
<body>

<?php 

    $current_link = $_SERVER['REQUEST_URI']; //nilalagay buong url 

    function activeLink($path) {
        $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); //inaalis ung mga ? or like store.php?id=2 ganyan

        return $current_path === $path ? 'active-link' : '';
    }

    if(!isset($_SESSION['email']) && strpos($current_link, 'login.php') === false) {
        header('Location: /Veterinary_Management_System/auth/login.php');
        exit();
    }

    if(strpos($current_link, 'login.php') === false) {
?>

<nav class="navbar glass-navbar rounded-5 shadow-sm mt-3 ms-5 me-5 navbar-expand-lg">
    <div class="container-fluid">
        <a class="Title navbar-brand fw-bold ms-3" href="/Veterinary_Management_System/index.php">VetCare</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVetcare" aria-controls="navbarVetcare" aria-expanded="false" aria-label="Toggle navigation">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="orange" class="bi bi-people-fill" viewBox="0 0 512 512">
                <path d="M226.5 92.9c14.3 42.9-.3 86.2-32.6 96.8s-70.1-15.6-84.4-58.5s.3-86.2 32.6-96.8s70.1 15.6 84.4 58.5zM100.4 198.6c18.9 32.4 14.3 70.1-10.2 84.1s-59.7-.9-78.5-33.3S-2.7 179.3 21.8 165.3s59.7 .9 78.5 33.3zM69.2 401.2C121.6 259.9 214.7 224 256 224s134.4 35.9 186.8 177.2c3.6 9.7 5.2 20.1 5.2 30.5l0 1.6c0 25.8-20.9 46.7-46.7 46.7c-11.5 0-22.9-1.4-34-4.2l-88-22c-15.3-3.8-31.3-3.8-46.6 0l-88 22c-11.1 2.8-22.5 4.2-34 4.2C84.9 480 64 459.1 64 433.3l0-1.6c0-10.4 1.6-20.8 5.2-30.5zM421.8 282.7c-24.5-14-29.1-51.7-10.2-84.1s54-47.3 78.5-33.3s29.1 51.7 10.2 84.1s-54 47.3-78.5 33.3zM310.1 189.7c-32.3-10.6-46.9-53.9-32.6-96.8s52.1-69.1 84.4-58.5s46.9 53.9 32.6 96.8s-52.1 69.1-84.4 58.5z"/>
            </svg> <!-- nag gegenerate paw button pag nag collapse -->
        </button>
        <div class="collapse navbar-collapse" id="navbarVetcare">
            <div class="d-flex align-items-center w-100">
                <div class="vertical-line ms-2 me-3 d-none d-lg-block"></div>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if(isset($_SESSION['role']) && ($_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin')) : ?>
                        <li class="nav-item"><a class="nav-link ms-2 <?= activeLink('/Veterinary_Management_System/Admin_Dashboard.php') ?>" href="/Veterinary_Management_System/Admin_Dashboard.php">Dashboard</a></li>
                    <?php else : ?>
                    <li class="nav-item"><a class="nav-link ms-2 <?= activeLink('/Veterinary_Management_System/Pet_Owner_Dashboard/index.php') ?>" href="/Veterinary_Management_System/Pet_Owner_Dashboard/index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link ms-2 <?= activeLink('/Veterinary_Management_System/Pet_Owner_Dashboard/appointments.php') ?>" href="/Veterinary_Management_System/Pet_Owner_Dashboard/appointments.php">Appointments</a></li>
                    <li class="nav-item"><a class="nav-link ms-2 <?= activeLink('/Veterinary_Management_System/Pet_Owner_Dashboard/treatments.php') ?>" href="/Veterinary_Management_System/Pet_Owner_Dashboard/treatments.php">Pets Treatments</a></li>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['role']) && ($_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin')) : ?>
                        <li class="nav-item"><a class="nav-link ms-4 <?= activeLink('/Veterinary_Management_System/Pet_Owner_Management/index.php') ?>" href="/Veterinary_Management_System/Pet_Owner_Management/index.php">Pet Owners</a></li>
                        <li class="nav-item"><a class="nav-link ms-4 <?= activeLink('/Veterinary_Management_System/Pet_Management/index.php') ?>" href="/Veterinary_Management_System/Pet_Management/index.php">Pets</a></li>
                        <li class="nav-item"><a class="nav-link ms-4 <?= activeLink('/Veterinary_Management_System/Treatment_Management/index.php') ?>" href="/Veterinary_Management_System/Treatment_Management/index.php">Treatments</a></li>
                        <li class="nav-item"><a class="nav-link ms-4 <?= activeLink('/Veterinary_Management_System/Treatment_Product/index.php') ?>" href="/Veterinary_Management_System/Treatment_Product/index.php">Products</a></li>
                        <li class="nav-item"><a class="nav-link ms-4 <?= activeLink('/Veterinary_Management_System/Available_Dates/index.php') ?>" href="/Veterinary_Management_System/Available_Dates/index.php">Available Dates</a></li>
                        <li class="nav-item"><a class="nav-link ms-4 <?= activeLink('/Veterinary_Management_System/Appointment_Management/index.php') ?>" href="/Veterinary_Management_System/Appointment_Management/index.php">Appointments</a></li>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0"> 
                    <?php if(isset($_SESSION['role']) && ($_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin')) { ?>   
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle me-4" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Manage Accounts</a>
                        <ul class="dropdown-menu">
                            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin') {
                                echo '<li><a class="dropdown-item" href="/Veterinary_Management_System/users/Admin_Management/index.php">Admins</a></li>';
                            } ?>
                            <li><a class="dropdown-item" href="/Veterinary_Management_System/users/Pet_Owners/index.php">Pet Owners</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <div class="vertical-line mt-2 me-4 d-none d-lg-block"></div>
                    <li class="nav-item dropdown">
                        <a class="nav-link me-4" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="orange" class="bi bi-people-fill" viewBox="0 0 512 512">
                                <path d="M226.5 92.9c14.3 42.9-.3 86.2-32.6 96.8s-70.1-15.6-84.4-58.5s.3-86.2 32.6-96.8s70.1 15.6 84.4 58.5zM100.4 198.6c18.9 32.4 14.3 70.1-10.2 84.1s-59.7-.9-78.5-33.3S-2.7 179.3 21.8 165.3s59.7 .9 78.5 33.3zM69.2 401.2C121.6 259.9 214.7 224 256 224s134.4 35.9 186.8 177.2c3.6 9.7 5.2 20.1 5.2 30.5l0 1.6c0 25.8-20.9 46.7-46.7 46.7c-11.5 0-22.9-1.4-34-4.2l-88-22c-15.3-3.8-31.3-3.8-46.6 0l-88 22c-11.1 2.8-22.5 4.2-34 4.2C84.9 480 64 459.1 64 433.3l0-1.6c0-10.4 1.6-20.8 5.2-30.5zM421.8 282.7c-24.5-14-29.1-51.7-10.2-84.1s54-47.3 78.5-33.3s29.1 51.7 10.2 84.1s-54 47.3-78.5 33.3zM310.1 189.7c-32.3-10.6-46.9-53.9-32.6-96.8s52.1-69.1 84.4-58.5s46.9 53.9 32.6 96.8s-52.1 69.1-84.4 58.5z"/>
                            </svg>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <div class="dropdown-item d-flex align-items-center gap-2">
                                    <span class="text-truncate" style="max-width: 150px;"><?php echo $_SESSION['name']; ?></span>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="logout dropdown-item" href="/Veterinary_Management_System/auth/logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>  
            </div>
        </div>
    </div>
</nav>

<?php } ?>