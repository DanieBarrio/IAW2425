<?php
session_start();
require 'db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$conn = conectar();

// Mostrar totales de actividades
$total_propuestas = $conn->query("SELECT COUNT(*) FROM actividades")->fetch_row()[0];
$total_aprobadas = $conn->query("SELECT COUNT(*) FROM actividades WHERE aprobada = 1")->fetch_row()[0];
$total_pendientes = $conn->query("SELECT COUNT(*) FROM actividades WHERE aprobada = 0")->fetch_row()[0];

// Paginación
$por_pagina = 5;
$página = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$inicio = ($página - 1) * $por_pagina;

$query = "SELECT 
    a.id,
    a.titulo,
    t.nombre AS tipo,
    d.nombre AS departamento,
    p.nombre AS profesor,
    DATE_FORMAT(a.fecha_inicio, '%d/%m/%Y') AS fecha,
    a.coste,
    a.total_alumnos,
    a.aprobada
FROM actividades a
JOIN tipo t ON a.tipo_id = t.id
JOIN departamento d ON a.departamento_id = d.id
JOIN profesores p ON a.profesor_id = p.id
ORDER BY a.fecha_inicio DESC LIMIT $inicio, $por_pagina";
$actividades = $conn->query($query);

$total_actividades = $conn->query("SELECT COUNT(*) FROM actividades")->fetch_row()[0];
$total_páginas = ceil($total_actividades / $por_pagina);

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Actividades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body.dark-mode {
            background-color: #343a40 !important;
            color: white !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Gestión Actividades</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">
                        <?= htmlspecialchars($_SESSION['user']) ?>
                        (<?= isset($_SESSION['rol']) && $_SESSION['rol'] === 'ad' ? 'Admin' : 'Usuario' ?>)
                    </span>
                </li>
                <li class="nav-item">
                    <a href="add_activity.php" class="btn btn-success btn-sm mx-2">➕ Nueva</a>
                </li>
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'ad'): ?>
                <li class="nav-item">
                    <a href="gestion_usuarios.php" class="btn btn-info btn-sm">👥 Usuarios</a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-danger btn-sm">🔒 Salir</a>
                </li>
                <li class="nav-item">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="darkModeSwitch" onchange="toggleDarkMode()">
                        <label class="form-check-label" for="darkModeSwitch">Modo Oscuro</label>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="alert alert-info">
        Bienvenido <?= htmlspecialchars($_SESSION['user']) ?>, se conectó por última vez el <?= htmlspecialchars($_SESSION['last_login'] ?? 'Nunca') ?>
    </div>
 
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Título</th>
                <th>Tipo</th>
                <th>Departamento</th>
                <th>Responsable</th>
                <th>Fecha</th>
                <th>Coste</th>
                <th>Alumnos</th>
                <th>Aprobada</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($act = $actividades->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($act['titulo']) ?></td>
                    <td><?= htmlspecialchars($act['tipo']) ?></td>
                    <td><?= htmlspecialchars($act['departamento']) ?></td>
                    <td><?= htmlspecialchars($act['profesor']) ?></td>
                    <td><?= htmlspecialchars($act['fecha']) ?></td>
                    <td><?= number_format($act['coste'], 2) ?>€</td>
                    <td><?= htmlspecialchars($act['total_alumnos']) ?></td>
                    <td>
                        <?php if ($_SESSION['rol'] === 'ad'): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="actividad_id" value="<?= $act['id'] ?>">
                                <input type="hidden" name="aprobada" value="<?= $act['aprobada'] ? 0 : 1 ?>">
                                <button type="submit" class="btn btn-<?= $act['aprobada'] ? 'danger' : 'success' ?> btn-sm">
                                    <?= $act['aprobada'] ? '❌ Desaprobar' : '✅ Aprobar' ?>
                                </button>
                            </form>
                        <?php else: ?>
                            <span class="badge bg-<?= $act['aprobada'] ? 'success' : 'danger' ?>">
                                <?= $act['aprobada'] ? 'Aprobada' : 'No Aprobada' ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($_SESSION['rol'] === 'ad'): ?>
                            <div class="btn-group">
                                <a href="edit_activity.php?id=<?= $act['id'] ?>" class="btn btn-outline-warning btn-sm">✏️ Editar</a>
                                <a href="delete_activity.php?id=<?= $act['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar permanentemente esta actividad?')">🗑️ Eliminar</a>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">Acción no permitida</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav aria-label="Paginación">
           <div class="alert alert-info">
        Total de Actividades: Propuestas: <?= $total_propuestas ?> | Aprobadas: <?= $total_aprobadas ?> | Pendientes: <?= $total_pendientes ?>
    </div>

        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_páginas; $i++): ?>
                <li class="page-item <?= $i == $página ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
<footer>

    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="darkModeSwitch" onchange="toggleDarkMode()">
                        <label class="form-check-label" for="darkModeSwitch">Modo Oscuro</label>
                    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
    }

    window.onload = function () {
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            document.body.classList.add('dark-mode');
        }
    };
</script>
</body>
</html>
