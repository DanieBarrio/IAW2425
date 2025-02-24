<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    die("ID de actividad no válido");
}

$conn = conectar();
$actividad_id = (int)$_GET['id'];

try {
    $conn->begin_transaction();

    // 1. Eliminar de acompanante
    $stmt_acomp = $conn->prepare("DELETE FROM acompanante WHERE actividad_id = ?");
    $stmt_acomp->bind_param("i", $actividad_id);
    
    if (!$stmt_acomp->execute()) {
        throw new Exception("Error eliminando acompañantes: " . $stmt_acomp->error);
    }

    // 2. Eliminar actividad principal
    $stmt_actividad = $conn->prepare("DELETE FROM actividades WHERE id = ?");
    $stmt_actividad->bind_param("i", $actividad_id);
    
    if (!$stmt_actividad->execute()) {
        throw new Exception("Error eliminando actividad: " . $stmt_actividad->error);
    }

    if ($stmt_actividad->affected_rows === 0) {
        throw new Exception("No existe la actividad con ID: $actividad_id");
    }

    $conn->commit();
    $_SESSION['success'] = "Actividad eliminada correctamente";

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Error al eliminar: " . $e->getMessage();
    
} finally {
    $conn->close();
    header("Location: index.php");
    exit;
}
?>