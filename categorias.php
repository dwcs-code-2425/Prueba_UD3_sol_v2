<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <h1>Categorías</h1>
    <?php

    require_once 'connection.php';
    $categorias = getCategories();


    if ($categorias) {
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Categoría</th>
                    <th></th>

                </tr>
            </thead>
            <tbody>
                <?php


                foreach ($categorias as $categoria) {
                    ?>
                    <tr>
                        <td><?= $categoria["CategoryID"] ?></td>
                        <td><?= $categoria["CategoryName"] ?></td>
                        <td>



                            <a class="btn btn-primary" href="productos.php?catid=<?= $categoria["CategoryID"] ?>"
                                role="button">Ver productos</a>

                        </td>
                    </tr>
                    <?php
                }
    }


    ?>
        </tbody>
    </table>

    <?php
    mostrarError();
    ?>
</body>

</html>