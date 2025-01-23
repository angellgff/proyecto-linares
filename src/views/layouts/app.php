<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?> - Sistema Académico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            padding-top: 1rem;
            transition: all 0.3s;
        }
        
        .sidebar-header {
            padding: 1rem;
            text-align: center;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }

        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
        }

        .sidebar i {
            font-size: 1.2rem;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }

        .user-info {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 1rem;
            color: white;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h5 class="mb-0">Sistema Académico</h5>
            <small class="text-muted">Panel de Control</small>
        </div>
        
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link" href="/dashboard">
                    <i class='bx bxs-dashboard'></i> Inicio
                </a>
            </li>
            
            <?php 
            $userRole = $data['user']['role'] ?? '';
            if (in_array($userRole, ['admin', 'coordinador'])): 
            ?>
                <li class="nav-item">
                    <a class="nav-link" href="/coordinador/materias">
                        <i class='bx bx-book'></i> Materias
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/coordinador/horarios">
                        <i class='bx bx-time'></i> Horarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/coordinador/profesores">
                        <i class='bx bx-user-voice'></i> Profesores
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/coordinador/trayectos">
                        <i class='bx bx-map-alt'></i> Trayectos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/coordinador/aulas">
                        <i class='bx bx-building'></i> Aulas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/coordinador/alumnos">
                        <i class='bx bx-user-pin'></i> Alumnos
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <div class="user-info">
            <div class="dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <i class='bx bx-user-circle'></i>
                    <?php echo htmlspecialchars($data['user']['name']); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="/profile">Mi Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/logout">Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php echo $content; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Marcar el enlace activo
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html> 