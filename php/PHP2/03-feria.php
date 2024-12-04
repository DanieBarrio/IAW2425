<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feria</title>
</head>
<body>
<?php
$fechaInicio = new DateTime(date("Y-m-d"));
// Fecha de finalización (puede ser la fecha actual)
$fechaFin = new DateTime('2025-05-06');
// Calcular la diferencia entre las fechas
$diferencia = $fechaInicio->diff($fechaFin);
// Acceder a los componentes de tiempo deseados
echo "Diferencia de días: " . $diferencia->days . " días<br>";
?> 
</body>
</html>