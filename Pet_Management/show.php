<?php 
    session_start();
    require_once  '../database/Database.php';
    require_once '../models/Pet.php'; 
    require_once '../models/User.php';
    require_once '../models/Treatment.php';

    $db = new Database();
    $conn = $db->getConnection();

    Pet::setConnection($conn);
    $pet = Pet::find($_GET['id']);

    if (!$pet) {
        header('Location: index.php');
        exit();
    }

    include '../layout/header.php';

    Treatment::setConnection($conn); 
    $treatments = Treatment::all();
    $treatment = Treatment::find($_GET['id']);

    Product::setConnection($conn);
    $products = Product::all();
    $product = Product::find($_GET['id']);

    User::setConnection($conn);
    $users = User::find($pet->user_id); //pangkuha ng pet owner

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            //display website
?>

<div class="container-xxl p-4 rounded-5 mt-4">
    <div class="row gx-2">
        <div class="col-4">
            <d class="card rounded-4 p-4 text-center">
                <div class="card shadow-gradient rounded-4 p-2">
                    <div class="card-body text-center">
                        <h1 class="text-center mb-4" style="color: orange">Pet Owner:</h1>
                        <h2 class="text-center mt-3"><?=$users->name?></h2> 
                    </div>
                </div>
                <div class="card shadow-gradient rounded-4 p-2 mt-3">
                    <div class="d-flex justify-content-between gap-3">
                        <a class="btn btn-success w-50 rounded-3" href="../reports/pet_report.php?id=<?= $pet->id ?>">View</a>
                        <a class="btn btn-warning w-50 rounded-3" href="../reports/pet_report.php?action=D&id=<?= $pet->id ?>">Download</a>
                        <a class="btn btn-danger w-50 rounded-3" href="index.php">Back</a>
                    </div>
                </div>
            </div>
        <div class="col-8">
            <div class="card shadow rounded-4 p-2">
                <div class="card-body text-center">
                    <h1 class="text-center mb-4" style="color: orange">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="40" fill="orange" viewBox="0 0 512 512">
                            <path d="M226.5 92.9c14.3 42.9-.3 86.2-32.6 96.8s-70.1-15.6-84.4-58.5s.3-86.2 32.6-96.8s70.1 15.6 84.4 58.5zM100.4 198.6c18.9 32.4 14.3 70.1-10.2 84.1s-59.7-.9-78.5-33.3S-2.7 179.3 21.8 165.3s59.7 .9 78.5 33.3zM69.2 401.2C121.6 259.9 214.7 224 256 224s134.4 35.9 186.8 177.2c3.6 9.7 5.2 20.1 5.2 30.5l0 1.6c0 25.8-20.9 46.7-46.7 46.7c-11.5 0-22.9-1.4-34-4.2l-88-22c-15.3-3.8-31.3-3.8-46.6 0l-88 22c-11.1 2.8-22.5 4.2-34 4.2C84.9 480 64 459.1 64 433.3l0-1.6c0-10.4 1.6-20.8 5.2-30.5zM421.8 282.7c-24.5-14-29.1-51.7-10.2-84.1s54-47.3 78.5-33.3s29.1 51.7 10.2 84.1s-54 47.3-78.5 33.3zM310.1 189.7c-32.3-10.6-46.9-53.9-32.6-96.8s52.1-69.1 84.4-58.5s46.9 53.9 32.6 96.8s-52.1 69.1-84.4 58.5z"/>
                        </svg>
                        <span class="ms-2 mt-4">Pet Details</span>
                    </h1>
                    <div class="shadow-gradient rounded-4 p-2">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">Pet Name</th>
                                <th class="text-center">Birthdate</th>
                                <th class="text-center">Breed</th>
                                <th class="text-center">Specie</th>
                                <th class="text-center">Gender</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center"><?=$pet->name?></td>
                                <td class="text-center"><?=$pet->birthdate?></td>
                                <td class="text-center"><?=$pet->breed?></td>
                                <td class="text-center"><?=$pet->specie?></td>
                                <td class="text-center"><?=$pet->gender?></td>
                                <td class="text-center"><?=$pet->status?></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3 mt-4">
    <div class="col-12">
        <div class="card shadow rounded-4 p-4">
            <h4 class="mb-4 text-center" style="color: orange; font-size: 30px">Treatments</h4>
            <div class="card shadow-gradient rounded-4 p-2">
                <table class="table table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Treatment Name</th>
                            <th>Diagnosis</th>
                            <th>Description</th>
                            <th>Doctor Fee</th>
                            <th>Date</th>
                            <th>Total Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $treatments = Treatment::where('pet_id', '=', $pet->id);//
                        if(count($treatments)>0):
                            foreach ($treatments as $treatment):
                                $products = Product::where('treatment_id', '=', $treatment->id);
                                $totalProductCost = 0;
                                foreach ($products as $product) {
                                    $totalProductCost += $product->cost;
                                }
                                $totalCost = $totalProductCost + $treatment->doctor_fee;
                    ?>
                        <tr>
                            <td><?= $treatment->id ?></td>
                            <td><?= $treatment->treatment_name ?></td>
                            <td><?= $treatment->diagnosis ?></td>
                            <td style="white-space: normal; width: 25%;"><?= $treatment->description ?></td>
                            <td>₱<?= number_format($treatment->doctor_fee, 2) ?></td>
                            <td><?= $treatment->date ?></td>
                            <td><strong>₱<?= number_format($totalCost, 2) ?></strong></td>
                        </tr>
                    <?php endforeach; 
                    else: ?>
                    <tr>
                <td colspan="7" class="text-center">No treatments found for this pet.</td>
                    </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
 
    <div class="row gx-3 mt-4">
    <div class="col-12">
        <div class="card shadow rounded-4 p-4 mb-3">
            <h4 class="mb-4 text-center" style="color: orange; font-size: 30px">Products Used</h4>
            <div class="card shadow-gradient rounded-4 p-2">
                <table class="table table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>Treatment Name</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $treatments = Treatment::where('pet_id', '=', $pet->id);
                        $hasProducts = false;

                        foreach ($treatments as $treatment) {
                            $products = Product::where('treatment_id', '=', $treatment->id);
                            $totalProductCost = 0;

                            if (count($products) > 0) {
                                $hasProducts = true;
                                foreach ($products as $product) {
                                    $totalProductCost += $product->cost;
                                    ?>
                                    <tr>
                                        <td><?= $treatment->treatment_name ?></td>
                                        <td><?= $product->product_name ?></td>
                                        <td><?= $product->quantity ?></td>
                                        <td>₱<?= number_format($product->cost, 2) ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr class="table-warning">
                                    <th colspan="3" class="text-start">&nbsp;&nbsp;Subtotal for <?= $treatment->treatment_name ?></th>
                                    <td><strong>₱<?= number_format($totalProductCost, 2) ?></strong></td>
                                </tr>
                                <?php
                            }
                        }
                        if (!$hasProducts):
                            ?>
                            <tr>
                                <td colspan="4">No products used for this pet's treatments.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
                window.location.href = '../index.php';
            });
        </script>";
            exit();
        }

    include '../layout/footer.php';
?>
