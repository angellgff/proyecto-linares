<?php
ob_start(); // Iniciar el buffer de salida al principio
require_once __DIR__ . '/../config/database.php';

// Autoloader básico
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/../';
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Router básico
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

// Rutas públicas
$publicRoutes = ['', 'login', 'register'];

switch ($uri) {
    case '':
        $controller = new Controllers\HomeController();
        $controller->landing();
        break;
    
    case 'login':
        $controller = new Controllers\AuthController();
        $controller->login();
        break;
    
    case 'register':
        $controller = new Controllers\AuthController();
        $controller->register();
        break;
    
    case 'logout':
        $controller = new Controllers\AuthController();
        $controller->logout();
        break;
        
    case 'dashboard':
        \Middleware\AuthMiddleware::isAuthenticated();
        $controller = new Controllers\HomeController();
        $controller->index();
        break;
    
    case 'coordinador/dashboard':
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\CoordinadorDashboardController();
        $controller->index();
        break;

    case 'coordinador/horarios':
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\HorarioController();
        $controller->index();
        break;

    case 'coordinador/profesores':
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\ProfesorController();
        $controller->index();
        break;

    case 'coordinador/users':
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\UserController();
        $controller->index();
        break;

    case 'coordinador/users/create':
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\UserController();
        $controller->create();
        break;

    case (preg_match('/^coordinador\/users\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\UserController();
        $controller->edit($matches[1]);
        break;

    case (preg_match('/^coordinador\/users\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\UserController();
        $controller->delete($matches[1]);
        break;

    case 'coordinador/estudiantes':
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\EstudianteController();
        $controller->index();
        break;

    case 'coordinador/materias':
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\MateriaController();
        $controller->index();
        break;

    case 'coordinador/trayectos':
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\TrayectoController();
        $controller->index();
        break;

    case (preg_match('/^coordinador\/aulas$/', $uri) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\AulaController();
        $controller->index();
        break;

    case (preg_match('/^coordinador\/aulas\/create$/', $uri) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\AulaController();
        $controller->create();
        break;

    case (preg_match('/^coordinador\/aulas\/(\d+)\/edit$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\AulaController();
        $controller->edit($matches[1]);
        break;

    case (preg_match('/^coordinador\/aulas\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\AulaController();
        $controller->delete($matches[1]);
        break;

    case 'coordinador/profesores/create':
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\ProfesorController();
        $controller->create();
        break;

    case (preg_match('/^coordinador\/profesores\/(\d+)\/edit$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\ProfesorController();
        $controller->edit($matches[1]);
        break;

    case (preg_match('/^coordinador\/profesores\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\ProfesorController();
        $controller->delete($matches[1]);
        break;

    case (preg_match('/^coordinador\/profesores\/(\d+)\/horarios$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\ProfesorController();
        $controller->horarios($matches[1]);
        break;

    case (preg_match('/^coordinador\/materias$/', $uri) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\MateriaController();
        $controller->index();
        break;

    case (preg_match('/^coordinador\/materias\/create$/', $uri) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\MateriaController();
        $controller->create();
        break;

    case (preg_match('/^coordinador\/materias\/(\d+)\/edit$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\MateriaController();
        $controller->edit($matches[1]);
        break;

    case (preg_match('/^coordinador\/materias\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\MateriaController();
        $controller->delete($matches[1]);
        break;

    case (preg_match('/^coordinador\/trayectos\/(\d+)\/alumnos$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\TrayectoController();
        $controller->alumnos($matches[1]);
        break;

    case (preg_match('/^coordinador\/alumnos$/', $uri) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\AlumnoController();
        $controller->index();
        break;

    case (preg_match('/^coordinador\/alumnos\/create$/', $uri) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\AlumnoController();
        $controller->create();
        break;

    case (preg_match('/^coordinador\/alumnos\/(\d+)\/edit$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\AlumnoController();
        $controller->edit($matches[1]);
        break;

    case (preg_match('/^coordinador\/alumnos\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\AlumnoController();
        $controller->delete($matches[1]);
        break;

    case (preg_match('/^coordinador\/alumnos\/(\d+)\/horarios$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\AlumnoController();
        $controller->horarios($matches[1]);
        break;

    case (preg_match('/^coordinador\/horarios$/', $uri) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\HorarioController();
        $controller->index();
        break;

    case (preg_match('/^coordinador\/horarios\/create$/', $uri) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\HorarioController();
        $controller->create();
        break;

    case (preg_match('/^coordinador\/horarios\/(\d+)\/edit$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\HorarioController();
        $controller->edit($matches[1]);
        break;

    case (preg_match('/^coordinador\/horarios\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\HorarioController();
        $controller->delete($matches[1]);
        break;

    case (preg_match('/^coordinador\/horarios\/aulas-disponibles$/', $uri) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\HorarioController();
        $controller->getAulasDisponibles();
        break;

    case (preg_match('/^coordinador\/horarios\/profesores-disponibles$/', $uri) ? true : false):
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);
        $controller = new Controllers\HorarioController();
        $controller->getProfesoresDisponibles();
        break;

    default:
        if (!in_array($uri, $publicRoutes)) {
            \Middleware\AuthMiddleware::isAuthenticated();
        }
        http_response_code(404);
        echo "Página no encontrada";
} 