<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Horario del Profesor: <?php echo htmlspecialchars($data['profesor']['primer_nombre'] . ' ' . $data['profesor']['primer_apellido']); ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/coordinador/profesores" class="btn btn-secondary">
            <i class='bx bx-arrow-back'></i> Volver
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Hora</th>
                <th>Lunes</th>
                <th>Martes</th>
                <th>Miércoles</th>
                <th>Jueves</th>
                <th>Viernes</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $horas = [
                '07:00 - 07:45', '07:45 - 08:30', '08:30 - 09:15', '09:15 - 10:00',
                '10:00 - 10:45', '10:45 - 11:30', '11:30 - 12:15', '12:15 - 13:00',
                '13:00 - 13:45', '13:45 - 14:30', '14:30 - 15:15', '15:15 - 16:00'
            ];
            
            foreach ($horas as $hora): ?>
                <tr>
                    <td class="table-light"><?php echo $hora; ?></td>
                    <?php foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia): ?>
                        <td>
                            <?php
                            $bloque = null;
                            foreach ($data['horarios'] as $horario) {
                                if ($horario['hora'] === $hora && $horario['dia'] === $dia) {
                                    $bloque = $horario;
                                    break;
                                }
                            }
                            if ($bloque): ?>
                                <div class="small">
                                    <strong>Materia:</strong> <?php echo htmlspecialchars($bloque['materia_codigo']); ?><br>
                                    <strong>Aula:</strong> <?php echo htmlspecialchars($bloque['aula_codigo']); ?><br>
                                    <strong>Trayecto:</strong> <?php echo htmlspecialchars($bloque['trayecto_codigo']); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/app.php';
?> 