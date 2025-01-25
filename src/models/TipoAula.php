<?php
namespace Models;

class TipoAula {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM tipo_aula ORDER BY nombre");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tipo_aula WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($nombre) {
        try {
            $stmt = $this->db->prepare("INSERT INTO tipo_aula (nombre) VALUES (?)");
            return $stmt->execute([$nombre]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Ya existe un tipo de aula con este nombre");
            }
            throw new \Exception("Error al crear el tipo de aula: " . $e->getMessage());
        }
    }

    public function update($id, $nombre) {
        try {
            $stmt = $this->db->prepare("
                UPDATE tipo_aula 
                SET nombre = ?, 
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            return $stmt->execute([$nombre, $id]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Ya existe un tipo de aula con este nombre");
            }
            throw new \Exception("Error al actualizar el tipo de aula: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM tipo_aula WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("No se puede eliminar este tipo de aula porque hay aulas que lo utilizan");
            }
            throw new \Exception("Error al eliminar el tipo de aula: " . $e->getMessage());
        }
    }
} 