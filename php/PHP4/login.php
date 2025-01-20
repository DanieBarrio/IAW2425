<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-3 m-0 border-0 bd-example m-0 border-0">
    <form id="loginForm">
        <div class="mb-3">
            <label for="usuario" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="usuario" >
            
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password">
        </div>
  
        <button type="submit" class="btn btn-primary">Submit</button>
        <script>
            document.getElementById('loginForm').addEventListener('submit', function(e) { e.preventDefault();
        </script>
        <?php 
            include 'coneccion-2.php';
            $conn = conectar();
            session_start();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $usuario = $_POST['usuario'];
                $password = $_POST['password'];

                if (!empty($usuario) && !empty($password)) {
                    $conn = conectar();
                    $contra_hash = crypt($password, '$5$rounds=Kjl9NAPk23');
                    $sql = "SELECT * FROM usuarios WHERE nombre = '$usuario' AND contrasena = '$contra_hash' ";
                
                    if ($result->num_rows > 0) {
                        setcookie("usuario", $usuario, time() + (86400 * 30), "/"); // 86400 = 1 día
                        echo "Login exitoso. Cookie establecida.";
                    } else {
                        echo "Usuario o contraseña incorrectos.";
                    }

                    $conn->close();
                } else {
                    echo "Por favor, rellena todos los campos.";
                }
            }
        ?>
    </form>
</body>
</html>