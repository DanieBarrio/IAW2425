<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imagen Aleatoria</title>
</head>
<body>
<?php
#Me ha dado pereza meter imagenes 
$imagenes = array(
    '1.png',
    '2.png',
    'tres.png',
    '4.png',
    'siete.png'
);
echo "<img src='".$imagenes[rand(0, 4)]."'"; 
?>
    
    
</body>
</html>
