<?php
namespace Controllers;

class AlumnoController {
    private $alumnoModel;
    private $trayectoModel;
    private $personaModel;
    private $db;

    public function __construct() {
        $this->alumnoModel = new \Models\Alumno();
        $this->trayectoModel = new \Models\Trayecto();
        $this->personaModel = new \Models\Persona();
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

        $alumnos = $this->alumnoModel->getAll();
        $data = array_merge($this->getBaseData(), [
            'title' => 'Gestión de Alumnos',
            'alumnos' => $alumnos
        ]);
        
        require_once __DIR__ . '/../views/coordinador/alumnos/index.php';
    }

    public function create() {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        $trayectos = $this->trayectoModel->getAll();
        $data = array_merge($this->getBaseData(), [
            'title' => 'Registrar Alumno',
            'trayectos' => $trayectos
        ]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->db->beginTransaction();

                // Crear persona primero
                $personaId = $this->personaModel->create(
                    $_POST['cedula'],
                    $_POST['primer_nombre'],
                    $_POST['segundo_nombre'],
                    $_POST['primer_apellido'],
                    $_POST['segundo_apellido'],
                    $_POST['telefono'],
                    $_POST['correo']
                );

                // Luego crear el alumno
                if ($this->alumnoModel->create($personaId, $_POST['trayecto_id'])) {
                    $this->db->commit();
                    header('Location: /coordinador/alumnos?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al crear el alumno");
                }
            } catch (\Exception $e) {
                $this->db->rollBack();
                $error = $e->getMessage();
                require_once __DIR__ . '/../views/coordinador/alumnos/create.php';
                return;
            }
        }

        require_once __DIR__ . '/../views/coordinador/alumnos/create.php';
    }

    public function edit($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        try {
            $alumno = $this->alumnoModel->getById($id);
            if (!$alumno) {
                header('Location: /coordinador/alumnos?error=' . urlencode('Alumno no encontrado'));
                exit;
            }

            $trayectos = $this->trayectoModel->getAll();
            $data = array_merge($this->getBaseData(), [
                'title' => 'Editar Alumno',
                'alumno' => $alumno,
                'trayectos' => $trayectos
            ]);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->db->beginTransaction();
                try {
                    // Validar campos requeridos
                    if (empty($_POST['cedula']) || empty($_POST['primer_nombre']) || 
                        empty($_POST['primer_apellido']) || empty($_POST['telefono']) || 
                        empty($_POST['correo']) || empty($_POST['trayecto_id'])) {
                        throw new \Exception("Todos los campos marcados con * son obligatorios");
                    }

                    // Actualizar datos de persona
                    $this->personaModel->update(
                        $alumno['fk_persona'],
                        $_POST['cedula'],
                        $_POST['primer_nombre'],
                        $_POST['segundo_nombre'],
                        $_POST['primer_apellido'],
                        $_POST['segundo_apellido'],
                        $_POST['telefono'],
                        $_POST['correo']
                    );

                    // Actualizar trayecto del alumno
                    if ($this->alumnoModel->update($id, $_POST['trayecto_id'])) {
                        $this->db->commit();
                        header('Location: /coordinador/alumnos?success=1');
                        exit;
                    } else {
                        throw new \Exception("Error al actualizar el alumno");
                    }
                } catch (\Exception $e) {
                    $this->db->rollBack();
                    throw $e;
                }
            }

            require_once __DIR__ . '/../views/coordinador/alumnos/edit.php';
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $data = array_merge($this->getBaseData(), [
                'title' => 'Editar Alumno',
                'alumno' => $alumno ?? null,
                'trayectos' => $trayectos ?? []
            ]);
            require_once __DIR__ . '/../views/coordinador/alumnos/edit.php';
        }
    }

    public function delete($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if ($this->alumnoModel->delete($id)) {
                    header('Location: /coordinador/alumnos?success=1');
                    exit;
                } else {
                    throw new \Exception("Error al eliminar el alumno");
                }
            } catch (\Exception $e) {
                header('Location: /coordinador/alumnos?error=' . urlencode($e->getMessage()));
                exit;
            }
        } else {
            header('Location: /coordinador/alumnos');
            exit;
        }
    }

    public function horarios($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador', 'profesor', 'estudiante']);

        try {
            $alumno = $this->alumnoModel->getById($id);
            if (!$alumno) {
                header('Location: /coordinador/alumnos');
                exit;
            }

            $horarios = $this->alumnoModel->getHorarios($id);
            $data = array_merge($this->getBaseData(), [
                'title' => 'Horarios del Alumno',
                'alumno' => $alumno,
                'horarios' => $horarios
            ]);

            require_once __DIR__ . '/../views/coordinador/alumnos/horarios.php';
        } catch (\Exception $e) {
            header('Location: /coordinador/alumnos?error=' . urlencode($e->getMessage()));
            exit;
        }
    }
} 