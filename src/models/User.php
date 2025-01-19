<?php
namespace Models;

class User {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function authenticate($username, $password) {
        $stmt = $this->db->prepare("
            SELECT u.id, u.usuario, u.contrasena, r.nombre as rol, 
                   p.primer_nombre, p.primer_apellido, p.correo
            FROM usuario u 
            JOIN persona p ON u.fk_persona = p.id
            JOIN rol r ON u.fk_rol = r.id
            WHERE u.usuario = ?
        ");
        $stmt->execute([$username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['contrasena'])) {
            unset($user['contrasena']); // No devolver la contraseña
            return $user;
        }
        return false;
    }

    public function all() {
        $stmt = $this->db->prepare("
            SELECT *
            FROM usuario u 
            JOIN persona p ON u.fk_persona = p.id
        ");
        $stmt->execute();
        $user = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $user;
    }

    public function createUser($userData, $role = 'alumno') {
        try {
            $this->db->beginTransaction();
            
            // Insertar persona
            $stmtPersona = $this->db->prepare("
                INSERT INTO persona (
                    primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
                    cedula, sexo, numero_telefono, correo
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmtPersona->execute([
                $userData['primer_nombre'],
                $userData['segundo_nombre'] ?? null,
                $userData['primer_apellido'],
                $userData['segundo_apellido'] ?? null,
                $userData['cedula'],
                $userData['sexo'] ?? null,
                $userData['numero_telefono'] ?? null,
                $userData['correo']
            ]);
            
            $personaId = $this->db->lastInsertId();
            
            // Insertar usuario
            $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
            $stmtUser = $this->db->prepare("
                INSERT INTO usuario (
                    usuario, contrasena, fk_persona, fk_rol
                ) VALUES (?, ?, ?, (SELECT id FROM rol WHERE nombre = ?))
            ");
            $stmtUser->execute([
                $userData['usuario'],
                $hashedPassword,
                $personaId,
                $role
            ]);
            
            $this->db->commit();
            return true;
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }
} 