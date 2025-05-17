<?php 
    require '..\database\Database.php';
    require_once '..\models\Treatment.php';

    $database = new Database();
    $db = $database->getConnection();
    
    Product::setConnection($db);
    $product = Product::find($_GET['id']);

    Treatment::setConnection($db);
    $treatments = Treatment::all();
  
    if (!$product) {
        header('Location: index.php');
        exit();
    }
    
    include  '..\layout\header.php';
    if (isset($_SESSION['role']) && ($_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin')) {
            //display website
?>

<div class="container-xxl d-flex justify-content-center align-items-center mt-5">
    <div class="card shadow rounded-4 p-4" style="width: 700px;">
        <div class="card-title text-center">
            <h1>
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="purple" class="bi bi-capsule" viewBox="0 0 16 16">
                    <path d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z"/>
                </svg>
            </h1>
            <h2 class="fw-bold mt-3" style="color: orange;">Update Product</h2>
        </div>

        <form action="update.php?id=<?=$product->id?>" method="POST" class="mt-4">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" id="product_name" name="product_name" class="form-control" value="<?= $product->product_name ?>" required> 
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="treatment_id" class="form-label">Product Allotment</label>
                    <select id="treatment_id" name="treatment_id" class="form-select" required>
                                <option value="" disabled>Select a Treatment</option>
                                <?php foreach ($treatments as $treatment): ?>
                                    <option value="<?=$treatment->id?>" <?= $treatment->id == $product->treatment_id ? 'selected' : '' ?>>
                                        <?=$treatment->id?> - <?=$treatment->treatment_name?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="cost_price" class="form-label">Cost Price</label>
                    <input type="number" id="cost_price" name="cost_price" class="form-control" value="<?= $product->cost_price?>" required>
                </div>
                <div class="col-md-4">
                    <label for="retail_price" class="form-label">Retail Price</label>
                    <input type="number" id="retail_price" name="retail_price" class="form-control" value="<?= $product->retail_price?>" required>
                </div>
                <div class="col-md-4">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" value="<?= $product->quantity?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="cost" class="form-label">Cost</label>
                    <input type="number" id="cost" name="cost" class="form-control bg-secondary-subtle" value ="<?= $product->cost?>" readonly required step="any" style="background-color: #e9ecef;">                
                </div>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const retailPrice = document.getElementById('retail_price');
                const quantity = document.getElementById('quantity');
                const cost = document.getElementById('cost');

                function updateCost() {
                    const price = parseFloat(retailPrice.value) || 0;
                    const qty = parseFloat(quantity.value) || 0;
                    cost.value = price * qty;
                }

                retailPrice.addEventListener('input', updateCost);
                quantity.addEventListener('input', updateCost);
            });
            </script>

            <div class="d-grid">
                <div class="d-flex gap-2 mt-3">
                    <a href="index.php" class="btn btn-danger w-100 rounded-3">Cancel</a>
                    <button type="submit" class="btn btn-primary w-100 rounded-3">Update Product</button>
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

    include '../layout/footer.php';
?>