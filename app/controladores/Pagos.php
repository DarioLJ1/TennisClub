<?php
namespace app\controladores;

use app\librerias\Controller;
use app\modelos\Pago;
use app\modelos\Reserva;
use app\modelos\ClaseParticular;
use app\utilidades\EmailUtil;

class Pagos extends Controller {
    private $pagoModelo;
    private $reservaModelo;
    private $claseModelo;
    private $emailUtil;

    public function __construct() {
        if (!isLoggedIn()) {
            redirect('usuarios/login');
        }
        $this->pagoModelo = $this->model('Pago');
        $this->reservaModelo = $this->model('Reserva');
        $this->claseModelo = $this->model('ClaseParticular');
        $this->emailUtil = new EmailUtil();
    }

    // Añadimos el método index que faltaba
    public function index() {
        // Redirigir a la página principal si se accede directamente
        redirect('paginas/index');
    }

    public function procesar($id_reserva) {
        $reserva = $this->reservaModelo->obtenerReservaPorId($id_reserva);
        if (!$reserva) {
            flash('pago_mensaje', 'Reserva no encontrada', 'alert alert-danger');
            redirect('pistas');
        }

        // Calcular el precio basado en el tipo de reserva
        $precio = ($reserva->tipo_reserva == 'individual') ? 10 : 20;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $datos = [
                'id_reserva' => $id_reserva,
                'monto' => $precio,
                'metodo_pago' => $_POST['metodo_pago'] ?? '',
                'numero_tarjeta' => $_POST['numero_tarjeta'] ?? '',
                'fecha_expiracion' => $_POST['fecha_expiracion'] ?? '',
                'cvv' => $_POST['cvv'] ?? '',
                'estado' => 'completado'
            ];

            // Validar los campos requeridos
            if (empty($datos['metodo_pago'])) {
                flash('pago_mensaje', 'Por favor, seleccione un método de pago', 'alert alert-danger');
                return $this->view('pagos/procesar', $datos);
            }

            if ($datos['metodo_pago'] == 'tarjeta') {
                if (empty($datos['numero_tarjeta']) || empty($datos['fecha_expiracion']) || empty($datos['cvv'])) {
                    flash('pago_mensaje', 'Por favor, complete todos los campos de la tarjeta', 'alert alert-danger');
                    return $this->view('pagos/procesar', $datos);
                }
            } else {
                $datos['numero_tarjeta'] = null;
                $datos['fecha_expiracion'] = null;
                $datos['cvv'] = null;
            }

            if ($this->pagoModelo->registrarPago($datos)) {
                // Enviar correo de confirmación
                $asunto = 'Confirmación de Reserva y Pago - HomeTennis';
                $cuerpo = $this->generarCuerpoCorreo($reserva, $datos);
                
                if ($this->emailUtil->enviarEmail($_SESSION['user_email'], $asunto, $cuerpo)) {
                    flash('pago_mensaje', 'Pago realizado con éxito y correo de confirmación enviado', 'alert alert-success');
                    error_log("Correo de confirmación enviado exitosamente para la reserva ID: $id_reserva. Destinatario: {$_SESSION['user_email']}");
                } else {
                    flash('pago_mensaje', 'Pago realizado con éxito pero hubo un problema al enviar el correo de confirmación', 'alert alert-warning');
                    error_log("Error al enviar correo de confirmación para la reserva ID: $id_reserva. Destinatario: {$_SESSION['user_email']}");
                }
                redirect('pistas');
            } else {
                flash('pago_mensaje', 'Hubo un problema al procesar el pago', 'alert alert-danger');
                return $this->view('pagos/procesar', $datos);
            }
        } else {
            $datos = [
                'id_reserva' => $id_reserva,
                'monto' => $precio,
                'metodo_pago' => '',
                'numero_tarjeta' => '',
                'fecha_expiracion' => '',
                'cvv' => ''
            ];

            $this->view('pagos/procesar', $datos);
        }
    }

    public function procesarClase($id_clase) {
        $clase = $this->claseModelo->obtenerClasePorId($id_clase);
        if (!$clase) {
            flash('pago_mensaje', 'Clase no encontrada', 'alert alert-danger');
            redirect('clases');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $datos = [
                'id_clase' => $id_clase,
                'monto' => $clase->precio,
                'metodo_pago' => $_POST['metodo_pago'] ?? '',
                'numero_tarjeta' => $_POST['numero_tarjeta'] ?? '',
                'fecha_expiracion' => $_POST['fecha_expiracion'] ?? '',
                'cvv' => $_POST['cvv'] ?? '',
                'estado' => 'completado'
            ];

            // Validar los campos requeridos
            if (empty($datos['metodo_pago'])) {
                flash('pago_mensaje', 'Por favor, seleccione un método de pago', 'alert alert-danger');
                return $this->view('pagos/procesar_clase', $datos);
            }

            if ($datos['metodo_pago'] == 'tarjeta') {
                if (empty($datos['numero_tarjeta']) || empty($datos['fecha_expiracion']) || empty($datos['cvv'])) {
                    flash('pago_mensaje', 'Por favor, complete todos los campos de la tarjeta', 'alert alert-danger');
                    return $this->view('pagos/procesar_clase', $datos);
                }
            }

            if ($this->pagoModelo->registrarPago($datos)) {
                // Actualizar estado de la clase
                $this->claseModelo->confirmarClase($id_clase);
                
                flash('clase_mensaje', 'Pago realizado con éxito', 'alert alert-success');
                redirect('clases');
            } else {
                flash('pago_mensaje', 'Hubo un problema al procesar el pago', 'alert alert-danger');
                return $this->view('pagos/procesar_clase', $datos);
            }
        } else {
            $datos = [
                'id_clase' => $id_clase,
                'clase' => $clase,
                'monto' => $clase->precio,
                'metodo_pago' => '',
                'numero_tarjeta' => '',
                'fecha_expiracion' => '',
                'cvv' => ''
            ];

            $this->view('pagos/procesar_clase', $datos);
        }
    }

    private function generarCuerpoCorreo($reserva, $datosPago) {
        return "
            <h1>Confirmación de Reserva y Pago</h1>
            <p>Tu reserva ha sido confirmada y el pago procesado con éxito. Detalles:</p>
            <ul>
                <li>Pista: {$reserva->nombre_pista}</li>
                <li>Fecha: {$reserva->fecha}</li>
                <li>Hora de inicio: {$reserva->hora_inicio}</li>
                <li>Hora de fin: {$reserva->hora_fin}</li>
                <li>Tipo de reserva: {$reserva->tipo_reserva}</li>
                <li>Monto pagado: {$datosPago['monto']}€</li>
                <li>Método de pago: {$datosPago['metodo_pago']}</li>
            </ul>
            <p>Gracias por usar HomeTennis.</p>
        ";
    }
}