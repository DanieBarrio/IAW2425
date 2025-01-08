


<?php 

session_start();

$_SESSION["nombre"] = "Pepito Conejo";
print "<p>Se ha guardado su nombre.</p>\n";
print "<p>El nombre es $_SESSION[nombre]</p>";
session_unset();
if (isset($_SESSION["nombre"])) {
    print "<p>Su nombre es $_SESSION[nombre].</p>\n";
} else {
    print "<p>No sé su nombre.</p>\n";
}


?>