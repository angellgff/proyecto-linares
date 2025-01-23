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

    public function createUserWithPerson($personaData, $userData) {
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
                $personaData['primer_nombre'],
                $personaData['segundo_nombre'] ?? null,
                $personaData['primer_apellido'],
                $personaData['segundo_apellido'] ?? null,
                $personaData['cedula'],
                $personaData['sexo'] ?? null,
                $personaData['numero_telefono'] ?? null,
                $personaData['correo']
            ]);
            
            $personaId = $this->db->lastInsertId();
            
            // Obtener el ID del rol
            $stmtRol = $this->db->prepare("SELECT id FROM rol WHERE nombre = ?");
            $stmtRol->execute([$userData['rol']]);
            $rolId = $stmtRol->fetchColumn();

            if (!$rolId) {
                throw new \Exception("Rol no encontrado");
            }
            
            // Insertar usuario
            $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
            $stmtUser = $this->db->prepare("
                INSERT INTO usuario (
                    usuario, contrasena, fk_persona, fk_rol
                ) VALUES (?, ?, ?, ?)
            ");
            $stmtUser->execute([
                $userData['usuario'],
                $hashedPassword,
                $personaId,
                $rolId
            ]);
            
            $userId = $this->db->lastInsertId();
            
            $this->db->commit();
            return $userId;
        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw new \Exception("Error al crear el usuario: " . $e->getMessage());
        }
    }

    public function updateUserWithPerson($userId, $personaData, $userData) {
        try {
            $this->db->beginTransaction();

            // Obtener el ID de la persona asociada al usuario
            $stmtGetPersona = $this->db->prepare("SELECT fk_persona FROM usuario WHERE id = ?");
            $stmtGetPersona->execute([$userId]);
            $personaId = $stmtGetPersona->fetchColumn();

            if (!$personaId) {
                throw new \Exception("Usuario no encontrado");
            }

            // Actualizar persona
            $stmtPersona = $this->db->prepare("
                UPDATE persona SET 
                    primer_nombre = ?, 
                    segundo_nombre = ?, 
                    primer_apellido = ?, 
                    segundo_apellido = ?, 
                    cedula = ?, 
                    sexo = ?, 
                    numero_telefono = ?, 
                    correo = ?,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            $stmtPersona->execute([
                $personaData['primer_nombre'],
                $personaData['segundo_nombre'] ?? null,
                $personaData['primer_apellido'],
                $personaData['segundo_apellido'] ?? null,
                $personaData['cedula'],
                $personaData['sexo'] ?? null,
                $personaData['numero_telefono'] ?? null,
                $personaData['correo'],
                $personaId
            ]);

            // Actualizar usuario
            $sql = "UPDATE usuario SET usuario = ?";
            $params = [$userData['usuario']];

            if (!empty($userData['password'])) {
                $sql .= ", contrasena = ?";
                $params[] = password_hash($userData['password'], PASSWORD_DEFAULT);
            }

            $sql .= ", updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $params[] = $userId;

            $stmtUser = $this->db->prepare($sql);
            $stmtUser->execute($params);

            $this->db->commit();
            return true;
        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw new \Exception("Error al actualizar el usuario: " . $e->getMessage());
        }
    }

    public function getByIdWithDetails($id) {
        $stmt = $this->db->prepare("
            SELECT u.*, p.*, r.nombre as rol_nombre
            FROM usuario u
            JOIN persona p ON u.fk_persona = p.id
            JOIN rol r ON u.fk_rol = r.id
            WHERE u.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        try {
            $fields = ['usuario = :usuario'];
            $params = [':usuario' => $data['usuario']];
            
            if (isset($data['password'])) {
                $fields[] = 'contrasena = :password';
                $params[':password'] = $data['password'];
            }
            
            $sql = "UPDATE usuario SET " . implode(', ', $fields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $params[':id'] = $id;
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (\PDOException $e) {
            throw new \Exception("Error al actualizar el usuario: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            // Obtener el ID de la persona antes de eliminar el usuario
            $stmt = $this->db->prepare("SELECT fk_persona FROM usuario WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$user) {
                throw new \Exception("Usuario no encontrado");
            }
            
            // Eliminar el usuario
            $stmt = $this->db->prepare("DELETE FROM usuario WHERE id = ?");
            if (!$stmt->execute([$id])) {
                throw new \Exception("Error al eliminar el usuario");
            }
            
            // Eliminar la persona asociada
            $stmt = $this->db->prepare("DELETE FROM persona WHERE id = ?");
            if (!$stmt->execute([$user['fk_persona']])) {
                throw new \Exception("Error al eliminar la persona asociada");
            }
            
            return true;
        } catch (\PDOException $e) {
            throw new \Exception("Error al eliminar el usuario: " . $e->getMessage());
        }
    }
} 