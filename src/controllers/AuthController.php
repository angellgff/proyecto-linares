<?php
namespace Controllers;

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new \Models\User();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($user = $this->userModel->authenticate($username, $password)) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['usuario'];
                $_SESSION['role'] = $user['rol'];
                $_SESSION['full_name'] = $user['primer_nombre'] . ' ' . $user['primer_apellido'];
                
                header('Location: /dashboard');
                exit;
            } else {
                $error = "Credenciales inválidas";
                require_once __DIR__ . '/../views/auth/login.php';
            }
        } else {
            require_once __DIR__ . '/../views/auth/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'primer_nombre' => $_POST['primer_nombre'] ?? '',
                'segundo_nombre' => $_POST['segundo_nombre'] ?? '',
                'primer_apellido' => $_POST['primer_apellido'] ?? '',
                'segundo_apellido' => $_POST['segundo_apellido'] ?? '',
                'cedula' => $_POST['cedula'] ?? '',
                'correo' => $_POST['correo'] ?? '',
                'usuario' => $_POST['usuario'] ?? '',
                'password' => $_POST['password'] ?? '',
                'numero_telefono' => $_POST['numero_telefono'] ?? '',
                'sexo' => $_POST['sexo'] ?? ''
            ];
            
            if ($this->userModel->createUser($userData)) {
                header('Location: /login');
                exit;
            } else {
                $error = "Error al registrar usuario";
                require_once __DIR__ . '/../views/auth/register.php';
            }
        } else {
            require_once __DIR__ . '/../views/auth/register.php';
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }
} 