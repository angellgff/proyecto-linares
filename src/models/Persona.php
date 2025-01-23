<?php
namespace Models;

class Persona {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM persona WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO persona (
                primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
                cedula, sexo, numero_telefono, correo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['primer_nombre'],
            $data['segundo_nombre'] ?? null,
            $data['primer_apellido'],
            $data['segundo_apellido'] ?? null,
            $data['cedula'],
            $data['sexo'] ?? null,
            $data['numero_telefono'] ?? null,
            $data['correo']
        ]);
    }

    public function update($id, $data) {
        try {
            error_log("Iniciando actualización de persona ID: " . $id);
            error_log("Datos recibidos: " . print_r($data, true));
            
            $sql = "UPDATE persona SET 
                    primer_nombre = :primer_nombre,
                    segundo_nombre = :segundo_nombre,
                    primer_apellido = :primer_apellido,
                    segundo_apellido = :segundo_apellido,
                    cedula = :cedula,
                    sexo = :sexo,
                    numero_telefono = :numero_telefono,
                    correo = :correo,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id";
                    
            $stmt = $this->db->prepare($sql);
            
            $params = [
                ':primer_nombre' => $data['primer_nombre'],
                ':segundo_nombre' => $data['segundo_nombre'],
                ':primer_apellido' => $data['primer_apellido'],
                ':segundo_apellido' => $data['segundo_apellido'],
                ':cedula' => $data['cedula'],
                ':sexo' => $data['sexo'],
                ':numero_telefono' => $data['numero_telefono'],
                ':correo' => $data['correo'],
                ':id' => $id
            ];
            
            error_log("SQL: " . $sql);
            error_log("Parámetros: " . print_r($params, true));
            
            $result = $stmt->execute($params);
            error_log("Resultado de la actualización: " . ($result ? "éxito" : "fallo"));
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Error en actualización de persona: " . $e->getMessage());
            throw new \Exception("Error al actualizar la persona: " . $e->getMessage());
        }
    }
} 