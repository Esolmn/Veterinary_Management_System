<?php
    include '../../layout/header.php';
    require_once '../../models/Model.php';
    require_once '../../models/User.php';
    require_once '../../database/Database.php';
    
    $db = new Database();
    $conn = $db->getConnection();
    User::setConnection($conn);
    $users = User::all();

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            //display website

?>

<div class="container-xxl p-4 shadow rounded mt-5" style="background-color: white;"> 
    <h1 class="text-center" style="color: orange;">Pet Owners</h1>
    <div class="mt-4 mb-4 d-flex justify-content-between">
    <a class="btn custom-purple-btn" href="create.php">Create Account</a>
        <div class="ml-auto">
            <a href="../../reports/pet_owner_report.php" target="_blank" class="btn btn-success">Users PDF</a>
        </div>
    </div>
    <!-- to apply the datatable downloaded need lagyan id and name ang table -->
    <table id="petOwnerTable" class="table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <th style="color:crimson">ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($users as $user) :
                    if($user->role !== 'pet_owner') continue;
            ?>
                <tr>
                    <td style="color:crimson"><?=$user->id ?></td>
                    <td><?=$user->name ?></td>
                    <td><?=$user->email ?></td>
                    <td><?=$user->role ?></td>
                    <td class="text-center"><?=$user->status ?></td>
                    <td>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin') {
                echo '<a class="btn btn-primary me-1" href="edit.php?id=' . $user->id . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                        </svg>
                    </a>';
                    } ?>
                    <a class="btn btn-danger me-2" onclick="
                            const swalWithBootstrapButtons = Swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                    cancelButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            });
                            swalWithBootstrapButtons.fire({
                                title: 'Are you sure?',
                                text: 'You won\'t be able to revert this!',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, delete it!',
                                cancelButtonText: 'No, cancel!',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed){
                                    window.location.href = 'delete.php?id=<?=$user->id?>';
                                }       
                            });
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                            </svg>
                        </a>
                        <?php if ($user->status == 'active'): ?>
                            <a class="btn btn-warning me-2" onclick="
                                Swal.fire({
                                    title: 'Manage Admin',
                                    text: 'Do you want to deactivate this admin?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Deactivate',
                                    cancelButtonText: 'Cancel',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'status.php?id=<?=$user->id?>&status=inactive'; // Passing inactive as status
                                    }
                                });
                            ">Deactivate
                            </a>
                        <?php elseif ($user->status == 'inactive'): ?>
                            <a class="btn btn-success me-2" onclick="
                                Swal.fire({
                                    title: 'Manage Admin',
                                    text: 'Do you want to activate this admin?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Activate',
                                    cancelButtonText: 'Cancel',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'status.php?id=<?=$user->id?>&status=active'; // Passing active as status
                                    }
                                });
                            ">Activate
                            </a>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
                            echo '<a class="btn custom-purple-btn" onclick="
                                Swal.fire({
                                    title: \'Manage Admin\',
                                    text: \'Do you want to reset password?\',
                                    icon: \'warning\',
                                    showCancelButton: true,
                                    confirmButtonText: \'Reset Password\',
                                    cancelButtonText: \'Cancel\',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = \'reset_password.php?id=' . $user->id . '\';
                                    }
                                });
                            ">Reset Password</a>';
                        } ?>
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
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