<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fecha</title>

    
</head>
<body>
<?php
setlocale(LC_ALL,"es_ES");
$string = "20180417";
$day= array("domingo","lunes","martes","miercoles","jueves","viernes","sábado");
$month= array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");

echo $day[date('w')]." ".date('d')." de ".$month[date('n')-1]. " del ".date('Y') ;

?></body>
</html>