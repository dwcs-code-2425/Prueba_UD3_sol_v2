<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <h1>Productos por Categoría</h1>

    <?php
    const CATS_COOKIE = 60 * 60 * 24 * 15; //15 DÍAS EN segundos
    require_once 'connection.php';

    iniciarSesion();
    if (!isset($_SESSION["usuario"])){
        header("location: login.php");
        exit;
    }
    if (isset($_GET["catid"])) {
        $catId = $_GET["catid"];

        if (isset($_COOKIE["catIds"])) {
            $array = explode(",", $_COOKIE["catIds"]);
            if (!in_array($catId, $array)) {
                $array[] = $catId;
                $csv_cats = implode(",", $array);
                setcookie("catIds", $csv_cats, time() + CATS_COOKIE);
            }
        } else {
            setcookie("catIds", $catId, time() + CATS_COOKIE);
        }


        $numProdutos = countProductosByCatId($catId);
        ?>
        <p> Se han encontrado <?= $numProdutos ?> productos para la categoría con id = <?= $catId ?></p>
        <?php

    }
    mostrarError();


    ?>



</body>

</html>