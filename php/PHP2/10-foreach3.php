<?php
// Array de tweets
$tweets = [
    "Este es el primer tweet!",
    "Aquí va el segundo tweet con más contenido.",
    "¡Un tercer tweet para la colección!",
];

// Función para generar el código HTML de un tweet
function mostrar_tweet($tweet) {
    return "
    <div class='tweet'>
        <div class='tweet-content'>$tweet</div>
    </div>
    ";
}

// HTML inicial
$html = "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Tweets</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f8fa;
            color: #14171a;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 500px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .tweet {
            border-bottom: 1px solid #e1e8ed;
            padding: 10px 0;
        }
        .tweet-content {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class='container'>
";

// Recorrer el array de tweets y añadirlos al HTML
foreach ($tweets as $tweet) {
    $html .= mostrar_tweet($tweet);
}

// HTML final
$html .= "
    </div>
</body>
</html>
";

// Mostrar el HTML
echo $html;
?>