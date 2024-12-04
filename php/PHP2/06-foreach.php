<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refranes</title>
</head>
<body>
<?php
$refranes = array(
    "A quien madruga, Dios le ayuda.",
    "Más vale tarde que nunca.",
    "No hay mal que por bien no venga.",
    "Al mal tiempo, buena cara.",
    "En boca cerrada no entran moscas.",
    "El que la sigue, la consigue.",
    "Dime con quién andas y te diré quién eres.",
    "No dejes para mañana lo que puedas hacer hoy.",
    "Quien mucho abarca, poco aprieta.",
    "Camarón que se duerme, se lo lleva la corriente."
);
    echo "<ul>";
    foreach( $refranes as $refran){
        echo "<li> $refran</li>";
    }
    echo "</ul>";
?>

</body>
</html>