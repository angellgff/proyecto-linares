<?php
namespace Models;

class BloqueHorario {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT bh.*,
                   m.codigo as materia_codigo,
                   a.codigo as aula_codigo,
                   t.codigo as trayecto_codigo,
                   CONCAT(p.primer_nombre, ' ', p.primer_apellido) as profesor_nombre,
                   CASE bh.dia
                       WHEN 1 THEN 'Lunes'
                       WHEN 2 THEN 'Martes'
                       WHEN 3 THEN 'Miércoles'
                       WHEN 4 THEN 'Jueves'
                       WHEN 5 THEN 'Viernes'
                   END as nombre_dia
            FROM bloque_horario bh
            JOIN materia m ON bh.fk_materia = m.id
            JOIN aula a ON bh.fk_aula = a.id
            JOIN trayecto t ON bh.fk_trayecto = t.id
            JOIN profesor pr ON bh.fk_profesor = pr.id
            JOIN usuario u ON pr.fk_usuario = u.id
            JOIN persona p ON u.fk_persona = p.id
            ORDER BY bh.dia, bh.hora
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT bh.*,
                   m.codigo as materia_codigo,
                   a.codigo as aula_codigo,
                   t.codigo as trayecto_codigo,
                   CONCAT(p.primer_nombre, ' ', p.primer_apellido) as profesor_nombre
            FROM bloque_horario bh
            JOIN materia m ON bh.fk_materia = m.id
            JOIN aula a ON bh.fk_aula = a.id
            JOIN trayecto t ON bh.fk_trayecto = t.id
            JOIN profesor pr ON bh.fk_profesor = pr.id
            JOIN usuario u ON pr.fk_usuario = u.id
            JOIN persona p ON u.fk_persona = p.id
            WHERE bh.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($dia, $hora, $materiaId, $aulaId, $profesorId, $trayectoId) {
        try {
            // Verificar si ya existe un horario en ese bloque
            if ($this->existeConflicto($dia, $hora, $aulaId, $profesorId, null)) {
                throw new \Exception("Ya existe un horario asignado en este bloque");
            }

            $stmt = $this->db->prepare("
                INSERT INTO bloque_horario (
                    dia, hora, fk_materia, fk_aula, fk_profesor, fk_trayecto
                ) VALUES (?, ?, ?, ?, ?, ?)
            ");
            return $stmt->execute([$dia, $hora, $materiaId, $aulaId, $profesorId, $trayectoId]);
        } catch (\PDOException $e) {
            throw new \Exception("Error al crear el horario: " . $e->getMessage());
        }
    }

    public function update($id, $dia, $hora, $materiaId, $aulaId, $profesorId, $trayectoId) {
        try {
            // Verificar si ya existe un horario en ese bloque (excluyendo el actual)
            if ($this->existeConflicto($dia, $hora, $aulaId, $profesorId, $id)) {
                throw new \Exception("Ya existe un horario asignado en este bloque");
            }

            $stmt = $this->db->prepare("
                UPDATE bloque_horario 
                SET dia = ?,
                    hora = ?,
                    fk_materia = ?,
                    fk_aula = ?,
                    fk_profesor = ?,
                    fk_trayecto = ?,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            return $stmt->execute([$dia, $hora, $materiaId, $aulaId, $profesorId, $trayectoId, $id]);
        } catch (\PDOException $e) {
            throw new \Exception("Error al actualizar el horario: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM bloque_horario WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            throw new \Exception("Error al eliminar el horario: " . $e->getMessage());
        }
    }

    private function existeConflicto($dia, $hora, $aulaId, $profesorId, $excludeId = null) {
        $sql = "
            SELECT COUNT(*) as count
            FROM bloque_horario
            WHERE dia = ? AND hora = ? 
            AND (fk_aula = ? OR fk_profesor = ?)
        ";
        $params = [$dia, $hora, $aulaId, $profesorId];

        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function getAulasDisponibles($dia, $hora, $excludeId = null) {
        $sql = "
            SELECT a.*
            FROM aula a
            WHERE a.id NOT IN (
                SELECT fk_aula
                FROM bloque_horario
                WHERE dia = ? AND hora = ?
        ";
        
        $params = [$dia, $hora];
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $sql .= ")";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProfesoresDisponibles($dia, $hora, $excludeId = null) {
        $sql = "
            SELECT pr.id, p.primer_nombre, p.primer_apellido
            FROM profesor pr
            JOIN usuario u ON pr.fk_usuario = u.id
            JOIN persona p ON u.fk_persona = p.id
            WHERE pr.id NOT IN (
                SELECT fk_profesor
                FROM bloque_horario
                WHERE dia = ? AND hora = ?
        ";
        
        $params = [$dia, $hora];
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $sql .= ")";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 