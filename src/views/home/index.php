<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Bienvenido, <?php echo htmlspecialchars($data['user']['name']); ?></h1>
    </div>
</div>

<div class="row">
    <?php if (in_array($data['user']['role'], ['admin', 'coordinador'])): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gestión de Horarios</h5>
                    <p class="card-text">Administra los horarios de clases y asignaciones.</p>
                    <a href="/horarios" class="btn btn-primary">Ir a Horarios</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($data['user']['role'] === 'admin'): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gestión de Usuarios</h5>
                    <p class="card-text">Administra los usuarios del sistema.</p>
                    <a href="/users" class="btn btn-primary">Ir a Usuarios</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gestión de Materias</h5>
                    <p class="card-text">Administra las materias y sus asignaciones.</p>
                    <a href="/materias" class="btn btn-primary">Ir a Materias</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($data['user']['role'] === 'profesor'): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mis Horarios</h5>
                    <p class="card-text">Consulta tus horarios de clase.</p>
                    <a href="/mis-horarios" class="btn btn-primary">Ver Horarios</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($data['user']['role'] === 'alumno'): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mi Horario</h5>
                    <p class="card-text">Consulta tu horario de clases.</p>
                    <a href="/mi-horario" class="btn btn-primary">Ver Horario</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div> 