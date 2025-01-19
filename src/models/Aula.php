<?php
namespace Models;

class Aula {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT a.*, ta.nombre as tipo_aula
            FROM aula a
            JOIN tipo_aula ta ON a.fk_tipo_aula = ta.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT a.*, ta.nombre as tipo_aula
            FROM aula a
            JOIN tipo_aula ta ON a.fk_tipo_aula = ta.id
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($codigo, $tipoAulaId) {
        $stmt = $this->db->prepare("
            INSERT INTO aula (codigo, fk_tipo_aula) VALUES (?, ?)
        ");
        return $stmt->execute([$codigo, $tipoAulaId]);
    }

    public function update($id, $codigo, $tipoAulaId) {
        $stmt = $this->db->prepare("
            UPDATE aula 
            SET codigo = ?, fk_tipo_aula = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        return $stmt->execute([$codigo, $tipoAulaId, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM aula WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 