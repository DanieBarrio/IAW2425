<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reserva</title>
</head>
<body>
<h1>Reserva </h1>
<form action="reserva.php" method="POST">

    <input type="text" name="nombre" placeholder="Nombre">
    <input type="text" name="Apellidos" placeholder="Apellidos">
    <input type="email" name="Email" placeholder="Email">
    <input type="text" name="DNI" placeholder="DNI">
    <input type="text" name="fechae" placeholder="Dia entrada">
    <input type="number" name="fechas" placeholder="numero de dias a quedar">
    <select class="form-control" name="reserva" id="reserva">
            
            <option value="1">simple(30€)</option>
            <option value="2">doble(50€)</option>
            <option value="3">triple(80€)</option>
            <option value="4">suit(100€)</option>
            
    </select>
    <input type="submit" values="Login">
</form>

<?php

    if( isset($_POST["nombre"]) && isset($_POST["Apellidos"]) && isset($_POST["Email"]) && isset($_POST["DNI"]) &&
    isset($_POST["fechae"]) && isset($_POST["fechas"]) && isset($_POST["reserva"]) ){
        $nombre = htmlspecialchars($_POST["nombre"]);
        $apellido = htmlspecialchars($_POST["Apellidos"]);
        $email = htmlspecialchars($_POST["Email"]);
        $dni  = htmlspecialchars($_POST["DNI"]);
        $dia   = htmlspecialchars($_POST["fechae"]);
        $dias  = htmlspecialchars($_POST["fechas"]);
        $reserva = htmlspecialchars($_POST["reserva"]);
        $imagenes = array(
            'hab0.png',
            'hab1.png',
            'hab2.png',
            'hab3.png'
        );
        if($reserva == 1){
            $reserva2 = "simple(30€)" ;
        $pago = 30 * $dias;}
        if($reserva == 2){
            $reserva2 = "doble(50€)" ;
        $pago = 50 * $dias;}
        if($reserva == 3){
           $reserva2 = "triple(80€)" ;
           $pago = 80 * $dias;}
        if($reserva == 4){
            $reserva2 = "suit(100€)" ;
        $pago = 100 * $dias;}
        if($dias < 0 || $dia > 31 ){
            echo "<p>Porfavor revise los datos y asegurese que es correcto</p>";
        }
        else
        {
        echo "<p>Nombre: $nombre <br> Apellido: $apellido <br>Correo electronico: $email <br>
        Dni: $dni <br> Dia de entrada: $dia <br> Dias a hospedarse: $dias<br>
        Tipo de reserva: $reserva2 <br> Total a pagar: $pago €</p>";
        echo "<img src='".$imagenes[$reserva -1]."'"; 
        }
        
    }

?>
</body>
</html>