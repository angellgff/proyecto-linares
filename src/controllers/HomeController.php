<?php
namespace Controllers;

class HomeController {
    public function landing() {
        // Asegurarnos que la sesión esté iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        require_once __DIR__ . '/../views/landing.php';
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $data = [
            'title' => 'Dashboard',
            'user' => [
                'name' => $_SESSION['full_name'],
                'role' => $_SESSION['role']
            ]
        ];
        require_once __DIR__ . '/../views/layouts/main.php';
    }
} 