<?php
namespace Controllers;

class ProfesorController {
    private $profesorModel;
    private $userModel;
    private $personaModel;
    private $db;

    public function __construct() {
        $this->profesorModel = new \Models\Profesor();
        $this->userModel = new \Models\User();
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

        $profesores = $this->profesorModel->getAll();
        $data = array_merge($this->getBaseData(), [
            'title' => 'Gestión de Profesores',
            'profesores' => $profesores
        ]);
        
        require_once __DIR__ . '/../views/coordinador/profesores/index.php';
    }

    public function create() {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        $data = array_merge($this->getBaseData(), [
            'title' => 'Crear Profesor'
        ]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $personaData = [
                    'primer_nombre' => $_POST['primer_nombre'],
                    'segundo_nombre' => $_POST['segundo_nombre'] ?? null,
                    'primer_apellido' => $_POST['primer_apellido'],
                    'segundo_apellido' => $_POST['segundo_apellido'] ?? null,
                    'cedula' => $_POST['cedula'],
                    'sexo' => $_POST['sexo'] ?? null,
                    'numero_telefono' => $_POST['numero_telefono'] ?? null,
                    'correo' => $_POST['correo']
                ];

                $userData = [
                    'usuario' => $_POST['usuario'],
                    'password' => $_POST['password'],
                    'rol' => 'profesor'
                ];

                $userId = $this->userModel->createUserWithPerson($personaData, $userData);
                if ($userId) {
                    $this->profesorModel->create($userId);
                    if (ob_get_length()) ob_end_clean();
                    header('Location: /coordinador/profesores');
                    exit;
                }
            } catch (\Exception $e) {
                $error = "Error al crear el profesor: " . $e->getMessage();
            }
        }

        require_once __DIR__ . '/../views/coordinador/profesores/create.php';
    }

    public function edit($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        try {
            $profesor = $this->profesorModel->getById($id);
            if (!$profesor) {
                header('Location: /coordinador/profesores');
                exit;
            }

            $data = array_merge($this->getBaseData(), [
                'title' => 'Editar Profesor',
                'profesor' => $profesor
            ]);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $personaData = [
                    'primer_nombre' => $_POST['primer_nombre'],
                    'segundo_nombre' => $_POST['segundo_nombre'] ?? null,
                    'primer_apellido' => $_POST['primer_apellido'],
                    'segundo_apellido' => $_POST['segundo_apellido'] ?? null,
                    'cedula' => $_POST['cedula'],
                    'sexo' => $_POST['sexo'] ?? null,
                    'numero_telefono' => $_POST['numero_telefono'] ?? null,
                    'correo' => $_POST['correo']
                ];

                $userData = [
                    'usuario' => $_POST['usuario']
                ];

                if (!empty($_POST['password'])) {
                    $userData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }

                $this->db->beginTransaction();
                
                try {
                    // Debug para verificar los datos antes de la actualización
                    error_log("Actualizando persona ID: " . $profesor['fk_persona']);
                    error_log("Datos persona: " . print_r($personaData, true));
                    
                    $personaUpdated = $this->personaModel->update($profesor['fk_persona'], $personaData);
                    
                    error_log("Actualizando usuario ID: " . $profesor['fk_usuario']);
                    error_log("Datos usuario: " . print_r($userData, true));
                    
                    $userUpdated = $this->userModel->update($profesor['fk_usuario'], $userData);

                    if (!$personaUpdated || !$userUpdated) {
                        throw new \Exception("Error al actualizar los datos");
                    }

                    $this->db->commit();
                    
                    if (ob_get_length()) ob_end_clean();
                    header('Location: /coordinador/profesores?success=1');
                    exit;
                } catch (\Exception $e) {
                    $this->db->rollBack();
                    throw $e;
                }
            }
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            $error = "Error al actualizar el profesor: " . $e->getMessage();
            $data['error'] = $error;
            error_log("Error en edición de profesor: " . $e->getMessage());
        }

        require_once __DIR__ . '/../views/coordinador/profesores/edit.php';
    }

    public function delete($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->db->beginTransaction();
            
            try {
                // Verificar si el profesor existe y obtener sus datos
                $profesor = $this->profesorModel->getById($id);
                if (!$profesor) {
                    throw new \Exception("Profesor no encontrado");
                }

                // Verificar si el profesor tiene horarios asignados
                $horarios = $this->profesorModel->getHorarios($id);
                if (!empty($horarios)) {
                    throw new \Exception("No se puede eliminar el profesor porque tiene horarios asignados");
                }

                // Primero eliminar el registro de profesor
                $stmt = $this->db->prepare("DELETE FROM profesor WHERE id = ?");
                if (!$stmt->execute([$id])) {
                    throw new \Exception("Error al eliminar el profesor");
                }

                // Luego eliminar usuario y persona asociada
                if (!$this->userModel->delete($profesor['fk_usuario'])) {
                    throw new \Exception("Error al eliminar el usuario asociado");
                }

                $this->db->commit();
                
                if (ob_get_length()) ob_end_clean();
                header('Location: /coordinador/profesores?success=1');
                exit;

            } catch (\Exception $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                $error = "Error al eliminar el profesor: " . $e->getMessage();
                error_log($error);
                header('Location: /coordinador/profesores?error=' . urlencode($error));
                exit;
            }
        } else {
            header('Location: /coordinador/profesores');
            exit;
        }
    }

    public function horarios($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador', 'profesor']);

        $profesor = $this->profesorModel->getById($id);
        if (!$profesor) {
            header('Location: /coordinador/profesores');
            exit;
        }

        $horarios = $this->profesorModel->getHorarios($id);
        
        $data = array_merge($this->getBaseData(), [
            'title' => 'Horario del Profesor',
            'profesor' => $profesor,
            'horarios' => $horarios
        ]);

        require_once __DIR__ . '/../views/coordinador/profesores/horarios.php';
    }
} 