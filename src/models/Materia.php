<?php
namespace Models;

class Materia {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT m.*, 
                   (SELECT COUNT(*) FROM bloque_horario WHERE fk_materia = m.id) as total_horarios
            FROM materia m
            ORDER BY m.codigo
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM materia WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($codigo) {
        try {
            $stmt = $this->db->prepare("INSERT INTO materia (codigo) VALUES (?)");
            return $stmt->execute([$codigo]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Ya existe una materia con este código");
            }
            throw new \Exception("Error al crear la materia: " . $e->getMessage());
        }
    }

    public function update($id, $codigo) {
        try {
            $stmt = $this->db->prepare("
                UPDATE materia 
                SET codigo = ?, 
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            return $stmt->execute([$codigo, $id]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Ya existe una materia con este código");
            }
            throw new \Exception("Error al actualizar la materia: " . $e->getMessage());
        }
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM materia WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getHorarios($materiaId) {
        $stmt = $this->db->prepare("
            SELECT bh.*, t.codigo as trayecto_codigo
            FROM bloque_horario bh
            JOIN trayecto t ON bh.fk_trayecto = t.id
            WHERE bh.fk_materia = ?
        ");
        $stmt->execute([$materiaId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 