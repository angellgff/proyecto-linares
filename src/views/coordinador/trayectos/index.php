<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Trayectos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="/coordinador/trayectos/create" class="btn btn-sm btn-primary">
                + Crear Trayecto
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
                <th>ID</th>
                <th>Código</th>
                <th>Periodo</th>
                <th>Alumnos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['trayectos'] as $trayecto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($trayecto['id']); ?></td>
                    <td><?php echo htmlspecialchars($trayecto['codigo']); ?></td>
                    <td><?php echo htmlspecialchars($trayecto['periodo']); ?></td>
                    <td>
                        <span class="badge bg-info">
                            <?php echo htmlspecialchars($trayecto['total_alumnos']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="/coordinador/trayectos/<?php echo $trayecto['id']; ?>/alumnos" 
                               class="btn btn-info"
                               title="Ver Alumnos">
                                <i class='bx bx-user'></i>
                            </a>
                            <a href="/coordinador/trayectos/<?php echo $trayecto['id']; ?>/edit" 
                               class="btn btn-primary"
                               title="Editar">
                                <i class='bx bx-pencil'></i>
                            </a>
                            <?php if ($trayecto['total_alumnos'] == 0): ?>
                                <form action="/coordinador/trayectos/delete/<?php echo $trayecto['id']; ?>" 
                                      method="POST" 
                                      onsubmit="return confirm('¿Está seguro de eliminar este trayecto?');"
                                      style="display: inline;">
                                    <button type="submit" class="btn btn-danger"
                                            title="Eliminar">
                                        <i class='bx bx-trash-alt'></i>
                                    </button>
                                </form>
                            <?php endif; ?>
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