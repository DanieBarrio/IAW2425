<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lexicogafikramente</title>
</head>
<body>
<?php
$pala = array("Pepe", "Pepa", "Pela", "Peña", "Pena", "Amancio Ortega");
sort($pala);

foreach ($pala as $key => $val) {
    echo  $val . "<br>";
}
?>

</body>
</html>