<?php
namespace Controllers;

class TrayectoController {
    private $trayectoModel;
    private $db;

    public function __construct() {
        $this->trayectoModel = new \Models\Trayecto();
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

        $trayectos = $this->trayectoModel->getAll();
        $data = array_merge($this->getBaseData(), [
            'title' => 'Gestión de Trayectos',
            'trayectos' => $trayectos
        ]);
        
        require_once __DIR__ . '/../views/coordinador/trayectos/index.php';
    }

    public function create() {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        $data = array_merge($this->getBaseData(), [
            'title' => 'Crear Trayecto'
        ]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (empty($_POST['codigo']) || empty($_POST['periodo'])) {
                    throw new \Exception("Todos los campos son obligatorios");
                }

                if ($this->trayectoModel->create($_POST['codigo'], $_POST['periodo'])) {
                    header('Location: /coordinador/trayectos?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al crear el trayecto");
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
                require_once __DIR__ . '/../views/coordinador/trayectos/create.php';
                return;
            }
        }

        require_once __DIR__ . '/../views/coordinador/trayectos/create.php';
    }

    public function edit($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        try {
            $trayecto = $this->trayectoModel->getById($id);
            if (!$trayecto) {
                header('Location: /coordinador/trayectos');
                exit;
            }

            $data = array_merge($this->getBaseData(), [
                'title' => 'Editar Trayecto',
                'trayecto' => $trayecto
            ]);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (empty($_POST['codigo']) || empty($_POST['periodo'])) {
                    throw new \Exception("Todos los campos son obligatorios");
                }

                if ($this->trayectoModel->update($id, $_POST['codigo'], $_POST['periodo'])) {
                    header('Location: /coordinador/trayectos?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al actualizar el trayecto");
                }
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        require_once __DIR__ . '/../views/coordinador/trayectos/edit.php';
    }

    public function delete($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if ($this->trayectoModel->delete($id)) {
                    header('Location: /coordinador/trayectos?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al eliminar el trayecto");
                }
            } catch (\Exception $e) {
                header('Location: /coordinador/trayectos?error=' . urlencode($e->getMessage()));
                exit;
            }
        } else {
            header('Location: /coordinador/trayectos');
            exit;
        }
    }

    public function alumnos($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        try {
            $trayecto = $this->trayectoModel->getById($id);
            if (!$trayecto) {
                header('Location: /coordinador/trayectos?error=' . urlencode('Trayecto no encontrado'));
                exit;
            }

            $alumnos = $this->trayectoModel->getAlumnos($id);
            $data = array_merge($this->getBaseData(), [
                'title' => 'Alumnos del Trayecto ' . $trayecto['codigo'],
                'trayecto' => $trayecto,
                'alumnos' => $alumnos
            ]);

            require_once __DIR__ . '/../views/coordinador/trayectos/alumnos.php';
        } catch (\Exception $e) {
            header('Location: /coordinador/trayectos?error=' . urlencode($e->getMessage()));
            exit;
        }
    }
} 