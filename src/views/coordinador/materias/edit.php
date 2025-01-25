<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Materia</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/coordinador/materias/<?php echo htmlspecialchars($data['materia']['id']); ?>/edit" class="row g-3">
            <div class="col-md-6">
                <label for="codigo" class="form-label">Código *</label>
                <input type="text" class="form-control" id="codigo" name="codigo" 
                       value="<?php echo htmlspecialchars($data['materia']['codigo']); ?>" required>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Actualizar Materia</button>
                <a href="/coordinador/materias" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/app.php';
?> 