<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?> - Sistema Académico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        .feature-icon {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
        .card {
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .sidebar {
            min-height: 100vh;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .nav-link {
            color: #333;
            padding: 0.8rem 1rem;
        }
        .nav-link:hover {
            background-color: #f8f9fa;
        }
        .nav-link.active {
            background-color: #e9ecef;
        }
        .main-content {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h5>Sistema Académico</h5>
                        <p class="text-muted">Panel de Coordinador</p>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="/coordinador/dashboard">
                                <i class='bx bxs-dashboard'></i> Dashboard
                            </a>
                        </li>
                        
                        <!-- Gestión Académica -->
                        <li class="nav-item">
                            <a class="nav-link" href="/coordinador/horarios">
                                <i class='bx bx-time-five'></i> Horarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/coordinador/trayectos">
                                <i class='bx bx-map-alt'></i> Trayectos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/coordinador/materias">
                                <i class='bx bx-book'></i> Materias
                            </a>
                        </li>

                        <!-- Gestión de Personas -->
                        <li class="nav-item">
                            <a class="nav-link" href="/coordinador/profesores">
                                <i class='bx bx-user-voice'></i> Profesores
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/coordinador/estudiantes">
                                <i class='bx bx-user-pin'></i> Estudiantes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/coordinador/users">
                                <i class='bx bx-user'></i> Usuarios
                            </a>
                        </li>

                        <!-- Gestión de Infraestructura -->
                        <li class="nav-item">
                            <a class="nav-link" href="/coordinador/aulas">
                                <i class='bx bx-building'></i> Aulas
                            </a>
                        </li>

                        <li class="nav-item mt-4">
                            <a class="nav-link text-danger" href="/logout">
                                <i class='bx bx-log-out'></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Bienvenido, <?php echo htmlspecialchars($data['user']['name']); ?></h1>
                </div>

                <div class="row g-4 py-4">
                    <!-- Gestión de Horarios -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class='bx bx-time-five feature-icon'></i>
                                <h5 class="card-title">Horarios</h5>
                                <p class="card-text">Gestiona los horarios de clases y asignaciones.</p>
                                <a href="/coordinador/horarios" class="btn btn-primary">Gestionar Horarios</a>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Profesores -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class='bx bx-user-voice feature-icon'></i>
                                <h5 class="card-title">Profesores</h5>
                                <p class="card-text">Administra la información de los profesores.</p>
                                <a href="/coordinador/profesores" class="btn btn-primary">Gestionar Profesores</a>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Estudiantes -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class='bx bx-user-pin feature-icon'></i>
                                <h5 class="card-title">Estudiantes</h5>
                                <p class="card-text">Administra la información de los estudiantes.</p>
                                <a href="/coordinador/estudiantes" class="btn btn-primary">Gestionar Estudiantes</a>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Trayectos -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class='bx bx-map-alt feature-icon'></i>
                                <h5 class="card-title">Trayectos</h5>
                                <p class="card-text">Gestiona los trayectos académicos.</p>
                                <a href="/coordinador/trayectos" class="btn btn-primary">Gestionar Trayectos</a>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Aulas -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class='bx bx-building feature-icon'></i>
                                <h5 class="card-title">Aulas</h5>
                                <p class="card-text">Administra las aulas y sus tipos.</p>
                                <a href="/coordinador/aulas" class="btn btn-primary">Gestionar Aulas</a>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Materias -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class='bx bx-book feature-icon'></i>
                                <h5 class="card-title">Materias</h5>
                                <p class="card-text">Gestiona las materias del plan de estudio.</p>
                                <a href="/coordinador/materias" class="btn btn-primary">Gestionar Materias</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 