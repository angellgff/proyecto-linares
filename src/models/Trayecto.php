<?php
namespace Models;

class Trayecto {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM trayecto");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM trayecto WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($codigo, $periodo) {
        $stmt = $this->db->prepare("
            INSERT INTO trayecto (codigo, periodo) VALUES (?, ?)
        ");
        return $stmt->execute([$codigo, $periodo]);
    }

    public function update($id, $codigo, $periodo) {
        $stmt = $this->db->prepare("
            UPDATE trayecto 
            SET codigo = ?, periodo = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        return $stmt->execute([$codigo, $periodo, $id]);
    }

    public function getAlumnos($trayectoId) {
        $stmt = $this->db->prepare("
            SELECT a.*, p.primer_nombre, p.primer_apellido, p.cedula
            FROM alumno a
            JOIN persona p ON a.fk_persona = p.id
            WHERE a.fk_trayecto = ?
        ");
        $stmt->execute([$trayectoId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 