<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triangulos</title>
</head>
<body>
<h1>Sistema de TRIANGULOS</h1>
<form action="triangulo.php" method="POST">

    <input type="number" name="numero" placeholder="Numero Mayor que 0" id="numero">
    <input type="submit" values="Calcular">
</form>
<?php
    
if(isset($_POST["numero"])   ){
    $numero = htmlspecialchars($_POST["numero"]); 
   
if($numero > 0 && is_numeric($numero)){
$texto = "<p>Triangulo</p><br>";
for($num1=$numero ; $num1 >=1; $num1--){
    
    for($num2=$num1; $num2 >= 1; $num2--){ 
        $texto .= "*";
    }
    $texto .= "<br>";
 }
 echo "$texto";
}
else{
    echo "No te saltes el javaScript";
}
}

?>

<script>


</script>
</body>
</html>

