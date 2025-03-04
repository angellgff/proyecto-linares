<?php
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Horario del Profesor: <?php echo htmlspecialchars($data['profesor']['primer_nombre'] . ' ' . $data['profesor']['primer_apellido']); ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="/coordinador/profesores" class="btn btn-sm btn-secondary">
                <i class='bx bx-arrow-back'></i> Volver
            </a>
        </div>
    </div>
</div>

<?php
// Definir estructura de horarios con las horas numéricas
$horarios = [];
for ($hora = 7; $hora <= 18; $hora++) {
    $horaFormato = sprintf("%02d:00 - %02d:45", $hora, $hora);
    $horarios[$hora] = [
        'formato' => $horaFormato,
        'bloques' => []
    ];
}

// Organizar los bloques en la estructura
foreach ($data['horarios'] as $horario) {
    $hora = (int)$horario['hora'];
    if (isset($horarios[$hora])) {
        $horarios[$hora]['bloques'][$horario['dia']] = [
            'materia' => $horario['materia_codigo'],
            'aula' => $horario['aula_codigo'],
            'trayecto' => $horario['trayecto_codigo']
        ];
    }
}
?>

<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead>
            <tr class="text-center">
                <th>Hora</th>
                <th>Lunes</th>
                <th>Martes</th>
                <th>Miércoles</th>
                <th>Jueves</th>
                <th>Viernes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($horarios as $hora => $info): ?>
                <tr>
                    <td class="text-center"><?php echo $info['formato']; ?></td>
                    <?php for ($dia = 1; $dia <= 5; $dia++): ?>
                        <td class="text-center">
                            <?php if (isset($info['bloques'][$dia])): ?>
                                <div class="p-2">
                                    <strong><?php echo htmlspecialchars($info['bloques'][$dia]['materia']); ?></strong><br>
                                    Aula: <?php echo htmlspecialchars($info['bloques'][$dia]['aula']); ?><br>
                                    Trayecto: <?php echo htmlspecialchars($info['bloques'][$dia]['trayecto']); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/app.php';
?> 