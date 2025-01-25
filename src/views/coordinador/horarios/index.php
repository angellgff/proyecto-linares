<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Horarios</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="/coordinador/horarios/create" class="btn btn-sm btn-primary">
                + Crear Horario
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
                <th>Día</th>
                <th>Hora</th>
                <th>Materia</th>
                <th>Profesor</th>
                <th>Aula</th>
                <th>Trayecto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data['horarios'])): ?>
                <tr>
                    <td colspan="7" class="text-center">No hay horarios registrados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($data['horarios'] as $horario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($horario['nombre_dia']); ?></td>
                        <td><?php echo htmlspecialchars($horario['hora']); ?></td>
                        <td><?php echo htmlspecialchars($horario['materia_codigo']); ?></td>
                        <td><?php echo htmlspecialchars($horario['profesor_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($horario['aula_codigo']); ?></td>
                        <td><?php echo htmlspecialchars($horario['trayecto_codigo']); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="/coordinador/horarios/<?php echo $horario['id']; ?>/edit" 
                                   class="btn btn-primary"
                                   title="Editar">
                                    <i class='bx bx-pencil'></i>
                                </a>
                                <form action="/coordinador/horarios/delete/<?php echo $horario['id']; ?>" 
                                      method="POST" 
                                      onsubmit="return confirm('¿Está seguro de eliminar este horario?');"
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
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/app.php';
?> 