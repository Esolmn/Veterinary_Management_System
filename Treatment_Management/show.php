<?php 
    require_once '../database/Database.php';
    require_once '../models/Treatment.php';

    $database = new Database();
    $db = $database->getConnection();

    Treatment::setConnection($db);
    $treatment = Treatment::find($_GET['id']);

    if(!$treatment){
        header('Location: index.php');
        exit();
    }

    include '../layout/header.php';

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            //display website
?>

<div class="container-xxl p-4 shadow rounded-5 mt-5">
    <div class="row gx-4 gy-4">
        <!-- Left Side: Treatment Details Card -->
        <div class="col-md-4 d-flex justify-content-center">
            <div class="card w-100 shadow rounded-4 p-4 mb-4 mb-md-0">
                <div class="card-header bg-white border-0 pb-0">
                    <h2 class="text-center fw-bolder mb-3" style="color: orange;">Treatment Details</h2>
                </div>
                <div class="card-body pt-3">
                    <div class="row gx-3 mb-2">
                        <div class="col-md-5 fw-bolder">Treatment ID:</div>
                        <div class="col-md-7"><?=$treatment->id?></div>
                    </div>
                    <div class="row gx-3 mb-2">
                        <div class="col-md-5 fw-bolder">Treatment Name:</div>
                        <div class="col-md-7"><?=$treatment->treatment_name?></div>
                    </div>
                    <div class="row gx-3 mb-2">
                        <div class="col-md-5 fw-bolder">Recipient ID (Pet ID):</div>
                        <div class="col-md-7"><?=$treatment->pet_id?></div>
                    </div>
                    <div class="row gx-3 mb-2">
                        <div class="col-md-5 fw-bolder">Diagnosis:</div>
                        <div class="col-md-7"><?=$treatment->diagnosis?></div>
                    </div>
                    <div class="row gx-3 mb-2">
                        <div class="col-md-5 fw-bolder">Description:</div>
                        <div class="col-md-7"><?=$treatment->description?></div>
                    </div>
                    <div class="row gx-3 mb-2">
                        <div class="col-md-5 fw-bolder">Date:</div>
                        <div class="col-md-7"><?=$treatment->date?></div>
                    </div>
                    <div class="row gx-3 mb-2">
                        <div class="col-md-5 fw-bolder">Doctor Fee:</div>
                        <div class="col-md-7">₱<?=number_format($treatment->doctor_fee, 2)?></div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 d-flex justify-content-center align-items-center gap-2 flex-wrap pt-3">
                    <div class="row align-items-stretch g-2">
                        <div class="col d-flex justify-content-center align-items-stretch">
                            <a href="../reports/treatments_indivreport.php?id=<?=$treatment->id?>" target="_blank" class="btn btn-success w-100 h-100 d-flex align-items-center justify-content-center">View</a>
                        </div>
                        <div class="col d-flex justify-content-center align-items-stretch">
                            <a href="../reports/treatments_indivreport.php?action=D&id=<?=$treatment->id?>" target="_blank" class="btn btn-warning w-100 h-100 d-flex align-items-center justify-content-center">Download</a>
                        </div>
                        <div class="col d-flex justify-content-end align-items-stretch">
                            <a class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-center" href="index.php">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: List of Treatment Products Card -->
        <div class="col-md-8">
            <div class="card shadow rounded-4 w-100 bg-white p-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h2 class="text-center fw-bolder mb-3" style="color: orange;">List of Treatment Products</h2>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table id="vetTable" class="table table-striped table-hover table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Quantity Used</th>
                                    <th>Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 1;
                                    $totalCost = 0;
                                    foreach($treatment->products() as $product) :
                                        $totalCost += $product->cost;
                                ?>
                                <tr>
                                    <td><?=$i++?></td>
                                    <td><?=$product->product_name?></td>
                                    <td><?=$product->quantity?></td>
                                    <td>₱<?=number_format($product->cost, 2)?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="text-end mt-5">
                            <h5><strong>Total Cost:</strong> ₱<?=number_format($totalCost + $treatment->doctor_fee, 2)?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    } else {
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
    }
    include '../layout/footer.php';
?>
