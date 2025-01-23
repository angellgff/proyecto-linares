<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Profesores</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/coordinador/profesores/create" class="btn btn-primary">
            <i class='bx bx-plus'></i> Nuevo Profesor
        </a>
    </div>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_GET['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Cédula</th>
                <th>Sexo</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['profesores'] as $profesor): ?>
                <tr>
                    <td><?php echo htmlspecialchars($profesor['id']); ?></td>
                    <td><?php echo htmlspecialchars($profesor['primer_nombre'] . ' ' . $profesor['segundo_nombre'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($profesor['primer_apellido'] . ' ' . $profesor['segundo_apellido'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($profesor['cedula']); ?></td>
                    <td><?php echo htmlspecialchars($profesor['sexo'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($profesor['numero_telefono'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($profesor['correo']); ?></td>
                    <td>
                        <a href="/coordinador/profesores/<?php echo $profesor['id']; ?>/horarios" 
                           class="btn btn-sm btn-info" title="Ver Horarios">
                            <i class='bx bx-time'></i>
                        </a>
                        <a href="/coordinador/profesores/<?php echo $profesor['id']; ?>/edit" 
                           class="btn btn-sm btn-primary" title="Editar">
                            <i class='bx bx-edit-alt'></i>
                        </a>
                        <button type="button" 
                                class="btn btn-sm btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal<?php echo $profesor['id']; ?>"
                                title="Eliminar">
                            <i class='bx bx-trash'></i>
                        </button>
                    </td>
                </tr>

                <!-- Modal de confirmación de eliminación -->
                <div class="modal fade" id="deleteModal<?php echo $profesor['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                ¿Está seguro que desea eliminar al profesor 
                                "<?php echo htmlspecialchars($profesor['primer_nombre'] . ' ' . $profesor['primer_apellido']); ?>"?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <form action="/coordinador/profesores/delete/<?php echo $profesor['id']; ?>" method="POST">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/app.php';
?> 