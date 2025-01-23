<?php
namespace Controllers;

class AulaController {
    private $aulaModel;

    public function __construct() {
        $this->aulaModel = new \Models\Aula();
    }

    public function index() {
        $aulas = $this->aulaModel->getAll();
        $data = [
            'title' => 'Gestión de Aulas',
            'aulas' => $aulas
        ];
        require_once __DIR__ . '/../views/coordinador/aulas/index.php';
    }
} 