<?php
session_start();
require 'db.php';

// Solo permitir administradores
if (!isset($_SESSION['user']) || $_SESSION['rol'] !== 'ad') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $actividad_id = (int)$_GET['id'];
    
    $conn = conectar();
    try {
        $conn->begin_transaction();
        $stmt = $conn->prepare("SELECT * FROM actividades WHERE id = ?");
        $stmt->bind_param("i", $actividad_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $actividad = $result->fetch_assoc();
        $u = $_SESSION['user'] . "$actividad['titulo'] ? 'selected' : ''" ;
        // Eliminar registros relacionados primero
        $stmt = $conn->prepare("DELETE FROM acompanante WHERE actividad_id = ?");
        $stmt->bind_param("i", $actividad_id);
        $stmt->execute();
        
        // Eliminar la actividad principal
        $stmt = $conn->prepare("DELETE FROM actividades WHERE id = ?");
        $stmt->bind_param("i", $actividad_id);
        $stmt->execute();
        
         //Recibimos el dato que hemos enviado en el formulario


        $u = $u . "\r\n";
 

 

        $file="eliminados.txt"; //el fichero en cuestión donde vamos a guardar los datos.

        $fd = fopen($file, "a") or die("No se puede abrir el fichero"); //abrimos el fichero/flujo, solo en modo escritura (esto lo hemos indicado con una a)

        $x=fputs($fd,$u); //Escribimos el fichero

        fclose($fd); //cerramos el flujo.


        if ($stmt->affected_rows === 0) {
            throw new Exception("Actividad no encontrada");
        }
        
        $conn->commit();
        $_SESSION['success'] = "Actividad eliminada correctamente";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error al eliminar: " . $e->getMessage();
    } finally {
        $conn->close();
    }
}

header("Location: index.php");
exit;
?>
