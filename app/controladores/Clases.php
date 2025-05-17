<?php
namespace app\controladores;

use app\librerias\Controller;
use app\modelos\ClaseParticular;
use app\modelos\Profesor;
use app\utilidades\EmailUtil;

class Clases extends Controller {
    private $claseModelo;
    private $profesorModelo;
    private $emailUtil;

    public function __construct(){
        if(!isLoggedIn()){
            redirect('usuarios/login');
        }
        $this->claseModelo = $this->model('ClaseParticular');
        $this->profesorModelo = $this->model('Profesor');
        $this->emailUtil = new EmailUtil();
    }

    public function index() {
        $clases = $this->claseModelo->obtenerClasesPorUsuario($_SESSION['user_id']);
        $datos = [
            'clases' => $clases
        ];
        $this->view('clases/index', $datos);
    }

    private function enviarEmailConfirmacionClase($clase){
        try {
            $asunto = 'Confirmación de Reserva de Clase - HomeTennis';
            $cuerpo = $this->generarCuerpoEmailClase($clase);
            
            if($this->emailUtil->enviarEmail($_SESSION['user_email'], $asunto, $cuerpo)){
                error_log("Correo de confirmación enviado exitosamente para la clase ID: {$clase->id}");
                return true;
            } else {
                error_log("Error al enviar correo de confirmación para la clase ID: {$clase->id}");
                return false;
            }
        } catch (\Exception $e) {
            error_log("Excepción al enviar email: " . $e->getMessage());
            return false;
        }
    }

    private function generarCuerpoEmailClase($clase){
        // Calcular el precio según el tipo de clase
        $precio_base = $clase->tipo_clase == 'Individual' ? 30.00 : 45.00;
        $precio_total = $precio_base * $clase->num_alumnos;

        return "
            <h1>Confirmación de Reserva de Clase</h1>
            <p>Tu clase ha sido reservada con los siguientes detalles:</p>
            <ul>
                <li>Profesor: {$clase->nombre_profesor} {$clase->apellido_profesor}</li>
                <li>Fecha: " . date('d/m/Y', strtotime($clase->fecha)) . "</li>
                <li>Hora: " . substr($clase->hora_inicio, 0, 5) . " - " . substr($clase->hora_fin, 0, 5) . "</li>
                <li>Tipo de clase: {$clase->tipo_clase}</li>
                <li>Número de alumnos: {$clase->num_alumnos}</li>
                <li>Precio: {$precio_total}€</li>
            </ul>
            <p>Por favor, procede con el pago para confirmar tu reserva.</p>
            <p>Gracias por usar HomeTennis.</p>
        ";
    }

