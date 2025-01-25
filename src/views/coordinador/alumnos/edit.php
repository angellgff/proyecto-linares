<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Alumno</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/coordinador/alumnos/<?php echo htmlspecialchars($data['alumno']['id']); ?>/edit" class="row g-3">
            <div class="col-md-6">
                <label for="cedula" class="form-label">Cédula *</label>
                <input type="text" class="form-control" id="cedula" name="cedula" 
                       value="<?php echo htmlspecialchars($data['alumno']['cedula']); ?>" required>
            </div>

            <div class="col-md-6">
                <label for="primer_nombre" class="form-label">Primer Nombre *</label>
                <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" 
                       value="<?php echo htmlspecialchars($data['alumno']['primer_nombre']); ?>" required>
            </div>

            <div class="col-md-6">
                <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre" 
                       value="<?php echo htmlspecialchars($data['alumno']['segundo_nombre']); ?>">
            </div>

            <div class="col-md-6">
                <label for="primer_apellido" class="form-label">Primer Apellido *</label>
                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" 
                       value="<?php echo htmlspecialchars($data['alumno']['primer_apellido']); ?>" required>
            </div>

            <div class="col-md-6">
                <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" 
                       value="<?php echo htmlspecialchars($data['alumno']['segundo_apellido']); ?>">
            </div>

            <div class="col-md-6">
                <label for="telefono" class="form-label">Teléfono *</label>
                <input type="tel" class="form-control" id="telefono" name="telefono" 
                       value="<?php echo htmlspecialchars($data['alumno']['numero_telefono']); ?>" required>
            </div>

            <div class="col-md-6">
                <label for="correo" class="form-label">Correo Electrónico *</label>
                <input type="email" class="form-control" id="correo" name="correo" 
                       value="<?php echo htmlspecialchars($data['alumno']['correo']); ?>" required>
            </div>

            <div class="col-md-6">
                <label for="trayecto_id" class="form-label">Trayecto *</label>
                <select class="form-select" id="trayecto_id" name="trayecto_id" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($data['trayectos'] as $trayecto): ?>
                        <option value="<?php echo htmlspecialchars($trayecto['id']); ?>"
                                <?php echo ($trayecto['id'] == $data['alumno']['fk_trayecto']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($trayecto['codigo'] . ' - ' . $trayecto['periodo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Actualizar Alumno</button>
                <a href="/coordinador/alumnos" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/app.php';
?> 