<?php
namespace app\controladores;

use app\librerias\Controller;
use app\modelos\Reserva;
use app\modelos\Pista;
use app\utilidades\EmailUtil;

class Reservas extends Controller {

    private $reservaModelo;
    private $pistaModelo;
    private $emailUtil;

    public function __construct(){

        if(!isLoggedIn()){
            redirect('usuarios/login');
        }

        $this->reservaModelo = $this->model('Reserva');
        $this->pistaModelo = $this->model('Pista');
        $this->emailUtil = new EmailUtil();

    }

    public function index(){

        $reservas = $this->reservaModelo->obtenerReservasPorUsuario($_SESSION['user_id']);
        
        $datos = [

            'reservas' => $reservas

        ];

        $this->view('reservas/index', $datos);
    }

    public function crear($id_pista = null){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $datos = [

                'id_pista' => $id_pista,
                'fecha' => trim($_POST['fecha']),
                'hora_inicio' => trim($_POST['hora_inicio']),
                'hora_fin' => trim($_POST['hora_fin']),
                'tipo_reserva' => trim($_POST['tipo_reserva']),
                'id_usuario' => $_SESSION['user_id'],
                'fecha_err' => '',
                'hora_inicio_err' => '',
                'hora_fin_err' => ''

            ];

            
            if(empty($datos['fecha'])){

                $datos['fecha_err'] = 'Por favor ingrese una fecha';

            }
            if(empty($datos['hora_inicio'])){

                $datos['hora_inicio_err'] = 'Por favor ingrese una hora de inicio';

            }
            if(empty($datos['hora_fin'])){

                $datos['hora_fin_err'] = 'Por favor ingrese una hora de fin';

            }

            
            if(empty($datos['fecha_err']) && empty($datos['hora_inicio_err']) && empty($datos['hora_fin_err'])){
                
                if($id_reserva = $this->reservaModelo->crearReserva($datos)){
                    
                    $pista = $this->pistaModelo->obtenerPistaPorId($datos['id_pista']);
                    $asunto = 'Confirmación de Reserva - HomeTennis';
                    $cuerpo = "
                        <h1>Confirmación de Reserva</h1>
                        <p>Tu reserva ha sido confirmada con los siguientes detalles:</p>
                        <ul>
                            <li>Pista: {$pista->nombre}</li>
                            <li>Fecha: {$datos['fecha']}</li>
                            <li>Hora de inicio: {$datos['hora_inicio']}</li>
                            <li>Hora de fin: {$datos['hora_fin']}</li>
                            <li>Tipo de reserva: {$datos['tipo_reserva']}</li>
                        </ul>
                        <p>Gracias por usar HomeTennis.</p>
                    ";

                    error_log("Intentando enviar correo a: {$_SESSION['user_email']}");

                    if($this->emailUtil->enviarEmail($_SESSION['user_email'], $asunto, $cuerpo)){

                        flash('reserva_mensaje', 'Reserva creada con éxito y correo enviado');
                        error_log("Correo enviado exitosamente a: {$_SESSION['user_email']}");

                    } else {

                        flash('reserva_mensaje', 'Reserva creada con éxito pero hubo un problema al enviar el correo', 'alert alert-warning');
                        error_log("Error al enviar correo a: {$_SESSION['user_email']}");
                    }

                    redirect('reservas');

                } else {

                    die('Algo salió mal al crear la reserva');

                }
            } else {
                
                $this->view('reservas/crear', $datos);

            }

        } else {

            $pista = $this->pistaModelo->obtenerPistaPorId($id_pista);

            $datos = [

                'id_pista' => $id_pista,
                'pista' => $pista,
                'fecha' => '',
                'hora_inicio' => '',
                'hora_fin' => '',
                'tipo_reserva' => '',
                'fecha_err' => '',
                'hora_inicio_err' => '',
                'hora_fin_err' => ''

            ];

            $this->view('reservas/crear', $datos);
        }
    }

    public function editar($id){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $datos = [

                'id' => $id,
                'fecha' => trim($_POST['fecha']),
                'hora_inicio' => trim($_POST['hora_inicio']),
                'hora_fin' => trim($_POST['hora_fin']),
                'tipo_reserva' => trim($_POST['tipo_reserva']),
                'fecha_err' => '',
                'hora_inicio_err' => '',
                'hora_fin_err' => ''

            ];

            
            if(empty($datos['fecha'])){

                $datos['fecha_err'] = 'Por favor ingrese una fecha';

            }
            if(empty($datos['hora_inicio'])){

                $datos['hora_inicio_err'] = 'Por favor ingrese una hora de inicio';

            }
            if(empty($datos['hora_fin'])){

                $datos['hora_fin_err'] = 'Por favor ingrese una hora de fin';

            }

            
            if(empty($datos['fecha_err']) && empty($datos['hora_inicio_err']) && empty($datos['hora_fin_err'])){

                
                if($this->reservaModelo->actualizarReserva($datos)){

                    flash('reserva_mensaje', 'Reserva actualizada con éxito');
                    redirect('reservas');

                } else {

                    die('Algo salió mal');

                }
            } else {
                
                $this->view('reservas/editar', $datos);
            }

        } else {
            
            $reserva = $this->reservaModelo->obtenerReservaPorId($id);

            
            if($reserva->id_usuario != $_SESSION['user_id']){

                redirect('reservas');

            }

            $datos = [

                'id' => $id,
                'fecha' => $reserva->fecha,
                'hora_inicio' => $reserva->hora_inicio,
                'hora_fin' => $reserva->hora_fin,
                'tipo_reserva' => $reserva->tipo_reserva,
                'fecha_err' => '',
                'hora_inicio_err' => '',
                'hora_fin_err' => ''

            ];

            $this->view('reservas/editar', $datos);
        }
    }

    public function eliminar($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            $reserva = $this->reservaModelo->obtenerReservaPorId($id);

            if($reserva->id_usuario != $_SESSION['user_id']){
                redirect('reservas');
            }

            if($this->reservaModelo->eliminarReserva($id)){

                flash('reserva_mensaje', 'Reserva eliminada');
                redirect('reservas');

            } else {

                die('Algo salió mal');

            }
        } else {

            redirect('reservas');

        }
    }

    /**
     * Muestra el historial de reservas con paginación
     */
    public function historial() {
        // Obtener parámetros de paginación
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 10; // Número de reservas por página
        $offset = ($paginaActual - 1) * $porPagina;

        // Obtener el ID del usuario actual
        $id_usuario = $_SESSION['user_id'];

        // Obtener el total de reservas para calcular la paginación
        $totalReservas = $this->reservaModelo->contarReservas($id_usuario);
        $totalPaginas = ceil($totalReservas / $porPagina);

        // Asegurarse de que la página actual es válida
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $totalPaginas && $totalPaginas > 0) {
            $paginaActual = $totalPaginas;
        }

        // Obtener las reservas para la página actual
        $reservas = $this->reservaModelo->obtenerReservasPaginadas($id_usuario, $porPagina, $offset);

        // Preparar datos para la vista
        $datos = [
            'reservas' => $reservas,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $totalPaginas,
            'totalReservas' => $totalReservas,
            'porPagina' => $porPagina
        ];

        // Cargar vista
        $this->view('reservas/historial', $datos);
    }
}