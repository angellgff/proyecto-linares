<?php
namespace Models;

class Rol {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM rol");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM rol WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByNombre($nombre) {
        $stmt = $this->db->prepare("SELECT * FROM rol WHERE nombre = ?");
        $stmt->execute([$nombre]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($nombre) {
        $stmt = $this->db->prepare("INSERT INTO rol (nombre) VALUES (?)");
        return $stmt->execute([$nombre]);
    }

    public function update($id, $nombre) {
        $stmt = $this->db->prepare("
            UPDATE rol 
            SET nombre = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        return $stmt->execute([$nombre, $id]);
    }

    public function getUsuarios($rolId) {
        $stmt = $this->db->prepare("
            SELECT u.*, p.primer_nombre, p.primer_apellido, p.cedula
            FROM usuario u
            JOIN persona p ON u.fk_persona = p.id
            WHERE u.fk_rol = ?
        ");
        $stmt->execute([$rolId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 