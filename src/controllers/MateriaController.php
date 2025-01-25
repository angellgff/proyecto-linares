<?php
namespace Controllers;

class MateriaController {
    private $materiaModel;
    private $db;

    public function __construct() {
        $this->materiaModel = new \Models\Materia();
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

        $materias = $this->materiaModel->getAll();
        $data = array_merge($this->getBaseData(), [
            'title' => 'Gestión de Materias',
            'materias' => $materias
        ]);
        
        require_once __DIR__ . '/../views/coordinador/materias/index.php';
    }

    public function create() {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        $data = array_merge($this->getBaseData(), [
            'title' => 'Crear Materia'
        ]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (empty($_POST['codigo'])) {
                    throw new \Exception("El código es obligatorio");
                }

                if ($this->materiaModel->create($_POST['codigo'])) {
                    header('Location: /coordinador/materias?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al crear la materia");
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
                require_once __DIR__ . '/../views/coordinador/materias/create.php';
                return;
            }
        }

        require_once __DIR__ . '/../views/coordinador/materias/create.php';
    }

    public function edit($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        try {
            $materia = $this->materiaModel->getById($id);
            if (!$materia) {
                header('Location: /coordinador/materias');
                exit;
            }

            $data = array_merge($this->getBaseData(), [
                'title' => 'Editar Materia',
                'materia' => $materia
            ]);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (empty($_POST['codigo'])) {
                    throw new \Exception("El código es obligatorio");
                }

                if ($this->materiaModel->update($id, $_POST['codigo'])) {
                    header('Location: /coordinador/materias?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al actualizar la materia");
                }
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        require_once __DIR__ . '/../views/coordinador/materias/edit.php';
    }

    public function delete($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Verificar si hay horarios asociados
                $horarios = $this->materiaModel->getHorarios($id);
                if (!empty($horarios)) {
                    throw new \Exception("No se puede eliminar la materia porque tiene horarios asociados");
                }

                if ($this->materiaModel->delete($id)) {
                    header('Location: /coordinador/materias?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al eliminar la materia");
                }
            } catch (\Exception $e) {
                header('Location: /coordinador/materias?error=' . urlencode($e->getMessage()));
                exit;
            }
        } else {
            header('Location: /coordinador/materias');
            exit;
        }
    }
} 