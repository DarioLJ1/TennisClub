<?php
namespace app\modelos;

use app\config\Database;

class Torneo {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function obtenerTorneos() {
        $this->db->query('SELECT * FROM torneos ORDER BY fecha_inicio');
        return $this->db->resultSet();
    }

    public function obtenerTorneosActivos() {
        $this->db->query('SELECT * FROM torneos WHERE estado = "abierto" OR estado = "programado" ORDER BY fecha_inicio');
        return $this->db->resultSet();
    }

    public function obtenerTorneoPorId($id) {
        $this->db->query('SELECT * FROM torneos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function crearTorneo($datos) {
        // Primero verificamos la estructura de la columna estado
        $this->db->query('SHOW COLUMNS FROM torneos LIKE "estado"');
        $estadoInfo = $this->db->single();
        
        // Extraemos los valores permitidos del ENUM
        $enumValues = [];
        if ($estadoInfo && strpos($estadoInfo->Type, 'enum') === 0) {
            preg_match('/enum$$(.*)$$/', $estadoInfo->Type, $matches);
            if (isset($matches[1])) {
                $enumStr = $matches[1];
                $enumValues = array_map(function($val) {
                    return trim($val, "'\"");
                }, explode(',', $enumStr));
            }
        }
        
        // Aseguramos que el estado sea válido
        $estado = $datos['estado'];
        if (!empty($enumValues) && !in_array($estado, $enumValues)) {
            // Si el estado no es válido, usamos el primer valor del ENUM
            $estado = $enumValues[0];
        }
        
        // Ahora procedemos con la inserción
        $this->db->query('INSERT INTO torneos (nombre, fecha_inicio, fecha_fin, descripcion, capacidad, estado, tipo, nivel, precio_inscripcion) 
                         VALUES (:nombre, :fecha_inicio, :fecha_fin, :descripcion, :capacidad, :estado, :tipo, :nivel, :precio_inscripcion)');
        
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':fecha_inicio', $datos['fecha_inicio']);
        $this->db->bind(':fecha_fin', $datos['fecha_fin']);
        $this->db->bind(':descripcion', $datos['descripcion']);
        $this->db->bind(':capacidad', $datos['capacidad']);
        $this->db->bind(':estado', $estado);
        $this->db->bind(':tipo', $datos['tipo']);
        $this->db->bind(':nivel', $datos['nivel']);
        $this->db->bind(':precio_inscripcion', $datos['precio_inscripcion']);

        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function actualizarTorneo($datos) {
        // Verificamos si las columnas existen antes de incluirlas en la consulta
        $this->db->query('SHOW COLUMNS FROM torneos LIKE "tipo"');
        $tipoExiste = $this->db->rowCount() > 0;
        
        $this->db->query('SHOW COLUMNS FROM torneos LIKE "nivel"');
        $nivelExiste = $this->db->rowCount() > 0;
        
        $this->db->query('SHOW COLUMNS FROM torneos LIKE "precio_inscripcion"');
        $precioExiste = $this->db->rowCount() > 0;
        
        // Verificamos la estructura de la columna estado
        $this->db->query('SHOW COLUMNS FROM torneos LIKE "estado"');
        $estadoInfo = $this->db->single();
        
        // Extraemos los valores permitidos del ENUM
        $enumValues = [];
        if ($estadoInfo && strpos($estadoInfo->Type, 'enum') === 0) {
            preg_match('/enum$$(.*)$$/', $estadoInfo->Type, $matches);
            if (isset($matches[1])) {
                $enumStr = $matches[1];
                $enumValues = array_map(function($val) {
                    return trim($val, "'\"");
                }, explode(',', $enumStr));
            }
        }
        
        // Aseguramos que el estado sea válido
        $estado = $datos['estado'];
        if (!empty($enumValues) && !in_array($estado, $enumValues)) {
            // Si el estado no es válido, usamos el primer valor del ENUM
            $estado = $enumValues[0];
        }
        
        // Construimos la consulta SQL según las columnas existentes
        $sql = 'UPDATE torneos SET nombre = :nombre, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin, 
                descripcion = :descripcion, capacidad = :capacidad, estado = :estado';
        
        if ($tipoExiste) $sql .= ', tipo = :tipo';
        if ($nivelExiste) $sql .= ', nivel = :nivel';
        if ($precioExiste) $sql .= ', precio_inscripcion = :precio_inscripcion';
        
        $sql .= ' WHERE id = :id';
        
        $this->db->query($sql);
        $this->db->bind(':id', $datos['id']);
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':fecha_inicio', $datos['fecha_inicio']);
        $this->db->bind(':fecha_fin', $datos['fecha_fin']);
        $this->db->bind(':descripcion', $datos['descripcion']);
        $this->db->bind(':capacidad', $datos['capacidad']);
        $this->db->bind(':estado', $estado);
        
        if ($tipoExiste) $this->db->bind(':tipo', $datos['tipo'] ?? 'Individual');
        if ($nivelExiste) $this->db->bind(':nivel', $datos['nivel'] ?? 'Todos');
        if ($precioExiste) $this->db->bind(':precio_inscripcion', $datos['precio_inscripcion'] ?? 0.00);

        return $this->db->execute();
    }

    public function eliminarTorneo($id) {
        // Primero eliminamos las inscripciones asociadas
        $this->db->query('DELETE FROM inscripciones_torneos WHERE id_torneo = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
        
        // Luego eliminamos el torneo
        $this->db->query('DELETE FROM torneos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function actualizarEstadoTorneo($id, $estado) {
        // Verificamos la estructura de la columna estado
        $this->db->query('SHOW COLUMNS FROM torneos LIKE "estado"');
        $estadoInfo = $this->db->single();
        
        // Extraemos los valores permitidos del ENUM
        $enumValues = [];
        if ($estadoInfo && strpos($estadoInfo->Type, 'enum') === 0) {
            preg_match('/enum$$(.*)$$/', $estadoInfo->Type, $matches);
            if (isset($matches[1])) {
                $enumStr = $matches[1];
                $enumValues = array_map(function($val) {
                    return trim($val, "'\"");
                }, explode(',', $enumStr));
            }
        }
        
        // Aseguramos que el estado sea válido
        if (!empty($enumValues) && !in_array($estado, $enumValues)) {
            // Si el estado no es válido, usamos el primer valor del ENUM
            $estado = $enumValues[0];
        }
        
        $this->db->query('UPDATE torneos SET estado = :estado WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':estado', $estado);
        return $this->db->execute();
    }
    
    // Método para verificar si hay cupo disponible en un torneo
    public function verificarCupoDisponible($id_torneo) {
        // Obtenemos la capacidad del torneo
        $this->db->query('SELECT capacidad FROM torneos WHERE id = :id');
        $this->db->bind(':id', $id_torneo);
        $torneo = $this->db->single();
        
        if (!$torneo) {
            return false;
        }
        
        // Contamos las inscripciones actuales
        $this->db->query('SELECT COUNT(*) as total FROM inscripciones_torneos WHERE id_torneo = :id_torneo');
        $this->db->bind(':id_torneo', $id_torneo);
        $resultado = $this->db->single();
        
        // Verificamos si hay cupo disponible
        return ($resultado->total < $torneo->capacidad);
    }

    /**
     * Obtener torneos por rango de fechas para informes
     * 
     * @param string $fecha_inicio Fecha de inicio en formato Y-m-d
     * @param string $fecha_fin Fecha de fin en formato Y-m-d
     * @param string $estado Estado del torneo (opcional)
     * @return array Conjunto de torneos
     */
    public function obtenerTorneosPorFecha($fecha_inicio, $fecha_fin, $estado = '') {
        $sql = 'SELECT t.* FROM torneos t 
                WHERE (t.fecha_inicio BETWEEN :fecha_inicio AND :fecha_fin 
                OR t.fecha_fin BETWEEN :fecha_inicio AND :fecha_fin
                OR (:fecha_inicio BETWEEN t.fecha_inicio AND t.fecha_fin))';
        
        if (!empty($estado)) {
            $sql .= ' AND t.estado = :estado';
        }
        
        $sql .= ' ORDER BY t.fecha_inicio';
        
        $this->db->query($sql);
        $this->db->bind(':fecha_inicio', $fecha_inicio);
        $this->db->bind(':fecha_fin', $fecha_fin);
        
        if (!empty($estado)) {
            $this->db->bind(':estado', $estado);
        }
        
        return $this->db->resultSet();
    }
}