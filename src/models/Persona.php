<?php
namespace Models;

class Persona {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM persona WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO persona (
                primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
                cedula, sexo, numero_telefono, correo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['primer_nombre'],
            $data['segundo_nombre'] ?? null,
            $data['primer_apellido'],
            $data['segundo_apellido'] ?? null,
            $data['cedula'],
            $data['sexo'] ?? null,
            $data['numero_telefono'] ?? null,
            $data['correo']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE persona SET 
                primer_nombre = ?, segundo_nombre = ?, primer_apellido = ?, 
                segundo_apellido = ?, cedula = ?, sexo = ?, 
                numero_telefono = ?, correo = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['primer_nombre'],
            $data['segundo_nombre'] ?? null,
            $data['primer_apellido'],
            $data['segundo_apellido'] ?? null,
            $data['cedula'],
            $data['sexo'] ?? null,
            $data['numero_telefono'] ?? null,
            $data['correo'],
            $id
        ]);
    }
} 