<?php
namespace app\modelos;

use app\config\Database;

class Reserva {
  private $db;

  public function __construct(){
      $this->db = new Database();
  }

  public function crearReserva($datos){
      $this->db->query('INSERT INTO reservas (id_usuario, id_pista, fecha, hora_inicio, hora_fin, tipo_reserva) VALUES(:id_usuario, :id_pista, :fecha, :hora_inicio, :hora_fin, :tipo_reserva)');
      $this->db->bind(':id_usuario', $datos['id_usuario']);
      $this->db->bind(':id_pista', $datos['id_pista']);
      $this->db->bind(':fecha', $datos['fecha']);
      $this->db->bind(':hora_inicio', $datos['hora_inicio']);
      $this->db->bind(':hora_fin', $datos['hora_fin']);
      $this->db->bind(':tipo_reserva', $datos['tipo_reserva']);

      if($this->db->execute()){
          $this->actualizarEstadisticas($datos['id_pista'], $datos['fecha'], $datos['hora_inicio'], $datos['hora_fin']);
          return $this->db->lastInsertId();
      } else {
          return false;
      }
  }

  public function obtenerReservasPorUsuario($id_usuario){
      $this->db->query('SELECT r.*, p.nombre as nombre_pista FROM reservas r JOIN pistas p ON r.id_pista = p.id WHERE r.id_usuario = :id_usuario ORDER BY r.fecha, r.hora_inicio');
      $this->db->bind(':id_usuario', $id_usuario);
      return $this->db->resultSet();
  }

  public function obtenerReservaPorId($id){
      $this->db->query('SELECT r.*, p.nombre as nombre_pista, r.tipo_reserva FROM reservas r JOIN pistas p ON r.id_pista = p.id WHERE r.id = :id');
      $this->db->bind(':id', $id);
      return $this->db->single();
  }

  public function obtenerHistorialReservas($id_usuario){
    $this->db->query('SELECT r.*, p.nombre as nombre_pista FROM reservas r JOIN pistas p ON r.id_pista = p.id WHERE r.id_usuario = :id_usuario ORDER BY r.fecha DESC, r.hora_inicio DESC');
    $this->db->bind(':id_usuario', $id_usuario);
    return $this->db->resultSet();
  }

  private function actualizarEstadisticas($id_pista, $fecha, $hora_inicio, $hora_fin){
      $inicio = new \DateTime($hora_inicio);
      $fin = new \DateTime($hora_fin);
      $diferencia = $fin->diff($inicio);
      $horas_uso = $diferencia->h + ($diferencia->i / 60);

      error_log("Actualizando estadísticas - Pista: $id_pista, Fecha: $fecha");
      error_log("Hora inicio: $hora_inicio, Hora fin: $hora_fin");
      error_log("Horas calculadas: $horas_uso");

      $this->db->query('SELECT * FROM estadisticas_uso WHERE id_pista = :id_pista AND fecha = :fecha');
      $this->db->bind(':id_pista', $id_pista);
      $this->db->bind(':fecha', $fecha);
      $resultado = $this->db->single();

      if($resultado){
          $this->db->query('UPDATE estadisticas_uso SET horas_uso = horas_uso + :horas_uso, num_reservas = num_reservas + 1 WHERE id = :id');
          $this->db->bind(':horas_uso', $horas_uso);
          $this->db->bind(':id', $resultado->id);
          error_log("Actualizando registro existente ID: " . $resultado->id);
      } else {
          $this->db->query('INSERT INTO estadisticas_uso (id_pista, fecha, horas_uso, num_reservas) VALUES (:id_pista, :fecha, :horas_uso, 1)');
          $this->db->bind(':id_pista', $id_pista);
          $this->db->bind(':fecha', $fecha);
          $this->db->bind(':horas_uso', $horas_uso);
      }

      $result = $this->db->execute();
      error_log("Resultado de la operación: " . ($result ? "éxito" : "fallo"));
      return $result;
  }

  public function obtenerEstadisticasPorPista($id_pista, $fecha_inicio, $fecha_fin){
      $this->db->query('SELECT e.*, p.nombre as nombre_pista FROM estadisticas_uso e JOIN pistas p ON e.id_pista = p.id WHERE e.id_pista = :id_pista AND e.fecha BETWEEN :fecha_inicio AND :fecha_fin');
      $this->db->bind(':id_pista', $id_pista);
      $this->db->bind(':fecha_inicio', $fecha_inicio);
      $this->db->bind(':fecha_fin', $fecha_fin);
      
      $resultado = $this->db->single();
      if (!$resultado) {
          $pista = $this->db->query('SELECT nombre FROM pistas WHERE id = :id_pista');
          $this->db->bind(':id_pista', $id_pista);
          $pista = $this->db->single();
          
          return (object)[
              'nombre_pista' => $pista->nombre,
              'total_horas' => 0,
              'total_reservas' => 0
          ];
      }

      return $resultado;
  }

