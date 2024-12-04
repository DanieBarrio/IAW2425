<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuadrado</title>
</head>
<body>
    
  
<?php
$num1 =  rand(0, 255);
$num2 =  rand(0, 255);
$num3 =  rand(0, 255);

echo "<div style='background-color: rgb(". $num1. "," .$num2. "," .$num3  .");  width: 300px;height: 300px;'> </div>";
?>
</body>
</html>