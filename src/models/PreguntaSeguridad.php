<?php
namespace Models;

class PreguntaSeguridad {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT ps.*, tps.nombre as tipo_pregunta
            FROM pregunta_seguridad ps
            JOIN tipo_pregunta_seguridad tps ON ps.fk_tipo_pregunta_seguridad = tps.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT ps.*, tps.nombre as tipo_pregunta
            FROM pregunta_seguridad ps
            JOIN tipo_pregunta_seguridad tps ON ps.fk_tipo_pregunta_seguridad = tps.id
            WHERE ps.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($respuesta, $tipoPreguntaId) {
        $stmt = $this->db->prepare("
            INSERT INTO pregunta_seguridad (respuesta, fk_tipo_pregunta_seguridad) 
            VALUES (?, ?)
        ");
        return $stmt->execute([
            password_hash($respuesta, PASSWORD_DEFAULT),
            $tipoPreguntaId
        ]);
    }

    public function verificarRespuesta($id, $respuesta) {
        $stmt = $this->db->prepare("SELECT respuesta FROM pregunta_seguridad WHERE id = ?");
        $stmt->execute([$id]);
        $pregunta = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($pregunta) {
            return password_verify($respuesta, $pregunta['respuesta']);
        }
        return false;
    }
} 