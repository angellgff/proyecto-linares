<?php
namespace Controllers;

class ProfesorController {
    private $profesorModel;

    public function __construct() {
        $this->profesorModel = new \Models\Profesor();
    }

    public function index() {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['admin', 'coordinador']);

        $profesores = $this->profesorModel->getAll();
        $data = [
            'title' => 'Gestión de Profesores',
            'profesores' => $profesores
        ];
        require_once __DIR__ . '/../views/profesor/index.php';
    }

    public function horarios($id = null) {
        \Middleware\AuthMiddleware::isAuthenticated();
        
        if (!$id) {
            $id = $_SESSION['profesor_id'] ?? null;
        }

        if (!$id) {
            header('Location: /dashboard');
            exit;
        }

        $horarios = $this->profesorModel->getHorarios($id);
        $profesor = $this->profesorModel->getById($id);
        
        $data = [
            'title' => 'Horarios del Profesor',
            'horarios' => $horarios,
            'profesor' => $profesor
        ];
        require_once __DIR__ . '/../views/profesor/horarios.php';
    }
} 