<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Horario</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/coordinador/horarios/<?php echo htmlspecialchars($data['horario']['id']); ?>/edit" class="row g-3">
            <div class="col-md-6">
                <label for="dia" class="form-label">Día *</label>
                <select class="form-select" id="dia" name="dia" required>
                    <option value="">Seleccione...</option>
                    <option value="1" <?php echo ($data['horario']['dia'] == 1) ? 'selected' : ''; ?>>Lunes</option>
                    <option value="2" <?php echo ($data['horario']['dia'] == 2) ? 'selected' : ''; ?>>Martes</option>
                    <option value="3" <?php echo ($data['horario']['dia'] == 3) ? 'selected' : ''; ?>>Miércoles</option>
                    <option value="4" <?php echo ($data['horario']['dia'] == 4) ? 'selected' : ''; ?>>Jueves</option>
                    <option value="5" <?php echo ($data['horario']['dia'] == 5) ? 'selected' : ''; ?>>Viernes</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="hora" class="form-label">Hora *</label>
                <select class="form-select" id="hora" name="hora" required>
                    <option value="">Seleccione...</option>
                    <?php
                    $horas = [
                        7 => '7:00 AM', 8 => '8:00 AM', 9 => '9:00 AM', 10 => '10:00 AM',
                        11 => '11:00 AM', 12 => '12:00 PM', 13 => '1:00 PM', 14 => '2:00 PM',
                        15 => '3:00 PM', 16 => '4:00 PM', 17 => '5:00 PM', 18 => '6:00 PM'
                    ];
                    foreach ($horas as $valor => $texto): ?>
                        <option value="<?php echo $valor; ?>" <?php echo ($data['horario']['hora'] == $valor) ? 'selected' : ''; ?>>
                            <?php echo $texto; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="materia_id" class="form-label">Materia *</label>
                <select class="form-select" id="materia_id" name="materia_id" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($data['materias'] as $materia): ?>
                        <option value="<?php echo htmlspecialchars($materia['id']); ?>"
                                <?php echo ($materia['id'] == $data['horario']['fk_materia']) ? 'selected' : ''; ?>>
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
                        <option value="<?php echo htmlspecialchars($aula['id']); ?>"
                                <?php echo ($aula['id'] == $data['horario']['fk_aula']) ? 'selected' : ''; ?>>
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
                        <option value="<?php echo htmlspecialchars($profesor['id']); ?>"
                                <?php echo ($profesor['id'] == $data['horario']['fk_profesor']) ? 'selected' : ''; ?>>
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
                        <option value="<?php echo htmlspecialchars($trayecto['id']); ?>"
                                <?php echo ($trayecto['id'] == $data['horario']['fk_trayecto']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($trayecto['codigo'] . ' - ' . $trayecto['periodo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Actualizar Horario</button>
                <a href="/coordinador/horarios" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/app.php';
?> 