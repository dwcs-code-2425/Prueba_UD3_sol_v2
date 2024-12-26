<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <h1>Login</h1>
    <div class="container-fluid">


        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-6">
                <form method="post">
                    <!-- Email input -->
                    <div class="form-group mb-4 ">
                        <label class="form-label" for="email">Email address</label>
                        <input type="email" id="email" class="form-control" name="email" required />

                    </div>

                    <!-- Current Password input -->
                    <div class="form-group mb-4">
                        <label class="form-label" for="currentPwd">Contraseña actual</label>
                        <input type="password" id="currentPwd" class="form-control" name="pwd" required />

                    </div>

                   


                    <!-- Submit button -->
                    <input type="submit" class="btn btn-primary btn-block mb-4" value="Iniciar sesión"></button>
                    <a href="register.php" class="btn btn-secondary btn-block mb-4">Regístrese aquí</a>

                </form>

            </div>
        </div>
    </div>




</body>

</html>
<?php

require_once 'connection.php';
if (isset($_POST["email"], $_POST["pwd"])) {
    $email = $_POST["email"];
    $pass = $_POST["pwd"];

    if (login($email, $pass)) {
        iniciarSesion();
        $_SESSION["usuario"] = $email;

        

        header("location: categorias.php");
        exit;
    } else {
        addError("Los datos introducidos no son correctos");
    }

}
mostrarError();
?>