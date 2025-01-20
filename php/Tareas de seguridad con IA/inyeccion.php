<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciberseguridad Ofensiva - Tarea 1</title>
</head>
<body>
    <h1>Subir Imagen</h1>

    <?php
        if (isset($_FILES['imagen'])) {
            $nombreImagen = $_FILES['imagen']['name'];
            $rutaTemp = $_FILES['imagen']['tmp_name'];

            move_uploaded_file($rutaTemp, "imagenes/".$nombreImagen);

            echo "<p>La imagen se ha subido correctamente</p>";
            echo "<img src='imagenes/{$nombreImagen}' alt='{$nombreImagen}'>";
        }
    ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="imagen">Selecciona tu imagen:</label><br>
        <input type="file" name="imagen"><br>
        <input type="submit" value="Subir">
    </form>

</body>
</html>