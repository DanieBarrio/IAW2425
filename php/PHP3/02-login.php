<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
</head>
<body>

<h1>Sistema de autenticación</h1>
<form action="02-login.php" method="POST">

    <input type="text" name="usuario" placeholder="Usuario">
    <input type="password" name="pass">
    <input type="submit" values="Login">
</form>

<?php

    if(isset($_POST["usuario"]) && isset($_POST["pass"])){
        $usuario = htmlspecialchars($_POST["usuario"]);
        $password = htmlspecialchars($_POST["pass"]);
        if ($usuario=="admin" && $password=="HACKERS")
            echo "<p>Bienvenido amo</p>";
        else
            echo "<p>Error en usuario y/o contraseña</p>";
    }

?>

</body>
</html>