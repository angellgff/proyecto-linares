<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Aulas</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="/coordinador/aulas/create" class="btn btn-sm btn-primary">
                + Crear Aula
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
                <th>Tipo</th>
                <th>Horarios</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['aulas'] as $aula): ?>
                <tr>
                    <td><?php echo htmlspecialchars($aula['id']); ?></td>
                    <td><?php echo htmlspecialchars($aula['codigo']); ?></td>
                    <td><?php echo htmlspecialchars($aula['tipo_aula']); ?></td>
                    <td>
                        <span class="badge bg-info">
                            <?php echo htmlspecialchars($aula['total_horarios']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="/coordinador/aulas/<?php echo $aula['id']; ?>/horarios" 
                               class="btn btn-info"
                               title="Ver Horarios">
                                <i class='bx bx-time'></i>
                            </a>
                            <a href="/coordinador/aulas/<?php echo $aula['id']; ?>/edit" 
                               class="btn btn-primary"
                               title="Editar">
                                <i class='bx bx-pencil'></i>
                            </a>
                            <?php if ($aula['total_horarios'] == 0): ?>
                                <form action="/coordinador/aulas/delete/<?php echo $aula['id']; ?>" 
                                      method="POST" 
                                      onsubmit="return confirm('¿Está seguro de eliminar esta aula?');"
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