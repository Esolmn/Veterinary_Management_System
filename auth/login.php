    <?php 
        //required folder and files
        include "../layout/header.php";
        require_once "../database/Database.php";
        require_once "../models/User.php";
    ?>

    <?php

        $db = new Database();
        $conn = $db->getConnection();
        User::setConnection($conn);

        //titignan kung may naka login na user
        if(isset($_SESSION['email'])){
            header('Location: ../index.php'); //redirect to index.php 
            exit();  
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){ //chinecheck kung POST ang ginamit sa create.php kung saan ka gumawa ng user account
            
            $user = User::authByEmail($_POST['email']);

            if($user){
                if(password_verify($_POST['password'], $user->password)){ 

                    if($user->status != 'active') { //pag check kung active or hindi
                        $_SESSION['error'] = 'User Account Inactive';
                        header('Location: login.php'); //balik login kapag inactive
                        exit(); // stop ng execution sa ibang code
                    }

                    $_SESSION['email'] = $user->email;
                    $_SESSION['role'] = $user->role;
                    $_SESSION['id'] = $user->id;
                    $_SESSION['name'] = $user->name;
                    header('Location: ../index.php'); // pag active diretso na rito
                    exit();
                }
                else{
                    $_SESSION['error'] = 'Invalid email or password';
                }
            }
            else{
                $_SESSION['error'] = 'Invalid email or password';   
            }
        }
    ?>
    <div class="container-xxl d-flex flex-column justify-content-center align-items-center" style="height: 100vh;">
        <div class="mb-4 text-center">
            <h1 class="Title fw-bold" style="font-size: 4rem;">VetCare</h1>
        </div>
        <div class="card shadow rounded-5 p-5" style="width: 400px; height: 500px;">
            <div class="card-title text-center">
                <h1>
                    <svg class="mt-4" xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="orange" class="bi bi-people-fill" viewBox="0 0 512 512">
                        <path d="M226.5 92.9c14.3 42.9-.3 86.2-32.6 96.8s-70.1-15.6-84.4-58.5s.3-86.2 32.6-96.8s70.1 15.6 84.4 58.5zM100.4 198.6c18.9 32.4 14.3 70.1-10.2 84.1s-59.7-.9-78.5-33.3S-2.7 179.3 21.8 165.3s59.7 .9 78.5 33.3zM69.2 401.2C121.6 259.9 214.7 224 256 224s134.4 35.9 186.8 177.2c3.6 9.7 5.2 20.1 5.2 30.5l0 1.6c0 25.8-20.9 46.7-46.7 46.7c-11.5 0-22.9-1.4-34-4.2l-88-22c-15.3-3.8-31.3-3.8-46.6 0l-88 22c-11.1 2.8-22.5 4.2-34 4.2C84.9 480 64 459.1 64 433.3l0-1.6c0-10.4 1.6-20.8 5.2-30.5zM421.8 282.7c-24.5-14-29.1-51.7-10.2-84.1s54-47.3 78.5-33.3s29.1 51.7 10.2 84.1s-54 47.3-78.5 33.3zM310.1 189.7c-32.3-10.6-46.9-53.9-32.6-96.8s52.1-69.1 84.4-58.5s46.9 53.9 32.6 96.8s-52.1 69.1-84.4 58.5z"/>
                    </svg>
                </h1>
                <h2 class="text-center fw-bold mt-3 mb-4" style="font-size: 2rem; color: orange;">LOGIN</h2>
            </div>
            <form action="login.php" method="POST">
                <div class="row">
                    <div class="col-12 mb-4">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                            class="form-control <?=(isset($_SESSION['error']) ? 'is-invalid' : null )?>" required>
                        <?php if(isset($_SESSION['error'])) :  ?>
                        <div class="invalid-feedback">
                            <?= $_SESSION['error'] ?>
                        </div>
                        <?php 
                            endif;
                            unset($_SESSION['error']);
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-4">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-warning w-100 rounded-3 btn-lg">Login</button>    
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php include '../layout/footer.php'; ?>