<?php
    require_once '../models/Model.php';
    require_once '../models/Treatment.php';
    require_once '../models/Product.php';
    require_once '../database/Database.php';
    include '../layout/header.php';
    
    $db = new Database();
    $conn = $db->getConnection();
    Product::setConnection($conn);
    $products = Product::all();

    Treatment::setConnection($conn);

    if(isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin') {
            //display website

?>

<div class="container-xxl p-4 shadow-gradient rounded-4 mt-5" style="background-color: white;"> 
    <h1 class="text-center fw-bolder mb-3" style="color: orange;">Products</h1>
    <div class="mt-4 mb-4 d-flex justify-content-between">
    <a class="btn custom-purple-btn" href="create.php">Add Product</a>
    </div>
    <!-- to apply the datatable downloaded need lagyan id and name ang table -->
    <table id="productTable" class="table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Alloted Treatment</th>
                <th>Cost Price</th>
                <th>Retail Price</th>
                <th>Quantity</th>
                <th>Cost</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $i = 1;
                foreach($products as $product) :
                    $treatment = Treatment::find($product->treatment_id);
            ?>
                <tr>
                    <td><?=$i++?></td>
                    <td><?=$product->product_name ?></td>
                    <td><?=$treatment->treatment_name ?></td>
                    <td><?=$product->cost_price ?></td>
                    <td><?=$product->retail_price ?></td>
                    <td><?=$product->quantity ?></td>
                    <td><?=$product->cost ?></td>
                    <td> 
                        
                        <a class="btn btn-success me-1" href="show.php?id=<?=$product->id ?>" >
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                            </svg>
                        </a>

                        <a class="btn btn-primary me-1" href="edit.php?id=<?=$product->id?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                            </svg>
                        </a>

                        <a class="btn btn-danger me-1" onclick="
                                const swalWithBootstrapButtons = Swal.mixin({
                                    customClass: {
                                        confirmButton: 'btn btn-success mx-2',
                                        cancelButton: 'btn btn-danger mx-2'
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
                                        window.location.href = 'delete.php?id=<?=$product->id?>';
                                    }       
                                });
                            ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                </svg>
                        </a>

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
            window.location.href = '../index.php';
        });
    </script>";
        exit();
    }

    include '../layout/footer.php';
?>