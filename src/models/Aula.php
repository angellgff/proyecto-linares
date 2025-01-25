<?php
namespace Models;

class Aula {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT a.*, ta.nombre as tipo_aula,
                   (SELECT COUNT(*) FROM bloque_horario WHERE fk_aula = a.id) as total_horarios
            FROM aula a
            JOIN tipo_aula ta ON a.fk_tipo_aula = ta.id
            ORDER BY a.codigo
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
        try {
            $stmt = $this->db->prepare("
                INSERT INTO aula (codigo, fk_tipo_aula) 
                VALUES (?, ?)
            ");
            return $stmt->execute([$codigo, $tipoAulaId]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Ya existe un aula con este código");
            }
            throw new \Exception("Error al crear el aula: " . $e->getMessage());
        }
    }

    public function update($id, $codigo, $tipoAulaId) {
        try {
            $stmt = $this->db->prepare("
                UPDATE aula 
                SET codigo = ?, 
                    fk_tipo_aula = ?,
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            return $stmt->execute([$codigo, $tipoAulaId, $id]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Ya existe un aula con este código");
            }
            throw new \Exception("Error al actualizar el aula: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM aula WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("No se puede eliminar esta aula porque tiene horarios asociados");
            }
            throw new \Exception("Error al eliminar el aula: " . $e->getMessage());
        }
    }

    public function getHorarios($aulaId) {
        $stmt = $this->db->prepare("
            SELECT bh.*, m.codigo as materia_codigo, t.codigo as trayecto_codigo
            FROM bloque_horario bh
            JOIN materia m ON bh.fk_materia = m.id
            JOIN trayecto t ON bh.fk_trayecto = t.id
            WHERE bh.fk_aula = ?
        ");
        $stmt->execute([$aulaId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 