<?php
// Conexión a la base de datos
$servername = "";
$username = "";
$password = "";
$database="";
$enlace = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$enlace) {
    die("Conexión fallida: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar campos vacíos
    if (empty($_POST['nombre']) || empty($_POST['apellidos']) || empty($_POST['telefono']) || empty($_POST['password'])) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Saneamiento de las entradas
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $apellidos = htmlspecialchars(trim($_POST['apellidos']));
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Verificar si el usuario ya existe
    $query = "SELECT id FROM usuarios WHERE numero='$telefono'";
    $resultado = mysqli_query($enlace, $query);

    if (mysqli_num_rows($resultado) > 0) {
        echo "<p>Error: El usuario ya está registrado.</p>";
    }
    else{
        // Cifrar la contraseña
        $password_encrypted = $password; // Sin cifrar (GRAN ERROR)
        // $password_encrypted = crypt($password, '$6$rounds=5000$' . uniqid(mt_rand(), true) . '$');

        // Insertar datos en la base de datos
        $query = "INSERT INTO usuarios (nombre, apellido, numero, contrasena) VALUES ('$nombre', '$apellidos', '$telefono', '$password_encrypted')";

        if (mysqli_query($enlace, $query)) {
            // Enviar correo electrónico de confirmación     
                echo "Usuario registrado correctamente. Hola $nombre,\n\nGracias por registrarte. Estos son tus datos:\nNombre: $nombre\nApellidos: $apellidos\nEmail: $telefono\n\nSaludos.";
          
        } else {
            echo "Error al registrar el usuario: " . mysqli_error($enlace);
        }
    }
}

mysqli_close($enlace);
?>

<!-- Formulario de registro -->
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
</head>
<body>
    <form method="POST" action="registro.php">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre"><br>
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos"><br>
        <label for="telefono">Telefono:</label>
        <input type="number" id="telefono" name="telefono"><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password"><br>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>