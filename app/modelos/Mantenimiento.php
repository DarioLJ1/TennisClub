<?php
namespace app\modelos;

use app\config\Database;

class Mantenimiento {

    private $db;

    public function __construct(){

        $this->db = new Database;

    }

    public function agregarMantenimiento($datos){

        $this->db->query('INSERT INTO mantenimientos (id_pista, fecha_inicio, fecha_fin, descripcion, estado) VALUES(:id_pista, :fecha_inicio, :fecha_fin, :descripcion, :estado)');
        $this->db->bind(':id_pista', $datos['id_pista']);
        $this->db->bind(':fecha_inicio', $datos['fecha_inicio']);
        $this->db->bind(':fecha_fin', $datos['fecha_fin']);
        $this->db->bind(':descripcion', $datos['descripcion']);
        $this->db->bind(':estado', $datos['estado']);

        if($this->db->execute()){

            return true;

        } else {

            return false;

        }
    }

    public function obtenerMantenimientoPorId($id){

        $this->db->query('SELECT * FROM mantenimientos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();

    }

    public function actualizarMantenimiento($datos){

        $this->db->query('UPDATE mantenimientos SET fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin, descripcion = :descripcion, estado = :estado WHERE id = :id');
        $this->db->bind(':id', $datos['id']);
        $this->db->bind(':fecha_inicio', $datos['fecha_inicio']);
        $this->db->bind(':fecha_fin', $datos['fecha_fin']);
        $this->db->bind(':descripcion', $datos['descripcion']);
        $this->db->bind(':estado', $datos['estado']);

        if($this->db->execute()){

            return true;

        } else {

            return false;

        }
    }

    public function eliminarMantenimiento($id){

        $this->db->query('DELETE FROM mantenimientos WHERE id = :id');
        $this->db->bind(':id', $id);

        if($this->db->execute()){

            return true;

        } else {

            return false;
            
        }
    }
}