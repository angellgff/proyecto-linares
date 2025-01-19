<?php
namespace Models;

class TipoPreguntaSeguridad {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM tipo_pregunta_seguridad");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tipo_pregunta_seguridad WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($nombre) {
        $stmt = $this->db->prepare("INSERT INTO tipo_pregunta_seguridad (nombre) VALUES (?)");
        return $stmt->execute([$nombre]);
    }

    public function update($id, $nombre) {
        $stmt = $this->db->prepare("
            UPDATE tipo_pregunta_seguridad 
            SET nombre = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        return $stmt->execute([$nombre, $id]);
    }
} 