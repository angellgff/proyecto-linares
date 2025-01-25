<?php
// Asegurarnos que la sesión esté iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
?>

<div class="px-4 py-5 my-5 text-center">
    <h1 class="display-5 fw-bold text-body-emphasis">Sistema de Gestión Académica</h1>
    <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">
            Bienvenido al Sistema de Gestión Académica del Instituto Universitario de Tecnología.
            <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
                Continúa gestionando tus actividades académicas.
            <?php else: ?>
                Gestiona tus horarios, materias y más de manera eficiente.
            <?php endif; ?>
        </p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
                <a href="/dashboard" class="btn btn-primary btn-lg px-4 gap-3">
                    <i class='bx bxs-dashboard'></i> Ir al Dashboard
                </a>
            <?php else: ?>
                <a href="/login" class="btn btn-primary btn-lg px-4 gap-3">
                    <i class='bx bx-log-in'></i> Iniciar Sesión
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/layouts/landing.php';
?> 