<?php
namespace Models;

class Alumno {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT a.*, pe.primer_nombre, pe.primer_apellido, pe.cedula,
                   t.codigo as trayecto_codigo
            FROM alumno a
            JOIN persona pe ON a.fk_persona = pe.id
            JOIN trayecto t ON a.fk_trayecto = t.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT a.*, pe.*, t.codigo as trayecto_codigo
            FROM alumno a
            JOIN persona pe ON a.fk_persona = pe.id
            JOIN trayecto t ON a.fk_trayecto = t.id
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($personaId, $trayectoId) {
        $stmt = $this->db->prepare("
            INSERT INTO alumno (fk_persona, fk_trayecto) VALUES (?, ?)
        ");
        return $stmt->execute([$personaId, $trayectoId]);
    }

    public function getHorario($alumnoId) {
        $stmt = $this->db->prepare("
            SELECT bh.*, m.codigo as materia_codigo, a.codigo as aula_codigo,
                   CONCAT(pe.primer_nombre, ' ', pe.primer_apellido) as profesor_nombre
            FROM alumno al
            JOIN bloque_horario bh ON al.fk_trayecto = bh.fk_trayecto
            JOIN materia m ON bh.fk_materia = m.id
            JOIN aula a ON bh.fk_aula = a.id
            JOIN profesor p ON bh.fk_profesor = p.id
            JOIN usuario u ON p.fk_usuario = u.id
            JOIN persona pe ON u.fk_persona = pe.id
            WHERE al.id = ?
        ");
        $stmt->execute([$alumnoId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 