<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Alumnos del Trayecto <?php echo htmlspecialchars($data['trayecto']['codigo']); ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="/coordinador/trayectos" class="btn btn-sm btn-secondary">
                <i class='bx bx-arrow-back'></i> Volver
            </a>
        </div>
    </div>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Operación realizada con éxito</div>
<?php endif; ?>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">Información del Trayecto</h5>
        <p class="card-text">
            <strong>Código:</strong> <?php echo htmlspecialchars($data['trayecto']['codigo']); ?><br>
            <strong>Periodo:</strong> <?php echo htmlspecialchars($data['trayecto']['periodo']); ?>
        </p>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data['alumnos'])): ?>
                <tr>
                    <td colspan="6" class="text-center">No hay alumnos registrados en este trayecto</td>
                </tr>
            <?php else: ?>
                <?php foreach ($data['alumnos'] as $alumno): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($alumno['cedula']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['primer_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['primer_apellido']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['numero_telefono']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['correo']); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="/coordinador/alumnos/<?php echo $alumno['id']; ?>/horarios" 
                                   class="btn btn-info"
                                   title="Ver Horarios">
                                    <i class='bx bx-time'></i>
                                </a>
                                <a href="/coordinador/alumnos/<?php echo $alumno['id']; ?>/edit" 
                                   class="btn btn-primary"
                                   title="Editar">
                                    <i class='bx bx-pencil'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/app.php';
?> 