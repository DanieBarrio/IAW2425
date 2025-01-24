<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$conn = conectar();
$error = '';
$actividad = [];
$acompanantes = [];

// Obtener datos de la actividad
if (isset($_GET['id'])) {
    try {
        // Datos principales
        $stmt = $conn->prepare("SELECT * FROM actividades WHERE id = ?");
        $stmt->bind_param("i", $_GET['id']);
        $stmt->execute();
        $actividad = $stmt->get_result()->fetch_assoc();
        
        if (!$actividad) {
            throw new Exception("Actividad no encontrada");
        }

        // Acompañantes actuales
        $stmt_acomp = $conn->prepare("SELECT profesor_id FROM acompanante WHERE actividad_id = ?");
        $stmt_acomp->bind_param("i", $_GET['id']);
        $stmt_acomp->execute();
        $result_acomp = $stmt_acomp->get_result();
        
        while ($row = $result_acomp->fetch_assoc()) {
            $acompanantes[] = $row['profesor_id'];
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id'])) {
    try {
        $conn->begin_transaction();

        // Validación
        $required = [
            'titulo', 'tipo_id', 'departamento_id', 'profesor_id',
            'fecha_inicio', 'fecha_fin', 'hora_inicio', 'hora_fin',
            'coste', 'total_alumnos', 'objetivo'
        ];
        
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("El campo $field es requerido");
            }
        }

        // Validar relación departamento-profesor
        $stmt_check = $conn->prepare("SELECT id FROM profesores WHERE id = ? AND id_departamento = ?");
        $stmt_check->bind_param("ii", $_POST['profesor_id'], $_POST['departamento_id']);
        $stmt_check->execute();
        
        if (!$stmt_check->get_result()->num_rows) {
            throw new Exception("El profesor no pertenece al departamento seleccionado");
        }

        // Actualizar actividad
        $stmt = $conn->prepare("
            UPDATE actividades SET
                titulo = ?,
                tipo_id = ?,
                departamento_id = ?,
                profesor_id = ?,
                fecha_inicio = ?,
                fecha_fin = ?,
                hora_inicio_id = ?,
                hora_fin_id = ?,
                coste = ?,
                total_alumnos = ?,
                objetivo = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "siiissiidsii",
            $_POST['titulo'],
            $_POST['tipo_id'],
            $_POST['departamento_id'],
            $_POST['profesor_id'],
            $_POST['fecha_inicio'],
            $_POST['fecha_fin'],
            $_POST['hora_inicio'],
            $_POST['hora_fin'],
            $_POST['coste'],
            $_POST['total_alumnos'],
            $_POST['objetivo'],
            $_GET['id']
        );

        if (!$stmt->execute()) {
            throw new Exception("Error actualizando actividad: " . $stmt->error);
        }

        // Actualizar acompañantes
        $conn->query("DELETE FROM acompanante WHERE actividad_id = " . $_GET['id']);
        
        if (!empty($_POST['acompanantes'])) {
            $stmt_acomp = $conn->prepare("INSERT INTO acompanante (actividad_id, profesor_id) VALUES (?, ?)");
            
            foreach ($_POST['acompanantes'] as $profesor_id) {
                $stmt_acomp->bind_param("ii", $_GET['id'], $profesor_id);
                $stmt_acomp->execute();
            }
        }

        $conn->commit();
        $_SESSION['success'] = "Actividad actualizada correctamente";
        header("Location: index.php");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        $error = $e->getMessage();
    }
}

// Obtener datos para formulario
$departamentos = $conn->query("SELECT * FROM departamento");
$profesores = $conn->query("SELECT * FROM profesores");
$tipos = $conn->query("SELECT * FROM tipo");
$horas = $conn->query("SELECT * FROM horas ORDER BY hora");
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Actividad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
    function cargarProfesores(depId) {
        fetch(`get_profesores.php?departamento_id=${depId}`)
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('profesor');
                select.innerHTML = data.options;
                select.value = <?= $actividad['profesor_id'] ?? 'null' ?>;
                select.disabled = false;
            });
    }
    
    window.onload = function() {
        <?php if (!empty($actividad['departamento_id'])): ?>
            cargarProfesores(<?= $actividad['departamento_id'] ?>);
        <?php endif; ?>
    }
    </script>
