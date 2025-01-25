<?php
namespace Models;

class Alumno {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT a.*, p.cedula, p.primer_nombre, p.primer_apellido, 
                   t.codigo as trayecto_codigo, t.periodo as trayecto_periodo
            FROM alumno a
            JOIN persona p ON a.fk_persona = p.id
            JOIN trayecto t ON a.fk_trayecto = t.id
            ORDER BY p.primer_apellido, p.primer_nombre
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT a.*, 
                   p.cedula, p.primer_nombre, p.segundo_nombre,
                   p.primer_apellido, p.segundo_apellido, 
                   p.numero_telefono, p.correo,
                   t.codigo as trayecto_codigo,
                   t.periodo as trayecto_periodo
            FROM alumno a
            JOIN persona p ON a.fk_persona = p.id
            JOIN trayecto t ON a.fk_trayecto = t.id
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($personaId, $trayectoId) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO alumno (fk_persona, fk_trayecto) 
                VALUES (?, ?)
            ");
            return $stmt->execute([$personaId, $trayectoId]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Esta persona ya está registrada como alumno");
            }
            throw new \Exception("Error al crear el alumno: " . $e->getMessage());
        }
    }

    public function update($id, $trayectoId) {
        try {
            $stmt = $this->db->prepare("
                UPDATE alumno 
                SET fk_trayecto = ?,
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            return $stmt->execute([$trayectoId, $id]);
        } catch (\PDOException $e) {
            throw new \Exception("Error al actualizar el alumno: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM alumno WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            throw new \Exception("Error al eliminar el alumno: " . $e->getMessage());
        }
    }

    public function getHorarios($alumnoId) {
        try {
            $stmt = $this->db->prepare("
                SELECT bh.*, 
                       m.codigo as materia_codigo, 
                       a.codigo as aula_codigo,
                       CONCAT(p.primer_nombre, ' ', p.primer_apellido) as profesor_nombre,
                       CASE bh.dia
                           WHEN 'Lunes' THEN 1
                           WHEN 'Martes' THEN 2
                           WHEN 'Miércoles' THEN 3
                           WHEN 'Jueves' THEN 4
                           WHEN 'Viernes' THEN 5
                       END as dia,
                       bh.hora
                FROM alumno al
                JOIN bloque_horario bh ON al.fk_trayecto = bh.fk_trayecto
                JOIN materia m ON bh.fk_materia = m.id
                JOIN aula a ON bh.fk_aula = a.id
                JOIN profesor pr ON bh.fk_profesor = pr.id
                JOIN usuario u ON pr.fk_usuario = u.id
                JOIN persona p ON u.fk_persona = p.id
                WHERE al.id = ?
                ORDER BY bh.dia, bh.hora
            ");
            $stmt->execute([$alumnoId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Error al obtener los horarios del alumno");
        }
    }
} 