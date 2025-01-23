<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Profesor</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/coordinador/profesores/<?php echo htmlspecialchars($data['profesor']['id']); ?>/edit" class="row g-3">
            <div class="col-md-6">
                <label for="primer_nombre" class="form-label">Primer Nombre *</label>
                <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" 
                       value="<?php echo htmlspecialchars($data['profesor']['primer_nombre']); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre" 
                       value="<?php echo htmlspecialchars($data['profesor']['segundo_nombre'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label for="primer_apellido" class="form-label">Primer Apellido *</label>
                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" 
                       value="<?php echo htmlspecialchars($data['profesor']['primer_apellido']); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" 
                       value="<?php echo htmlspecialchars($data['profesor']['segundo_apellido'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label for="cedula" class="form-label">Cédula *</label>
                <input type="text" class="form-control" id="cedula" name="cedula" 
                       value="<?php echo htmlspecialchars($data['profesor']['cedula']); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="sexo" class="form-label">Sexo</label>
                <select class="form-select" id="sexo" name="sexo">
                    <option value="">Seleccione...</option>
                    <option value="M" <?php echo ($data['profesor']['sexo'] === 'M') ? 'selected' : ''; ?>>Masculino</option>
                    <option value="F" <?php echo ($data['profesor']['sexo'] === 'F') ? 'selected' : ''; ?>>Femenino</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="numero_telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="numero_telefono" name="numero_telefono" 
                       value="<?php echo htmlspecialchars($data['profesor']['numero_telefono'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label for="correo" class="form-label">Correo Electrónico *</label>
                <input type="email" class="form-control" id="correo" name="correo" 
                       value="<?php echo htmlspecialchars($data['profesor']['correo']); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="usuario" class="form-label">Usuario *</label>
                <input type="text" class="form-control" id="usuario" name="usuario" 
                       value="<?php echo htmlspecialchars($data['profesor']['usuario']); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Contraseña (dejar en blanco para mantener la actual)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Actualizar Profesor</button>
                <a href="/coordinador/profesores" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/app.php';
?> 