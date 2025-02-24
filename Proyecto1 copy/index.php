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
<html>
<head>
    <title>Actividades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        .badge {
            font-size: 0.9em;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Bienvenido, <?= htmlspecialchars($_SESSION['user']) ?></h2>
            <small class="text-muted"><?= date('d/m/Y H:i') ?></small>
        </div>
        <div>
            <a href="add_activity.php" class="btn btn-success btn-sm">➕ Nueva</a>
            <a href="logout.php" class="btn btn-danger btn-sm">🔒 Salir</a>
        </div>
    </div>

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
                                <div class="btn-group">
                                    <a href="edit_activity.php?id=<?= $act['id'] ?>" 
                                       class="btn btn-sm btn-outline-warning">✏️ Editar</a>
                                    <a href="delete_activity.php?id=<?= $act['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('¿Eliminar permanentemente esta actividad?')">🗑️ Eliminar</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>