<?php
class Database {
    private static $instance = null;
    
    public static function getInstance() {
        if (self::$instance === null) {
            $host = 'mysql';
            $db   = 'mvc_app';
            $user = 'app_user';
            $pass = 'app_password';
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            
            try {
                self::$instance = new PDO($dsn, $user, $pass);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo "Error de conexión: " . $e->getMessage();
            }
        }
        return self::$instance;
    }
} 