<?php
namespace Models;

class Materia {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM materia");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM materia WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($codigo) {
        $stmt = $this->db->prepare("INSERT INTO materia (codigo) VALUES (?)");
        return $stmt->execute([$codigo]);
    }

    public function update($id, $codigo) {
        $stmt = $this->db->prepare("
            UPDATE materia SET codigo = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?
        ");
        return $stmt->execute([$codigo, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM materia WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 