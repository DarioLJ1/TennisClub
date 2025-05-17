<?php
namespace app\modelos;

use app\config\Database;

class ClaseParticular {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function obtenerClases() {
        $this->db->query('SELECT cp.*, p.nombre as nombre_profesor, p.apellido as apellido_profesor, 
                         u.nombre as nombre_usuario, u.email as email_usuario
                         FROM clases_particulares cp 
                         JOIN profesores p ON cp.id_profesor = p.id 
                         JOIN usuarios u ON cp.id_usuario = u.id
                         ORDER BY cp.fecha DESC, cp.hora_inicio DESC');
        return $this->db->resultSet();
    }

    public function obtenerClasesPorUsuario($id_usuario) {
        $this->db->query('SELECT cp.*, p.nombre as nombre_profesor, p.apellido as apellido_profesor, 
                         p.email as email_profesor
                         FROM clases_particulares cp 
                         JOIN profesores p ON cp.id_profesor = p.id 
                         WHERE cp.id_usuario = :id_usuario 
                         ORDER BY cp.fecha DESC, cp.hora_inicio DESC');
        $this->db->bind(':id_usuario', $id_usuario);
        return $this->db->resultSet();
    }

    public function obtenerClasePorId($id) {
        $this->db->query('SELECT cp.*, p.nombre as nombre_profesor, p.apellido as apellido_profesor,
                         u.nombre as nombre_usuario, u.email as email_usuario
                         FROM clases_particulares cp 
                         JOIN profesores p ON cp.id_profesor = p.id 
                         JOIN usuarios u ON cp.id_usuario = u.id 
                         WHERE cp.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function crearClase($datos) {
        $this->db->query('INSERT INTO clases_particulares (id_profesor, id_usuario, fecha, hora_inicio, hora_fin, 
                         tipo_clase, num_alumnos, precio, estado, notas) 
                         VALUES (:id_profesor, :id_usuario, :fecha, :hora_inicio, :hora_fin, 
                         :tipo_clase, :num_alumnos, :precio, :estado, :notas)');

        // Vincular valores
        $this->db->bind(':id_profesor', $datos['id_profesor']);
        $this->db->bind(':id_usuario', $datos['id_usuario']);
        $this->db->bind(':fecha', $datos['fecha']);
        $this->db->bind(':hora_inicio', $datos['hora_inicio']);
        $this->db->bind(':hora_fin', $datos['hora_fin']);
        $this->db->bind(':tipo_clase', $datos['tipo_clase']);
        $this->db->bind(':num_alumnos', $datos['num_alumnos']);
        $this->db->bind(':precio', $datos['tipo_clase'] == 'Individual' ? 30.00 : 45.00); // Precio base según tipo
        $this->db->bind(':estado', 'Pendiente');
        $this->db->bind(':notas', $datos['notas'] ?? null);

        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function verificarDisponibilidad($id_profesor, $fecha, $hora_inicio, $hora_fin) {
        $this->db->query('SELECT COUNT(*) as count FROM clases_particulares 
                         WHERE id_profesor = :id_profesor 
                         AND fecha = :fecha 
                         AND ((hora_inicio <= :hora_inicio AND hora_fin > :hora_inicio) 
                         OR (hora_inicio < :hora_fin AND hora_fin >= :hora_fin)
                         OR (hora_inicio >= :hora_inicio AND hora_fin <= :hora_fin))
                         AND estado != "Cancelada"');

        $this->db->bind(':id_profesor', $id_profesor);
        $this->db->bind(':fecha', $fecha);
        $this->db->bind(':hora_inicio', $hora_inicio);
        $this->db->bind(':hora_fin', $hora_fin);

        $row = $this->db->single();
        return $row->count == 0;
    }

    public function actualizarEstado($id, $estado) {
        $this->db->query('UPDATE clases_particulares SET estado = :estado WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':estado', $estado);
        return $this->db->execute();
    }

    public function cancelarClase($id) {
        return $this->actualizarEstado($id, 'Cancelada');
    }

    public function confirmarClase($id) {
        return $this->actualizarEstado($id, 'Confirmada');
    }

    public function obtenerValoracion($id_clase) {
        $this->db->query('SELECT * FROM valoraciones_clases WHERE id_clase = :id_clase');
        $this->db->bind(':id_clase', $id_clase);
        return $this->db->single();
    }

    public function agregarValoracion($datos) {
        $this->db->query('INSERT INTO valoraciones_clases (id_clase, id_usuario, puntuacion, comentario) 
                         VALUES (:id_clase, :id_usuario, :puntuacion, :comentario)');
        $this->db->bind(':id_clase', $datos['id_clase']);
        $this->db->bind(':id_usuario', $datos['id_usuario']);
        $this->db->bind(':puntuacion', $datos['puntuacion']);
        $this->db->bind(':comentario', $datos['comentario']);
        return $this->db->execute();
    }

    // Métodos adicionales para informes PDF

    /**
     * Obtener clases por rango de fechas para informes
     * 
     * @param string $fecha_inicio Fecha de inicio en formato Y-m-d
     * @param string $fecha_fin Fecha de fin en formato Y-m-d
     * @param string $id_profesor ID del profesor (opcional)
     * @param string $estado Estado de la clase (opcional)
     * @return array Conjunto de clases
     */
    public function obtenerClasesPorFecha($fecha_inicio, $fecha_fin, $id_profesor = '', $estado = '') {
        $sql = 'SELECT cp.*, p.nombre as nombre_profesor, p.apellido as apellido_profesor, 
                u.nombre as nombre_usuario
                FROM clases_particulares cp 
                JOIN profesores p ON cp.id_profesor = p.id 
                JOIN usuarios u ON cp.id_usuario = u.id 
                WHERE cp.fecha BETWEEN :fecha_inicio AND :fecha_fin';
        
        if (!empty($id_profesor)) {
            $sql .= ' AND cp.id_profesor = :id_profesor';
        }
        
        if (!empty($estado)) {
            // Convertir el estado al formato de la base de datos (primera letra mayúscula)
            $estado = ucfirst(strtolower($estado));
            $sql .= ' AND cp.estado = :estado';
        }
        
        $sql .= ' ORDER BY cp.fecha, cp.hora_inicio';
        
        $this->db->query($sql);
        $this->db->bind(':fecha_inicio', $fecha_inicio);
        $this->db->bind(':fecha_fin', $fecha_fin);
        
        if (!empty($id_profesor)) {
            $this->db->bind(':id_profesor', $id_profesor);
        }
        
        if (!empty($estado)) {
            $this->db->bind(':estado', $estado);
        }
        
        $resultados = $this->db->resultSet();
        
        // Procesar los resultados para adaptarlos al formato esperado por el generador de informes
        foreach ($resultados as $clase) {
            // Combinar nombre y apellido del profesor
            $clase->nombre_profesor = $clase->nombre_profesor . ' ' . $clase->apellido_profesor;
            
            // Añadir campo estado en minúsculas si es necesario para compatibilidad
            if (isset($clase->estado)) {
                $clase->estado = strtolower($clase->estado);
            }
        }
        
        return $resultados;
    }

    /**
     * Calcular ingresos por rango de fechas para informes financieros
     * 
     * @param string $fecha_inicio Fecha de inicio en formato Y-m-d
     * @param string $fecha_fin Fecha de fin en formato Y-m-d
     * @return array Datos de cantidad e ingresos
     */
    public function calcularIngresosPorFecha($fecha_inicio, $fecha_fin) {
        $this->db->query('SELECT COUNT(*) as cantidad, COALESCE(SUM(precio), 0) as total 
                        FROM clases_particulares 
                        WHERE fecha BETWEEN :fecha_inicio AND :fecha_fin 
                        AND estado != "Cancelada"');
        $this->db->bind(':fecha_inicio', $fecha_inicio);
        $this->db->bind(':fecha_fin', $fecha_fin);
        
        $resultado = $this->db->single();
        
        return [
            'cantidad' => $resultado->cantidad ?? 0,
            'total' => $resultado->total ?? 0
        ];
    }
}