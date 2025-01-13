<?php 
    $text = file_get_contents("https://www.eltiempo.es/sevilla.html");
    $dia = (explode('<span class="text-roboto-condensed">', $text));
    print_r($dia[5]);
?>