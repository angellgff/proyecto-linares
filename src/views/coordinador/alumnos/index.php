<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Alumnos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="/coordinador/alumnos/create" class="btn btn-sm btn-primary">
                + Registrar Alumno
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

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Trayecto</th>
                <th>Periodo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['alumnos'] as $alumno): ?>
                <tr>
                    <td><?php echo htmlspecialchars($alumno['cedula']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['primer_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['primer_apellido']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['trayecto_codigo']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['trayecto_periodo']); ?></td>
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
                            <form action="/coordinador/alumnos/delete/<?php echo $alumno['id']; ?>" 
                                  method="POST" 
                                  onsubmit="return confirm('¿Está seguro de eliminar este alumno?');"
                                  style="display: inline;">
                                <button type="submit" class="btn btn-danger"
                                        title="Eliminar">
                                    <i class='bx bx-trash-alt'></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/app.php';
?> 