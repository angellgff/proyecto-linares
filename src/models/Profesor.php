<?php
namespace Models;

class Profesor {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT p.*, pe.primer_nombre, pe.primer_apellido, pe.cedula
            FROM profesor p
            JOIN usuario u ON p.fk_usuario = u.id
            JOIN persona pe ON u.fk_persona = pe.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, pe.*, u.usuario
            FROM profesor p
            JOIN usuario u ON p.fk_usuario = u.id
            JOIN persona pe ON u.fk_persona = pe.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($usuarioId) {
        $stmt = $this->db->prepare("INSERT INTO profesor (fk_usuario) VALUES (?)");
        return $stmt->execute([$usuarioId]);
    }

    public function getHorarios($profesorId) {
        $stmt = $this->db->prepare("
            SELECT bh.*, m.codigo as materia_codigo, a.codigo as aula_codigo,
                   t.codigo as trayecto_codigo
            FROM bloque_horario bh
            JOIN materia m ON bh.fk_materia = m.id
            JOIN aula a ON bh.fk_aula = a.id
            JOIN trayecto t ON bh.fk_trayecto = t.id
            WHERE bh.fk_profesor = ?
        ");
        $stmt->execute([$profesorId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 