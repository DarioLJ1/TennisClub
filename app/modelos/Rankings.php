<?php
namespace app\modelos;

use app\config\Database;

class Rankings {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Inscribe un usuario al ranking con puntuación aleatoria
    public function inscribir($usuario) {
        // Generar puntuación aleatoria (100 a 1000)
        $puntuacion = rand(100, 1000);

        // Insertar usuario en el ranking
        $this->db->query('INSERT INTO ranking (usuario_id, nombre, apellidos, puntuacion, posicion) VALUES (:usuario_id, :nombre, :apellidos, :puntuacion, 0)');
        $this->db->bind(':usuario_id', $usuario->id);
        $this->db->bind(':nombre', $usuario->nombre);
        $this->db->bind(':apellidos', $usuario->apellidos ?? '');
        $this->db->bind(':puntuacion', $puntuacion);

        if ($this->db->execute()) {
            // Actualizar posiciones
            $this->actualizarPosiciones();
            return true;
        }
        return false;
    }

    // Obtiene todos los usuarios en el ranking, ordenados por puntuación
    public function getRanking() {
        $this->db->query('SELECT * FROM ranking ORDER BY puntuacion DESC');
        return $this->db->resultSet();
    }

    // Verifica si un usuario ya está inscrito
    public function estaInscrito($usuario_id) {
        $this->db->query('SELECT * FROM ranking WHERE usuario_id = :usuario_id');
        $this->db->bind(':usuario_id', $usuario_id);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    // Actualiza las posiciones en el ranking
    private function actualizarPosiciones() {
        $ranking = $this->getRanking();
        $posicion = 1;
        foreach ($ranking as $jugador) {
            $this->db->query('UPDATE ranking SET posicion = :posicion WHERE id = :id');
            $this->db->bind(':posicion', $posicion);
            $this->db->bind(':id', $jugador->id);
            $this->db->execute();
            $posicion++;
        }
    }
}