  public function obtenerEstadisticasTodasPistas($fecha_inicio, $fecha_fin){
      $this->db->query('SELECT p.nombre, COALESCE(SUM(e.horas_uso), 0) as total_horas, COALESCE(SUM(e.num_reservas), 0) as total_reservas FROM pistas p LEFT JOIN estadisticas_uso e ON p.id = e.id_pista AND e.fecha BETWEEN :fecha_inicio AND :fecha_fin GROUP BY p.id, p.nombre');
      $this->db->bind(':fecha_inicio', $fecha_inicio);
      $this->db->bind(':fecha_fin', $fecha_fin);
      return $this->db->resultSet();
  }

  // Métodos adicionales para informes PDF

  /**
   * Obtener reservas por rango de fechas para informes
   * 
   * @param string $fecha_inicio Fecha de inicio en formato Y-m-d
   * @param string $fecha_fin Fecha de fin en formato Y-m-d
   * @param string $estado Estado de la reserva (opcional)
   * @return array Conjunto de reservas
   */
  public function obtenerReservasPorFecha($fecha_inicio, $fecha_fin, $estado = '') {
      $sql = 'SELECT r.*, p.nombre as nombre_pista, u.nombre as nombre_usuario 
              FROM reservas r 
              LEFT JOIN pistas p ON r.id_pista = p.id 
              LEFT JOIN usuarios u ON r.id_usuario = u.id 
              WHERE r.fecha BETWEEN :fecha_inicio AND :fecha_fin';
      
      // Verificar si la columna estado existe
      $this->db->query("SHOW COLUMNS FROM reservas LIKE 'estado'");
      $tieneEstado = $this->db->rowCount() > 0;
      
      if ($tieneEstado && !empty($estado)) {
          $sql .= ' AND r.estado = :estado';
      }
      
      $sql .= ' ORDER BY r.fecha, r.hora_inicio';
      
      $this->db->query($sql);
      $this->db->bind(':fecha_inicio', $fecha_inicio);
      $this->db->bind(':fecha_fin', $fecha_fin);
      
      if ($tieneEstado && !empty($estado)) {
          $this->db->bind(':estado', $estado);
      }
      
      return $this->db->resultSet();
  }

  /**
   * Calcular ingresos por rango de fechas para informes financieros
   * 
   * @param string $fecha_inicio Fecha de inicio en formato Y-m-d
   * @param string $fecha_fin Fecha de fin en formato Y-m-d
   * @return array Datos de cantidad e ingresos
   */
  public function calcularIngresosPorFecha($fecha_inicio, $fecha_fin) {
      // Verificar si la columna precio existe
      $this->db->query("SHOW COLUMNS FROM reservas LIKE 'precio'");
      $tienePrecio = $this->db->rowCount() > 0;
      
      if ($tienePrecio) {
          $this->db->query('SELECT COUNT(*) as cantidad, COALESCE(SUM(precio), 0) as total 
                          FROM reservas 
                          WHERE fecha BETWEEN :fecha_inicio AND :fecha_fin');
      } else {
          // Si no hay columna precio, asignar un precio fijo para el cálculo
          $this->db->query('SELECT COUNT(*) as cantidad, COUNT(*) * 10 as total 
                          FROM reservas 
                          WHERE fecha BETWEEN :fecha_inicio AND :fecha_fin');
      }
      
      $this->db->bind(':fecha_inicio', $fecha_inicio);
      $this->db->bind(':fecha_fin', $fecha_fin);
      
      $resultado = $this->db->single();
      
      return [
          'cantidad' => $resultado->cantidad ?? 0,
          'total' => $resultado->total ?? 0
      ];
  }

  /**
   * Obtener reservas con paginación
   * 
   * @param int $id_usuario ID del usuario (opcional)
   * @param int $limite Número de registros por página
   * @param int $offset Número de registros a saltar
   * @return array Conjunto de reservas
   */
  public function obtenerReservasPaginadas($id_usuario = null, $limite = 10, $offset = 0) {
      $sql = 'SELECT r.*, p.nombre as nombre_pista, p.tipo as tipo_pista 
              FROM reservas r 
              LEFT JOIN pistas p ON r.id_pista = p.id';
      
      if ($id_usuario) {
          $sql .= ' WHERE r.id_usuario = :id_usuario';
      }
      
      $sql .= ' ORDER BY r.fecha DESC, r.hora_inicio DESC LIMIT :limite OFFSET :offset';
      
      $this->db->query($sql);
      
      if ($id_usuario) {
          $this->db->bind(':id_usuario', $id_usuario);
      }
      
      $this->db->bind(':limite', $limite, \PDO::PARAM_INT);
      $this->db->bind(':offset', $offset, \PDO::PARAM_INT);
      
      return $this->db->resultSet();
  }

  /**
   * Contar el total de reservas
   * 
   * @param int $id_usuario ID del usuario (opcional)
   * @return int Total de reservas
   */
  public function contarReservas($id_usuario = null) {
      $sql = 'SELECT COUNT(*) as total FROM reservas';
      
      if ($id_usuario) {
          $sql .= ' WHERE id_usuario = :id_usuario';
      }
      
      $this->db->query($sql);
      
      if ($id_usuario) {
          $this->db->bind(':id_usuario', $id_usuario);
      }
      
      $resultado = $this->db->single();
      return $resultado->total;
  }
}