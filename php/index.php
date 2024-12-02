<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Archivos</title>
</head>
<body>
    <h1>Listado de Archivos</h1>
    <?php
    $archivos = array_diff(scandir('.'), ['.', '..']);
    echo "<ul>";
    foreach ($archivos as $archivo) {
        $fechaModificacion = date("F d Y H:i:s", filemtime($archivo));
        echo "<li><h3>$archivo</h3><a href=\"$archivo\" target=\"_blank\">$fechaModificacion</a></li>";
    }
    echo "</ul>";
    ?>
</body>
</html>


