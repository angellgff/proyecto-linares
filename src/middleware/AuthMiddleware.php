<?php
namespace Middleware;

class AuthMiddleware {
    public static function isAuthenticated() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public static function hasRole($allowedRoles) {
        if (!isset($_SESSION['role'])) {
            header('Location: /login');
            exit;
        }

        $roles = is_array($allowedRoles) ? $allowedRoles : [$allowedRoles];
        
        if (!in_array($_SESSION['role'], $roles)) {
            header('Location: /dashboard');
            exit;
        }
    }
} 