<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Crear Horario</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/coordinador/horarios/create" class="row g-3">
            <div class="col-md-6">
                <label for="dia" class="form-label">Día *</label>
                <select class="form-select" id="dia" name="dia" required>
                    <option value="">Seleccione...</option>
                    <option value="1">Lunes</option>
                    <option value="2">Martes</option>
                    <option value="3">Miércoles</option>
                    <option value="4">Jueves</option>
                    <option value="5">Viernes</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="hora" class="form-label">Hora *</label>
                <select class="form-select" id="hora" name="hora" required>
                    <option value="">Seleccione...</option>
                    <option value="7">7:00 AM</option>
                    <option value="8">8:00 AM</option>
                    <option value="9">9:00 AM</option>
                    <option value="10">10:00 AM</option>
                    <option value="11">11:00 AM</option>
                    <option value="12">12:00 PM</option>
                    <option value="13">1:00 PM</option>
                    <option value="14">2:00 PM</option>
                    <option value="15">3:00 PM</option>
                    <option value="16">4:00 PM</option>
                    <option value="17">5:00 PM</option>
                    <option value="18">6:00 PM</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="materia_id" class="form-label">Materia *</label>
                <select class="form-select" id="materia_id" name="materia_id" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($data['materias'] as $materia): ?>
                        <option value="<?php echo htmlspecialchars($materia['id']); ?>">
                            <?php echo htmlspecialchars($materia['codigo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="aula_id" class="form-label">Aula *</label>
                <select class="form-select" id="aula_id" name="aula_id" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($data['aulas'] as $aula): ?>
                        <option value="<?php echo htmlspecialchars($aula['id']); ?>">
                            <?php echo htmlspecialchars($aula['codigo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="profesor_id" class="form-label">Profesor *</label>
                <select class="form-select" id="profesor_id" name="profesor_id" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($data['profesores'] as $profesor): ?>
                        <option value="<?php echo htmlspecialchars($profesor['id']); ?>">
                            <?php echo htmlspecialchars($profesor['nombre_completo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="trayecto_id" class="form-label">Trayecto *</label>
                <select class="form-select" id="trayecto_id" name="trayecto_id" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($data['trayectos'] as $trayecto): ?>
                        <option value="<?php echo htmlspecialchars($trayecto['id']); ?>">
                            <?php echo htmlspecialchars($trayecto['codigo'] . ' - ' . $trayecto['periodo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Crear Horario</button>
                <a href="/coordinador/horarios" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/app.php';
?> 