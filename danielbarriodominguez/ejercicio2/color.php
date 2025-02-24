<?php
    setcookie(
        name: "bg_color",
        value: $_POST["color"] ?? "#72373d",
        expires_or_options: time() +  60*60*24*365 
    );

    $color = $_COOKIE["bg_color"] ?? "red";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>color de fondo</title>
    <style>
        body {
            background: <?= $color ?>
        }
    </style>
</head>
<body>
    <form action="<?= $_SERVER["PHP_SELF"]?>" method="post">
        <label for="color">Seleccione color de fondo</label><br>
        <input type="color" name="color" id="color" ><br>
        <button type="submit">Cambiar</button>
    </form>
</body>
</html>