</head>
<body>
<div class="container mt-4">
    <h2>✏️ Editar Actividad</h2>
    <a href="index.php" class="btn btn-secondary mb-3">← Volver</a>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($actividad)): ?>
    <form method="POST">
        <div class="card mb-4">
            <div class="card-header">Información Básica</div>
            <div class="card-body">
                <div class="mb-3">
                    <label>Título</label>
                    <input type="text" name="titulo" class="form-control" 
                           value="<?= htmlspecialchars($actividad['titulo']) ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Tipo</label>
                        <select name="tipo_id" class="form-select" required>
                            <?php while ($tipo = $tipos->fetch_assoc()): ?>
                                <option value="<?= $tipo['id'] ?>" 
                                    <?= ($tipo['id'] == $actividad['tipo_id']) ? 'selected' : '' ?>>
                                    <?= $tipo['nombre'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Departamento</label>
                        <select name="departamento_id" id="departamento" class="form-select" required 
                                onchange="cargarProfesores(this.value)">
                            <?php $departamentos->data_seek(0); ?>
                            <?php while ($dep = $departamentos->fetch_assoc()): ?>
                                <option value="<?= $dep['id'] ?>" 
                                    <?= ($dep['id'] == $actividad['departamento_id']) ? 'selected' : '' ?>>
                                    <?= $dep['nombre'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Responsable</label>
                        <select name="profesor_id" id="profesor" class="form-select" required 
                            <?= empty($actividad['departamento_id']) ? 'disabled' : '' ?>>
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Fecha y Hora</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" 
                               class="form-control" 
                               value="<?= $actividad['fecha_inicio'] ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>Fecha Fin</label>
                        <input type="date" name="fecha_fin" 
                               class="form-control" 
                               value="<?= $actividad['fecha_fin'] ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Hora Inicio</label>
                        <select name="hora_inicio" class="form-select" required>
                            <?php $horas->data_seek(0); ?>
                            <?php while ($hora = $horas->fetch_assoc()): ?>
                                <option value="<?= $hora['id'] ?>" 
                                    <?= ($hora['id'] == $actividad['hora_inicio_id']) ? 'selected' : '' ?>>
                                    <?= date('H:i', strtotime($hora['hora'])) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>Hora Fin</label>
                        <select name="hora_fin" class="form-select" required>
                            <?php $horas->data_seek(0); ?>
                            <?php while ($hora = $horas->fetch_assoc()): ?>
                                <option value="<?= $hora['id'] ?>" 
                                    <?= ($hora['id'] == $actividad['hora_fin_id']) ? 'selected' : '' ?>>
                                    <?= date('H:i', strtotime($hora['hora'])) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Detalles</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Coste (€)</label>
                        <input type="number" step="0.01" name="coste" 
                               class="form-control" 
                               value="<?= $actividad['coste'] ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>Alumnos</label>
                        <input type="number" name="total_alumnos" 
                               class="form-control" 
                               value="<?= $actividad['total_alumnos'] ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Objetivo</label>
                    <textarea name="objetivo" class="form-control" rows="4" required><?= htmlspecialchars($actividad['objetivo']) ?></textarea>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Acompañantes</div>
            <div class="card-body">
                <div class="row">
                    <?php $profesores->data_seek(0); ?>
                    <?php while ($prof = $profesores->fetch_assoc()): ?>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="acompanantes[]" 
                                       value="<?= $prof['id'] ?>"
                                       <?= in_array($prof['id'], $acompanantes) ? 'checked' : '' ?>>
                                <label class="form-check-label">
                                    <?= $prof['nombre'] ?>
                                </label>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">💾 Guardar Cambios</button>
    </form>
    <?php else: ?>
        <div class="alert alert-warning">Actividad no encontrada</div>
    <?php endif; ?>
</div>
</body>
</html>