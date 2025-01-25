<?php
namespace Controllers;

class AulaController {
    private $aulaModel;
    private $tipoAulaModel;
    private $db;

    public function __construct() {
        $this->aulaModel = new \Models\Aula();
        $this->tipoAulaModel = new \Models\TipoAula();
        $this->db = \Database::getInstance();
    }

    private function getBaseData() {
        return [
            'user' => [
                'name' => $_SESSION['full_name'],
                'role' => $_SESSION['role']
            ]
        ];
    }

    public function index() {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        $aulas = $this->aulaModel->getAll();
        $data = array_merge($this->getBaseData(), [
            'title' => 'Gestión de Aulas',
            'aulas' => $aulas
        ]);
        
        require_once __DIR__ . '/../views/coordinador/aulas/index.php';
    }

    public function create() {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        $tipos_aula = $this->tipoAulaModel->getAll();
        $data = array_merge($this->getBaseData(), [
            'title' => 'Crear Aula',
            'tipos_aula' => $tipos_aula
        ]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (empty($_POST['codigo']) || empty($_POST['tipo_aula_id'])) {
                    throw new \Exception("Todos los campos son obligatorios");
                }

                if ($this->aulaModel->create($_POST['codigo'], $_POST['tipo_aula_id'])) {
                    header('Location: /coordinador/aulas?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al crear el aula");
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
                require_once __DIR__ . '/../views/coordinador/aulas/create.php';
                return;
            }
        }

        require_once __DIR__ . '/../views/coordinador/aulas/create.php';
    }

    public function edit($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        try {
            $aula = $this->aulaModel->getById($id);
            if (!$aula) {
                header('Location: /coordinador/aulas');
                exit;
            }

            $tipos_aula = $this->tipoAulaModel->getAll();
            $data = array_merge($this->getBaseData(), [
                'title' => 'Editar Aula',
                'aula' => $aula,
                'tipos_aula' => $tipos_aula
            ]);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (empty($_POST['codigo']) || empty($_POST['tipo_aula_id'])) {
                    throw new \Exception("Todos los campos son obligatorios");
                }

                if ($this->aulaModel->update($id, $_POST['codigo'], $_POST['tipo_aula_id'])) {
                    header('Location: /coordinador/aulas?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al actualizar el aula");
                }
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        require_once __DIR__ . '/../views/coordinador/aulas/edit.php';
    }

    public function delete($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Verificar si hay horarios asociados
                $horarios = $this->aulaModel->getHorarios($id);
                if (!empty($horarios)) {
                    throw new \Exception("No se puede eliminar el aula porque tiene horarios asociados");
                }

                if ($this->aulaModel->delete($id)) {
                    header('Location: /coordinador/aulas?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al eliminar el aula");
                }
            } catch (\Exception $e) {
                header('Location: /coordinador/aulas?error=' . urlencode($e->getMessage()));
                exit;
            }
        } else {
            header('Location: /coordinador/aulas');
            exit;
        }
    }
} 