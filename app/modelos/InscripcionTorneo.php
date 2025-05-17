<?php
namespace app\modelos;

use app\config\Database;

class InscripcionTorneo {
    
    private $db;

    public function __construct() {

        $this->db = new Database;

    }

    public function inscribirUsuario($id_torneo, $id_usuario) {

        $this->db->query('INSERT INTO inscripciones_torneos (id_torneo, id_usuario) VALUES(:id_torneo, :id_usuario)');
        $this->db->bind(':id_torneo', $id_torneo);
        $this->db->bind(':id_usuario', $id_usuario);
        return $this->db->execute();

    }

    public function obtenerInscripcionesPorTorneo($id_torneo) {

        $this->db->query('SELECT it.*, u.nombre FROM inscripciones_torneos it JOIN usuarios u ON it.id_usuario = u.id WHERE it.id_torneo = :id_torneo');
        $this->db->bind(':id_torneo', $id_torneo);
        return $this->db->resultSet();

    }

    public function obtenerInscripcionesPorUsuario($id_usuario) {

        $this->db->query('SELECT it.*, t.nombre as nombre_torneo, t.fecha_inicio, t.fecha_fin FROM inscripciones_torneos it JOIN torneos t ON it.id_torneo = t.id WHERE it.id_usuario = :id_usuario');
        $this->db->bind(':id_usuario', $id_usuario);
        return $this->db->resultSet();

    }

    public function actualizarEstadoInscripcion($id, $estado) {

        $this->db->query('UPDATE inscripciones_torneos SET estado = :estado WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':estado', $estado);
        return $this->db->execute();

    }

    public function eliminarInscripcion($id) {

        $this->db->query('DELETE FROM inscripciones_torneos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();

    }
}

