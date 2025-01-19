<?php
namespace Controllers;

class CoordinadorDashboardController {
    private $coordinadorModel;

    public function __construct() {
        $this->coordinadorModel = new \Models\Coordinador();
    }

    public function index() {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        $data = [
            'title' => 'Dashboard Coordinador',
            'user' => [
                'name' => $_SESSION['full_name'],
                'role' => $_SESSION['role']
            ]
        ];
        require_once __DIR__ . '/../views/coordinador/dashboard.php';
    }
} 