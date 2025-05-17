<?php
    include '../layout/header.php';
    require_once '../database/Database.php';
    require_once '../models/User.php';
    require_once '../models/Pet.php';
    require_once '../models/Treatment.php';
    require_once '../models/Product.php';
    require_once '../models/Appointment.php';
    require_once '../models/AvailableDate.php';

    $db = new Database();
    $conn = $db->getConnection();
    User::setConnection($conn);
    Pet::setConnection($conn);
    Treatment::setConnection($conn);
    Product::setConnection($conn);

    $treatment = Treatment::find($_GET['id']);
    $treatment_product = Product::where('treatment_id', '=', $_GET['id']);
    $doctor_fee = $treatment->doctor_fee;
    $totalCost = 0;

?>  
    
<div class="container mt-5 d-flex justify-content-center align-items-center">    
    <div class="card shadow rounded-4 p-4">
        <p class="text-center fw-bold mt-2 fs-2 mb-4" style="color: orange;">Treatment Products</p>
        <div class="card shadow rounded-4 p-2">
            <div style="max-height: 400px; overflow-y: auto;">
                <table class="table">
                    <thead class="table table-hover" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($treatment_product)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">No product has been appointed to this treatment.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($treatment_product as $product): 
                                $totalCost += $product->cost;
                            ?>
                            <tr>
                                <td><?= $product->product_name ?></td>
                                <td><?= $product->quantity ?></td>
                                <td>₱<?= number_format($product->cost, 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="table-warning">
                                <td colspan="2" class="text-end fw-bold">Total Cost (Products + Doctor Fee)</td>
                                <td class="fw-bold">₱<?= number_format($totalCost + $doctor_fee, 2) ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
    include '../layout/footer.php';
?>