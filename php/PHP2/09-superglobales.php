<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superglobales</title>
</head>
<body>
    <?php 
   // Obtener la dirección IP del usuario
   $ip_usuario = $_SERVER['REMOTE_ADDR'];
   
   // Obtener el navegador del usuario
   $navegador_usuario = $_SERVER['HTTP_USER_AGENT'];
   
   // Obtener la página de referencia (referrer)
   $pagina_referida = $_SERVER['HTTP_REFERER'];
   
   echo "Dirección IP del usuario: " . $ip_usuario . "<br>";
   echo "Navegador del usuario: " . $navegador_usuario . "<br>";
   echo "Página previa que refirió al usuario: " . $pagina_referida;
   ?>
   
</body>
</html>