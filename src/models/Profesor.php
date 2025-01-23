<?php
namespace Models;

class Profesor {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   pe.primer_nombre, 
                   pe.segundo_nombre,
                   pe.primer_apellido, 
                   pe.segundo_apellido,
                   pe.cedula,
                   pe.sexo,
                   pe.numero_telefono,
                   pe.correo
            FROM profesor p
            JOIN usuario u ON p.fk_usuario = u.id
            JOIN persona pe ON u.fk_persona = pe.id
            ORDER BY pe.primer_apellido, pe.primer_nombre
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   pe.primer_nombre, 
                   pe.segundo_nombre,
                   pe.primer_apellido, 
                   pe.segundo_apellido,
                   pe.cedula,
                   pe.sexo,
                   pe.numero_telefono,
                   pe.correo,
                   u.usuario,
                   u.id as fk_usuario,
                   pe.id as fk_persona
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
            SELECT bh.*, 
                   m.codigo as materia_codigo, 
                   a.codigo as aula_codigo,
                   t.codigo as trayecto_codigo,
                   bh.dia,
                   bh.hora
            FROM bloque_horario bh
            JOIN materia m ON bh.fk_materia = m.id
            JOIN aula a ON bh.fk_aula = a.id
            JOIN trayecto t ON bh.fk_trayecto = t.id
            WHERE bh.fk_profesor = ?
            ORDER BY 
                CASE bh.dia
                    WHEN 'Lunes' THEN 1
                    WHEN 'Martes' THEN 2
                    WHEN 'Miércoles' THEN 3
                    WHEN 'Jueves' THEN 4
                    WHEN 'Viernes' THEN 5
                END,
                bh.hora
        ");
        $stmt->execute([$profesorId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 