<?php
namespace Models;

class Trayecto {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT t.*, 
                   (SELECT COUNT(*) FROM alumno WHERE fk_trayecto = t.id) as total_alumnos
            FROM trayecto t
            ORDER BY t.codigo
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM trayecto WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($codigo, $periodo) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO trayecto (codigo, periodo) 
                VALUES (?, ?)
            ");
            return $stmt->execute([$codigo, $periodo]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Ya existe un trayecto con este código");
            }
            throw new \Exception("Error al crear el trayecto: " . $e->getMessage());
        }
    }

    public function update($id, $codigo, $periodo) {
        try {
            $stmt = $this->db->prepare("
                UPDATE trayecto 
                SET codigo = ?, 
                    periodo = ?, 
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            return $stmt->execute([$codigo, $periodo, $id]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Ya existe un trayecto con este código");
            }
            throw new \Exception("Error al actualizar el trayecto: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM trayecto WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("No se puede eliminar este trayecto porque tiene alumnos asociados");
            }
            throw new \Exception("Error al eliminar el trayecto: " . $e->getMessage());
        }
    }

    public function getAlumnos($trayectoId) {
        $stmt = $this->db->prepare("
            SELECT a.id, 
                   p.cedula,
                   p.primer_nombre,
                   p.primer_apellido,
                   p.numero_telefono,
                   p.correo
            FROM alumno a
            JOIN persona p ON a.fk_persona = p.id
            WHERE a.fk_trayecto = ?
            ORDER BY p.primer_apellido, p.primer_nombre
        ");
        $stmt->execute([$trayectoId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 