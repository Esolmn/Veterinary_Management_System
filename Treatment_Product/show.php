<?php 
    require_once '../database/Database.php';
    require_once '../models/User.php'; 
    require_once '../models/Treatment.php'; 

    $database = new Database();
    $db = $database->getConnection();

    Product::setConnection($db);
    $id = $_GET['id'];
    $product = Product::find($id);

    Treatment::setConnection($db);
    $treatment = Treatment::find($product->treatment_id);

    if(!$product){
        header('Location: index.php');
        exit();
    }
    
    include '../layout/header.php'; 

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            //display website
?>

<div class="container-xxl p-4 rounded-5 mt-5">
    <div class="row gx-4 gy-4">
        <!-- Left Side: Product Details Card -->
        <div class="col-md-4 d-flex justify-content-center">
            <div class="card w-100 shadow rounded-4 p-4 mb-4 mb-md-0">
                <div class="card-header bg-white border-0 pb-0">
                    <h2 class="text-center fw-bolder mb-3" style="color: orange;">Product Details</h2>
                </div>
                <div class="card-body pt-3">
                    <div class="card shadow-gradient rounded-4 p-4">
                        <div class="row gx-3 mb-2">
                            <div class="col-md-5 fw-bolder">Product Name:</div>
                            <div class="col-md-7"><?=$product->product_name?></div>
                        </div>
                        <div class="row gx-3 mb-2">
                            <div class="col-md-5 fw-bolder">Assigned Treatment:</div>
                            <div class="col-md-7"><?=$product->treatment_id?> - <?=$treatment->treatment_name?></div>
                        </div>
                        <div class="row gx-3 mb-2">
                            <div class="col-md-5 fw-bolder">Cost Price:</div>
                            <div class="col-md-7"><?=$product->cost_price?></div>
                        </div>
                        <div class="row gx-3 mb-2">
                            <div class="col-md-5 fw-bolder">Retail Price:</div>
                            <div class="col-md-7"><?=$product->retail_price?></div>
                        </div>
                        <div class="row gx-3 mb-2">
                            <div class="col-md-5 fw-bolder">Quantity:</div>
                            <div class="col-md-7"><?=$product->quantity?></div>
                        </div>
                        <div class="row gx-3 mb-2">
                            <div class="col-md-5 fw-bolder">Cost:</div>
                            <div class="col-md-7"><?=$product->cost?></div>
                        </div>
                    </div>
                </div>
                 <div class="card-footer bg-white border-0 d-flex justify-content-center align-items-center gap-2 flex-wrap pt-3">
                    <div class="row align-items-stretch g-2">
                        <div class="col d-flex justify-content-end align-items-stretch">
                            <a class="btn btn-danger w-100 h-100 d-flex align-items-center justify-content-center" href="index.php">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Treatment Preview Card -->
        <div class="col-md-8">
            <div class="card shadow rounded-4 w-100 bg-white p-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h2 class="text-center fw-bolder mb-3" style="color: orange;">Treatment Preview</h2>
                </div>
                <div class="card-body pt-3">
                    <div class="card shadow-gradient rounded-4 p-4">
                        <div class="table-responsive">
                            <table id="vetTable" class="table table-striped table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Pet ID</th>
                                        <th>Diagnosis</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th>Doctor Fee</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $product_treatment = Treatment::where('id', '=', $product->treatment_id);
                                        foreach($product_treatment as $treatment) :
                                    ?>
                                    <tr>
                                        <td><?=$treatment->id?></td>
                                        <td><?=$treatment->treatment_name?></td>
                                        <td><?=$treatment->pet_id?></td>
                                        <td><?=$treatment->diagnosis?></td>
                                        <td><?=$treatment->description?></td>
                                        <td><?=$treatment->date?></td>
                                        <td>₱<?=number_format($treatment->doctor_fee, 2)?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> >        

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