    public function reservar($id_profesor = null){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Obtener el profesor y sus horarios al inicio
            $profesor = $this->profesorModelo->obtenerProfesorPorId($id_profesor);
            $horarios = $this->profesorModelo->obtenerHorarioProfesor($id_profesor);

            // Establecer valores por defecto si no están presentes
            $tipo_clase = isset($_POST['tipo_clase']) ? trim($_POST['tipo_clase']) : 'Individual';
            $num_alumnos = isset($_POST['num_alumnos']) ? intval(trim($_POST['num_alumnos'])) : 1;
        
            // Validar y ajustar num_alumnos según el tipo de clase
            if ($tipo_clase === 'Individual') {
                $num_alumnos = 1;
            } else {
                $num_alumnos = max(1, min(4, $num_alumnos)); // Entre 1 y 4 alumnos para clases grupales
            }

            $datos = [
                'id_profesor' => $id_profesor,
                'profesor' => $profesor,  // Añadir el profesor a los datos
                'horarios' => $horarios,  // Añadir los horarios a los datos
                'id_usuario' => $_SESSION['user_id'],
                'fecha' => trim($_POST['fecha'] ?? ''),
                'hora_inicio' => trim($_POST['hora_inicio'] ?? ''),
                'hora_fin' => trim($_POST['hora_fin'] ?? ''),
                'tipo_clase' => $tipo_clase,
                'num_alumnos' => $num_alumnos,
                'notas' => trim($_POST['notas'] ?? ''),
                'fecha_err' => '',
                'hora_inicio_err' => '',
                'hora_fin_err' => '',
                'disponibilidad_err' => ''
            ];

            // Validar fecha y hora
            if(empty($datos['fecha'])){
                $datos['fecha_err'] = 'Por favor ingrese una fecha';
            } elseif(strtotime($datos['fecha']) < strtotime(date('Y-m-d'))){
                $datos['fecha_err'] = 'La fecha no puede ser anterior a hoy';
            }

            if(empty($datos['hora_inicio'])){
                $datos['hora_inicio_err'] = 'Por favor ingrese una hora de inicio';
            }

            if(empty($datos['hora_fin'])){
                $datos['hora_fin_err'] = 'Por favor ingrese una hora de fin';
            }

            // Verificar disponibilidad del profesor en su horario
            if(empty($datos['fecha_err']) && empty($datos['hora_inicio_err']) && empty($datos['hora_fin_err'])){
                if(!$this->profesorModelo->verificarDisponibilidadHorario(
                    $datos['id_profesor'],
                    $datos['fecha'],
                    $datos['hora_inicio'],
                    $datos['hora_fin']
                )){
                    $datos['disponibilidad_err'] = 'El profesor no tiene horario disponible en ese día y hora';
                    $this->view('clases/reservar', $datos);
                    return;
                }

                // Verificar si ya hay una clase reservada en ese horario
                if(!$this->claseModelo->verificarDisponibilidad(
                    $datos['id_profesor'],
                    $datos['fecha'],
                    $datos['hora_inicio'],
                    $datos['hora_fin']
                )){
                    $datos['disponibilidad_err'] = 'El profesor ya tiene una clase reservada en ese horario';
                    $this->view('clases/reservar', $datos);
                    return;
                }
            }

            // Asegurarse de que no hay errores
            if(empty($datos['fecha_err']) && empty($datos['hora_inicio_err']) && 
               empty($datos['hora_fin_err']) && empty($datos['disponibilidad_err'])){
            
                $id_clase = $this->claseModelo->crearClase($datos);
                if($id_clase){
                    // Obtener la clase con todos los detalles
                    $clase = $this->claseModelo->obtenerClasePorId($id_clase);
                
                    // Intentar enviar el email
                    if($this->enviarEmailConfirmacionClase($clase)){
                        flash('clase_mensaje', 'Clase reservada con éxito y correo de confirmación enviado', 'alert alert-success');
                    } else {
                        flash('clase_mensaje', 'Clase reservada con éxito pero hubo un problema al enviar el correo', 'alert alert-warning');
                    }
                
                    redirect('clases');
                } else {
                    flash('clase_mensaje', 'Error al crear la reserva', 'alert alert-danger');
                    $this->view('clases/reservar', $datos);
                }
            } else {
                $this->view('clases/reservar', $datos);
            }
        } else {
            $profesor = $this->profesorModelo->obtenerProfesorPorId($id_profesor);
            $horarios = $this->profesorModelo->obtenerHorarioProfesor($id_profesor);

            $datos = [
                'id_profesor' => $id_profesor,
                'profesor' => $profesor,
                'horarios' => $horarios,
                'fecha' => '',
                'hora_inicio' => '',
                'hora_fin' => '',
                'tipo_clase' => 'Individual',
                'num_alumnos' => 1,
                'notas' => '',
                'fecha_err' => '',
                'hora_inicio_err' => '',
                'hora_fin_err' => '',
                'disponibilidad_err' => ''
            ];

            $this->view('clases/reservar', $datos);
        }
    }

    public function cancelar($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $clase = $this->claseModelo->obtenerClasePorId($id);
            
            if($clase->id_usuario != $_SESSION['user_id']){
                redirect('clases');
            }

            if($this->claseModelo->cancelarClase($id)){
                flash('clase_mensaje', 'Clase cancelada correctamente');
            } else {
                flash('clase_mensaje', 'Error al cancelar la clase', 'alert alert-danger');
            }
            redirect('clases');
        } else {
            redirect('clases');
        }
    }

    public function valorar($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $datos = [
                'id_clase' => $id,
                'id_usuario' => $_SESSION['user_id'],
                'puntuacion' => trim($_POST['puntuacion']),
                'comentario' => trim($_POST['comentario'])
            ];

            if($this->claseModelo->agregarValoracion($datos)){
                flash('clase_mensaje', 'Valoración enviada correctamente');
                redirect('clases');
            } else {
                flash('clase_mensaje', 'Error al enviar la valoración', 'alert alert-danger');
                redirect('clases/valorar/' . $id);
            }
        } else {
            $clase = $this->claseModelo->obtenerClasePorId($id);
            
            if(!$clase || $clase->id_usuario != $_SESSION['user_id']){
                redirect('clases');
            }

            $datos = [
                'clase' => $clase
            ];

            $this->view('clases/valorar', $datos);
        }
    }
}