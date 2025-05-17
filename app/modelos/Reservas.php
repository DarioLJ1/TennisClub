<?php

    class Reserva {

        private $db;

        public function __construct(){

            $this->db = new DataBase;

        }

        public function crearReserva($datos){

            $this->db->query('INSERT INTO reservas (id_usuario, id_pista, fecha, hora_inicio, hora_fin) VALUES (:id_usuario, :id_pista, :fecha, :hora_inicio, :hora_fin)');
            $this->db->bind(':id_usuario', $datos['id_usuario']);
            $this->db->bind(':id_pista', $datos['id_pista']);
            $this->db->bind(':fecha', $datos['fecha']);
            $this->db->bind(':hora_inicio', $datos['hora_inicio']);
            $this->db->bind('hora_fin', $datos ['hora_fin']);

            if ($this->db->execute()){

                return true;

            }else{

                return false;

            }

        }

        public function obtenerReservasPorUsuario($id_usuario){

            $this->db->query('SELECT r.*,p.nombre as nombre_pista FROM reservas r JOIN pistas p ON r.id_pista = p.id WHERE r.id_usuario = :id_usuario ORDER BY r.fecha, r.hora_inicio');
            $this->db->bind(':id_usuario', $id_usuario);
            return $this->db->resultSet();

        }

    }