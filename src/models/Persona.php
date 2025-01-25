<?php
namespace Models;

class Persona {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM persona ORDER BY primer_apellido, primer_nombre");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM persona WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($cedula, $primerNombre, $segundoNombre, $primerApellido, $segundoApellido, $telefono, $correo) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO persona (
                    cedula, 
                    primer_nombre, 
                    segundo_nombre, 
                    primer_apellido, 
                    segundo_apellido, 
                    numero_telefono, 
                    correo
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $cedula,
                $primerNombre,
                $segundoNombre,
                $primerApellido,
                $segundoApellido,
                $telefono,
                $correo
            ]);

            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Ya existe una persona con esta cédula");
            }
            throw new \Exception("Error al crear la persona: " . $e->getMessage());
        }
    }

    public function update($id, $cedula, $primerNombre, $segundoNombre, $primerApellido, $segundoApellido, $telefono, $correo) {
        try {
            $stmt = $this->db->prepare("
                UPDATE persona 
                SET cedula = ?,
                    primer_nombre = ?,
                    segundo_nombre = ?,
                    primer_apellido = ?,
                    segundo_apellido = ?,
                    numero_telefono = ?,
                    correo = ?,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            
            return $stmt->execute([
                $cedula,
                $primerNombre,
                $segundoNombre,
                $primerApellido,
                $segundoApellido,
                $telefono,
                $correo,
                $id
            ]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Ya existe una persona con esta cédula");
            }
            throw new \Exception("Error al actualizar la persona: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM persona WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            throw new \Exception("Error al eliminar la persona: " . $e->getMessage());
        }
    }

    public function getByCedula($cedula) {
        $stmt = $this->db->prepare("SELECT * FROM persona WHERE cedula = ?");
        $stmt->execute([$cedula]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
} 