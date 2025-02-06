<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$conn = conectar();
$query = "SELECT 
            a.id,
            a.titulo,
            t.nombre AS tipo,
            d.nombre AS departamento,
            p.nombre AS profesor,
            DATE_FORMAT(a.fecha_inicio, '%d/%m/%Y') AS fecha,
            a.coste,
            a.total_alumnos
          FROM actividades a
          JOIN tipo t ON a.tipo_id = t.id
          JOIN departamento d ON a.departamento_id = d.id
          JOIN profesores p ON a.profesor_id = p.id
          ORDER BY a.fecha_inicio DESC";

$actividades = $conn->query($query);
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .table-hover tbody tr:hover { background-color: rgba(13, 110, 253, 0.05); }
        .badge { font-size: 0.9em; }
        .navbar-custom { background-color: #007bff; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Gestión Actividades</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">
                        <?= htmlspecialchars($_SESSION['user']) ?> 
                        (<?= isset($_SESSION['rol']) ? ($_SESSION['rol'] === 'ad' ? 'Admin' : 'Usuario') : 'Invitado' ?>)
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
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); endif; ?>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📋 Listado de Actividades</h4>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Departamento</th>
                            <th>Responsable</th>
                            <th>Fecha</th>
                            <th>Coste</th>
                            <th>Alumnos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($act = $actividades->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($act['titulo']) ?></td>
                            <td><span class="badge bg-info"><?= $act['tipo'] ?></span></td>
                            <td><?= $act['departamento'] ?></td>
                            <td><?= $act['profesor'] ?></td>
                            <td><?= $act['fecha'] ?></td>
                            <td><?= number_format($act['coste'], 2) ?>€</td>
                            <td><?= $act['total_alumnos'] ?></td>
                            <td>
                                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'ad'): ?>
                                <div class="btn-group">
                                    <a href="edit_activity.php?id=<?= $act['id'] ?>" 
                                       class="btn btn-sm btn-outline-warning">✏️ Editar</a>
                                    <a href="delete_activity.php?id=<?= $act['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('¿Eliminar permanentemente esta actividad?')">🗑️ Eliminar</a>
                                </div>
                                <?php else: ?>
                                <span class="text-muted">Acción no permitida</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>