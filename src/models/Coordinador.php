<?php
namespace Models;

class Coordinador {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT c.*, pe.primer_nombre, pe.primer_apellido, pe.cedula
            FROM coordinador c
            JOIN usuario u ON c.fk_usuario = u.id
            JOIN persona pe ON u.fk_persona = pe.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT c.*, pe.*, u.usuario
            FROM coordinador c
            JOIN usuario u ON c.fk_usuario = u.id
            JOIN persona pe ON u.fk_persona = pe.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($usuarioId) {
        $stmt = $this->db->prepare("INSERT INTO coordinador (fk_usuario) VALUES (?)");
        return $stmt->execute([$usuarioId]);
    }
} 