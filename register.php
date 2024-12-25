<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <h1>Registro de usuario</h1>
    
<section class="vh-100" style="background-color: #eee;">



<?php
require_once("connection.php");
require_once("util.php");
if( isset($_POST["email"], $_POST["pwd1"], $_POST["pwd2"])){
    $email = $_POST["email"];
    $pwd1 = $_POST["pwd1"];
    $pwd2 = $_POST["pwd2"];

    if($pwd1!==$pwd2){
        addError("Las contraseñas no coinciden");
    }
    else{
        if(register($email, $pwd1)){
            mostrarMsg("Se ha creado el usuario con éxito", "success");
        }
       
    }
    mostrarError();

}

?>

<div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
            <div class="card text-black" style="border-radius: 25px;">
                <div class="card-body p-md-5">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                            <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Regístrese</p>

                            <form class="mx-1 mx-md-4" method="post">



                                <div class="d-flex flex-row align-items-center mb-4">
                                    <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                    <div class="form-outline flex-fill mb-0">
                                        <input type="email" id="form3Example3c" class="form-control" name="email" required/>
                                        <label class="form-label" for="form3Example3c">Email</label>
                                    </div>
                                </div>

                                <div class="d-flex flex-row align-items-center mb-4">
                                    <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                    <div class="form-outline flex-fill mb-0">
                                        <input type="password" id="form3Example4c" class="form-control" name="pwd1" required/>
                                        <label class="form-label" for="form3Example4c">Contraseña</label>
                                    </div>
                                </div>

                                <div class="d-flex flex-row align-items-center mb-4">
                                    <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                                    <div class="form-outline flex-fill mb-0">
                                        <input type="password" id="form3Example4cd" class="form-control"  name="pwd2" required/>
                                        <label class="form-label" for="form3Example4cd">Repita su contraseña</label>
                                    </div>
                                </div>





                                <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                    <input type="submit" class="btn btn-primary btn-lg mx-3" value="Registrar usuario"/>
                                    <a class="btn btn-secondary btn-lg" href="login.php" role="button">Login</a>
                                </div>
                               

                            </form>

                        </div>
                        <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                            <img src="assets/user.png"
                                 class="img-fluid" alt="Sample image">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</section>
</body>
</html>