<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Trayecto</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/coordinador/trayectos/<?php echo htmlspecialchars($data['trayecto']['id']); ?>/edit" class="row g-3">
            <div class="col-md-6">
                <label for="codigo" class="form-label">Código *</label>
                <input type="text" class="form-control" id="codigo" name="codigo" 
                       value="<?php echo htmlspecialchars($data['trayecto']['codigo']); ?>" required>
            </div>

            <div class="col-md-6">
                <label for="periodo" class="form-label">Periodo *</label>
                <input type="text" class="form-control" id="periodo" name="periodo" 
                       value="<?php echo htmlspecialchars($data['trayecto']['periodo']); ?>" required>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Actualizar Trayecto</button>
                <a href="/coordinador/trayectos" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/app.php';
?> 