<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Crear Profesor</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/coordinador/profesores/create" class="row g-3">
            <!-- Datos personales -->
            <div class="col-md-6">
                <label for="cedula" class="form-label">Cédula *</label>
                <input type="text" class="form-control" id="cedula" name="cedula" required>
            </div>
            <div class="col-md-6">
                <label for="primer_nombre" class="form-label">Primer Nombre *</label>
                <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" required>
            </div>
            <div class="col-md-6">
                <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre">
            </div>
            <div class="col-md-6">
                <label for="primer_apellido" class="form-label">Primer Apellido *</label>
                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" required>
            </div>
            <div class="col-md-6">
                <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido">
            </div>
            <div class="col-md-6">
                <label for="sexo" class="form-label">Sexo</label>
                <select class="form-select" id="sexo" name="sexo">
                    <option value="">Seleccione...</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="numero_telefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" id="numero_telefono" name="numero_telefono">
            </div>
            <div class="col-md-6">
                <label for="correo" class="form-label">Correo *</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>

            <!-- Datos de usuario -->
            <div class="col-md-6">
                <label for="usuario" class="form-label">Usuario *</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Contraseña *</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Crear Profesor</button>
                <a href="/coordinador/profesores" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/app.php';
?> 