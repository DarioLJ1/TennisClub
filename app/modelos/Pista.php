<?php
namespace app\modelos;

use app\config\Database;

class Pista {

    private $db;

    public function __construct(){

        $this->db = new Database;

    }

    public function obtenerPistas(){

        $this->db->query('SELECT * FROM pistas');
        return $this->db->resultSet();

    }

    public function obtenerPistaPorId($id){

        $this->db->query('SELECT * FROM pistas WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();

    }

    public function agregarPista($datos){

        $this->db->query('INSERT INTO pistas (nombre, tipo, estado) VALUES(:nombre, :tipo, :estado)');
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':tipo', $datos['tipo']);
        $this->db->bind(':estado', $datos['estado']);

        if($this->db->execute()){

            return true;

        } else {

            return false;

        }
    }

    public function actualizarPista($datos){

        $this->db->query('UPDATE pistas SET nombre = :nombre, tipo = :tipo, estado = :estado WHERE id = :id');
        $this->db->bind(':id', $datos['id']);
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':tipo', $datos['tipo']);
        $this->db->bind(':estado', $datos['estado']);

        if($this->db->execute()){

            return true;

        } else {

            return false;

        }
    }

    public function eliminarPista($id){

        $this->db->query('DELETE FROM pistas WHERE id = :id');
        $this->db->bind(':id', $id);

        if($this->db->execute()){

            return true;

        } else {

            return false;

        }
    }

    public function obtenerMantenimientos($id_pista){

        $this->db->query('SELECT * FROM mantenimientos WHERE id_pista = :id_pista ORDER BY fecha_inicio DESC');
        $this->db->bind(':id_pista', $id_pista);
        return $this->db->resultSet();
        
    }
}