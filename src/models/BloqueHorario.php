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
                   CONCAT(p.primer_nombre, ' ', p.primer_apellido) as profesor_nombre
            FROM bloque_horario bh
            JOIN materia m ON bh.fk_materia = m.id
            JOIN aula a ON bh.fk_aula = a.id
            JOIN trayecto t ON bh.fk_trayecto = t.id
            JOIN profesor pr ON bh.fk_profesor = pr.id
            JOIN usuario u ON pr.fk_usuario = u.id
            JOIN persona p ON u.fk_persona = p.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO bloque_horario (
                hora, fk_trayecto, fk_aula, fk_materia, fk_profesor
            ) VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['hora'],
            $data['fk_trayecto'],
            $data['fk_aula'],
            $data['fk_materia'],
            $data['fk_profesor']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE bloque_horario 
            SET hora = ?, 
                fk_trayecto = ?, 
                fk_aula = ?, 
                fk_materia = ?, 
                fk_profesor = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['hora'],
            $data['fk_trayecto'],
            $data['fk_aula'],
            $data['fk_materia'],
            $data['fk_profesor'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM bloque_horario WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function verificarDisponibilidad($hora, $aulaId, $profesorId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM bloque_horario 
            WHERE hora = ? AND (fk_aula = ? OR fk_profesor = ?)
        ");
        $stmt->execute([$hora, $aulaId, $profesorId]);
        return $stmt->fetchColumn() == 0;
    }
} 