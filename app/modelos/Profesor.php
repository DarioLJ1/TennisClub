<?php
namespace app\modelos;

use app\config\Database;

class Profesor {

    private $db;

    public function __construct(){

        $this->db = new Database;

    }

    public function obtenerProfesores(){

        $this->db->query('SELECT * FROM profesores ORDER BY nombre');
        return $this->db->resultSet();

    }

    public function obtenerProfesorPorId($id){

        $this->db->query('SELECT * FROM profesores WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();

    }

    public function agregarProfesor($datos){

        $this->db->query('INSERT INTO profesores (nombre, apellido, email, telefono, especialidad, nivel, precio_hora, disponible) 
                         VALUES(:nombre, :apellido, :email, :telefono, :especialidad, :nivel, :precio_hora, :disponible)');
        
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':apellido', $datos['apellido']);
        $this->db->bind(':email', $datos['email']);
        $this->db->bind(':telefono', $datos['telefono']);
        $this->db->bind(':especialidad', $datos['especialidad']);
        $this->db->bind(':nivel', $datos['nivel']);
        $this->db->bind(':precio_hora', $datos['precio_hora']);
        $this->db->bind(':disponible', $datos['disponible']);

        return $this->db->execute();

    }

    public function actualizarProfesor($datos){

        $this->db->query('UPDATE profesores 
                         SET nombre = :nombre, 
                             apellido = :apellido, 
                             email = :email, 
                             telefono = :telefono, 
                             especialidad = :especialidad, 
                             nivel = :nivel, 
                             precio_hora = :precio_hora, 
                             disponible = :disponible 
                         WHERE id = :id');
        
        $this->db->bind(':id', $datos['id']);
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':apellido', $datos['apellido']);
        $this->db->bind(':email', $datos['email']);
        $this->db->bind(':telefono', $datos['telefono']);
        $this->db->bind(':especialidad', $datos['especialidad']);
        $this->db->bind(':nivel', $datos['nivel']);
        $this->db->bind(':precio_hora', $datos['precio_hora']);
        $this->db->bind(':disponible', $datos['disponible']);

        return $this->db->execute();

    }

    public function eliminarProfesor($id){

        $this->db->query('DELETE FROM profesores WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();

    }

    public function obtenerHorarioProfesor($id_profesor){

        $this->db->query('SELECT * FROM horarios_profesor WHERE id_profesor = :id_profesor ORDER BY dia_semana, hora_inicio');
        $this->db->bind(':id_profesor', $id_profesor);
        return $this->db->resultSet();

    }

    public function agregarHorario($datos){

        $this->db->query('INSERT INTO horarios_profesor (id_profesor, dia_semana, hora_inicio, hora_fin) 
                         VALUES(:id_profesor, :dia_semana, :hora_inicio, :hora_fin)');
        
        $this->db->bind(':id_profesor', $datos['id_profesor']);
        $this->db->bind(':dia_semana', $datos['dia_semana']);
        $this->db->bind(':hora_inicio', $datos['hora_inicio']);
        $this->db->bind(':hora_fin', $datos['hora_fin']);

        return $this->db->execute();

    }

    public function eliminarHorario($id){

        $this->db->query('DELETE FROM horarios_profesor WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();

    }

    public function obtenerDisponibilidad($id_profesor, $fecha){

        $this->db->query('SELECT hp.*, 
                         NOT EXISTS (
                             SELECT 1 FROM clases_particulares cp 
                             WHERE cp.id_profesor = :id_profesor 
                             AND cp.fecha = :fecha 
                             AND cp.hora_inicio < hp.hora_fin 
                             AND cp.hora_fin > hp.hora_inicio
                         ) as disponible
                         FROM horarios_profesor hp 
                         WHERE hp.id_profesor = :id_profesor 
                         AND hp.dia_semana = WEEKDAY(:fecha) + 1
                         ORDER BY hp.hora_inicio');
        
        $this->db->bind(':id_profesor', $id_profesor);
        $this->db->bind(':fecha', $fecha);
        return $this->db->resultSet();

    }

    public function verificarDisponibilidadHorario($id_profesor, $fecha, $hora_inicio, $hora_fin) {

        $dia_semana = date('N', strtotime($fecha));
        
        $this->db->query('SELECT * FROM horarios_profesor 
                          WHERE id_profesor = :id_profesor 
                          AND dia_semana = :dia_semana 
                          AND hora_inicio <= :hora_inicio 
                          AND hora_fin >= :hora_fin');
        
        $this->db->bind(':id_profesor', $id_profesor);
        $this->db->bind(':dia_semana', $dia_semana);
        $this->db->bind(':hora_inicio', $hora_inicio);
        $this->db->bind(':hora_fin', $hora_fin);
        
        $resultado = $this->db->single();
        
        return $resultado ? true : false;

    }
    
}