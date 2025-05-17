<?php
namespace app\modelos;

use app\config\Database;

class Pago {

    private $db;

    public function __construct() {

        $this->db = new Database();

    }

    public function registrarPago($datos) {

        $this->db->query('INSERT INTO pagos (id_reserva, monto, metodo_pago, estado) VALUES (:id_reserva, :monto, :metodo_pago, :estado)');
        $this->db->bind(':id_reserva', $datos['id_reserva']);
        $this->db->bind(':monto', $datos['monto']);
        $this->db->bind(':metodo_pago', $datos['metodo_pago']);
        $this->db->bind(':estado', $datos['estado']);

        return $this->db->execute();

    }

    public function obtenerPagoPorReserva($id_reserva) {

        $this->db->query('SELECT * FROM pagos WHERE id_reserva = :id_reserva');
        $this->db->bind(':id_reserva', $id_reserva);
        return $this->db->single();
        
    }
}

