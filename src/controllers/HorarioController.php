<?php
namespace Controllers;

class HorarioController {
    private $horarioModel;
    private $materiaModel;
    private $aulaModel;
    private $profesorModel;
    private $trayectoModel;
    private $db;

    public function __construct() {
        $this->horarioModel = new \Models\BloqueHorario();
        $this->materiaModel = new \Models\Materia();
        $this->aulaModel = new \Models\Aula();
        $this->profesorModel = new \Models\Profesor();
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

        $horarios = $this->horarioModel->getAll();
        $data = array_merge($this->getBaseData(), [
            'title' => 'Gestión de Horarios',
            'horarios' => $horarios
        ]);
        
        require_once __DIR__ . '/../views/coordinador/horarios/index.php';
    }

    public function create() {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        $materias = $this->materiaModel->getAll();
        $aulas = $this->aulaModel->getAll();
        $profesores = $this->profesorModel->getAll();
        $trayectos = $this->trayectoModel->getAll();

        $data = array_merge($this->getBaseData(), [
            'title' => 'Crear Horario',
            'materias' => $materias,
            'aulas' => $aulas,
            'profesores' => $profesores,
            'trayectos' => $trayectos
        ]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar campos requeridos
                if (empty($_POST['dia']) || empty($_POST['hora']) || 
                    empty($_POST['materia_id']) || empty($_POST['aula_id']) || 
                    empty($_POST['profesor_id']) || empty($_POST['trayecto_id'])) {
                    throw new \Exception("Todos los campos son obligatorios");
                }

                if ($this->horarioModel->create(
                    $_POST['dia'],
                    $_POST['hora'],
                    $_POST['materia_id'],
                    $_POST['aula_id'],
                    $_POST['profesor_id'],
                    $_POST['trayecto_id']
                )) {
                    header('Location: /coordinador/horarios?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al crear el horario");
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        require_once __DIR__ . '/../views/coordinador/horarios/create.php';
    }

    public function edit($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        try {
            $horario = $this->horarioModel->getById($id);
            if (!$horario) {
                header('Location: /coordinador/horarios?error=' . urlencode('Horario no encontrado'));
                exit;
            }

            $materias = $this->materiaModel->getAll();
            $aulas = $this->aulaModel->getAll();
            $profesores = $this->profesorModel->getAll();
            $trayectos = $this->trayectoModel->getAll();

            $data = array_merge($this->getBaseData(), [
                'title' => 'Editar Horario',
                'horario' => $horario,
                'materias' => $materias,
                'aulas' => $aulas,
                'profesores' => $profesores,
                'trayectos' => $trayectos
            ]);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (empty($_POST['dia']) || empty($_POST['hora']) || 
                    empty($_POST['materia_id']) || empty($_POST['aula_id']) || 
                    empty($_POST['profesor_id']) || empty($_POST['trayecto_id'])) {
                    throw new \Exception("Todos los campos son obligatorios");
                }

                if ($this->horarioModel->update(
                    $id,
                    $_POST['dia'],
                    $_POST['hora'],
                    $_POST['materia_id'],
                    $_POST['aula_id'],
                    $_POST['profesor_id'],
                    $_POST['trayecto_id']
                )) {
                    header('Location: /coordinador/horarios?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al actualizar el horario");
                }
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        require_once __DIR__ . '/../views/coordinador/horarios/edit.php';
    }

    public function delete($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if ($this->horarioModel->delete($id)) {
                    header('Location: /coordinador/horarios?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al eliminar el horario");
                }
            } catch (\Exception $e) {
                header('Location: /coordinador/horarios?error=' . urlencode($e->getMessage()));
                exit;
            }
        } else {
            header('Location: /coordinador/horarios');
            exit;
        }
    }

    public function getAulasDisponibles() {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['dia']) && isset($_GET['hora'])) {
            $aulas = $this->horarioModel->getAulasDisponibles($_GET['dia'], $_GET['hora']);
            header('Content-Type: application/json');
            echo json_encode($aulas);
            exit;
        }
    }

    public function getProfesoresDisponibles() {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['dia']) && isset($_GET['hora'])) {
            $profesores = $this->horarioModel->getProfesoresDisponibles($_GET['dia'], $_GET['hora']);
            header('Content-Type: application/json');
            echo json_encode($profesores);
            exit;
        }
    }
} 