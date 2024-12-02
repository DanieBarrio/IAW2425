<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>While</title>
</head>
<body>
    
<?php 
 $num1 = 1;
 $num2 = 10;
 echo "<table border='1'>";
 while($num1 <= $num2){
    echo "<tr><td> $num1 </td></tr> \n";
    $num1 +=1;
 }
 echo "</table>";
?>
</body>
</html>