<?php
namespace Controllers;

class UserController {
    private $userModel;
    private $rolModel;
    private $personaModel;

    public function __construct() {
        $this->userModel = new \Models\User();
        $this->rolModel = new \Models\Rol();
        $this->personaModel = new \Models\Persona();
    }

    public function index() {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['coordinador']);

        $usuarios = $this->userModel->all();
        $data = [
            'title' => 'Gestión de Usuarios',
            'usuarios' => $usuarios
        ];
        require_once __DIR__ . '/../views/admin/users/index.php';
    }

    public function create() {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['admin']);

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
                'usuario' => $_POST['usuario'],
                'password' => $_POST['password'],
                'rol' => $_POST['rol']
            ];

            try {
                $this->userModel->createUserWithPerson($personaData, $userData);
                header('Location: /coordinador/users');
                exit;
            } catch (\Exception $e) {
                $error = "Error al crear el usuario: " . $e->getMessage();
            }
        }

        $roles = $this->rolModel->getAll();
        $data = [
            'title' => 'Crear Usuario',
            'roles' => $roles
        ];
        require_once __DIR__ . '/../views/admin/users/create.php';
    }

    public function edit($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['admin']);

        $usuario = $this->userModel->getByIdWithDetails($id);
        if (!$usuario) {
            header('Location: /admin/users');
            exit;
        }

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
                'usuario' => $_POST['usuario'],
                'rol' => $_POST['rol']
            ];

            if (!empty($_POST['password'])) {
                $userData['password'] = $_POST['password'];
            }

            try {
                $this->userModel->updateUserWithPerson($id, $personaData, $userData);
                header('Location: /coordinador/users');
                exit;
            } catch (\Exception $e) {
                $error = "Error al actualizar el usuario: " . $e->getMessage();
            }
        }

        $roles = $this->rolModel->getAll();
        $data = [
            'title' => 'Editar Usuario',
            'usuario' => $usuario,
            'roles' => $roles
        ];
        require_once __DIR__ . '/../views/admin/users/edit.php';
    }

    public function delete($id) {
        \Middleware\AuthMiddleware::isAuthenticated();
        \Middleware\AuthMiddleware::hasRole(['admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->userModel->delete($id);
                header('Location: /coordinador/users');
                exit;
            } catch (\Exception $e) {
                $error = "Error al eliminar el usuario: " . $e->getMessage();
                header('Location: /coordinador/users?error=' . urlencode($error));
                exit;
            }
        }
    }
} 