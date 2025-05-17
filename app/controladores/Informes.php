<?php
namespace app\controladores;

use app\librerias\Controller;
use app\modelos\Reserva;
use app\modelos\ClaseParticular;
use app\modelos\Torneo;
use app\modelos\InscripcionTorneo;
use app\modelos\Usuario;

class Informes extends Controller {
    private $reservaModelo;
    private $claseModelo;
    private $torneoModelo;
    private $inscripcionModelo;
    private $usuarioModelo;

    public function __construct() {
        // Verificar si el usuario está logueado y es administrador
        if (!isLoggedIn() || !isAdmin()) {
            redirect('usuarios/login');
        }

        $this->reservaModelo = $this->model('Reserva');
        $this->claseModelo = $this->model('ClaseParticular');
        $this->torneoModelo = $this->model('Torneo');
        $this->inscripcionModelo = $this->model('InscripcionTorneo');
        $this->usuarioModelo = $this->model('Usuario');
    }

    /**
     * Página principal de informes
     */
    public function index() {
        $this->view('informes/index');
    }

    /**
     * Generar informe de reservas
     */
    public function reservas() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar el formulario
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $fecha_inicio = trim($_POST['fecha_inicio']);
            $fecha_fin = trim($_POST['fecha_fin']);
            $estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
            $formato = isset($_POST['formato']) ? trim($_POST['formato']) : 'pdf';
            
            // Validar fechas
            if (empty($fecha_inicio) || empty($fecha_fin)) {
                flash('informe_mensaje', 'Por favor, seleccione un rango de fechas válido', 'alert alert-danger');
                redirect('informes/reservas');
            }
            
            // Obtener reservas según los filtros
            $reservas = $this->reservaModelo->obtenerReservasPorFecha($fecha_inicio, $fecha_fin, $estado);
            
            if (empty($reservas)) {
                flash('informe_mensaje', 'No hay reservas para el período seleccionado', 'alert alert-warning');
                redirect('informes/reservas');
            }
            
            // Generar el informe
            $titulo = 'Informe de Reservas: ' . date('d/m/Y', strtotime($fecha_inicio)) . ' - ' . date('d/m/Y', strtotime($fecha_fin));
            
            if ($formato == 'pdf') {
                // Cargar el helper de PDF
                require_once APPROOT . '/helpers/pdf_helper.php';
                
                // Generar el PDF
                generarInformeReservas($reservas, $titulo);
                exit;
            } else {
                // Exportar a CSV
                $filename = 'informe_reservas_' . date('Ymd') . '.csv';
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                
                $output = fopen('php://output', 'w');
                
                // Encabezados CSV
                fputcsv($output, ['ID', 'Usuario', 'Pista', 'Fecha', 'Hora Inicio', 'Hora Fin', 'Estado', 'Precio']);
                
                // Datos
                foreach ($reservas as $reserva) {
                    fputcsv($output, [
                        $reserva->id,
                        $reserva->nombre_usuario,
                        $reserva->nombre_pista,
                        $reserva->fecha,
                        $reserva->hora_inicio,
                        $reserva->hora_fin,
                        $reserva->estado,
                        $reserva->precio
                    ]);
                }
                
                fclose($output);
                exit;
            }
        } else {
            // Mostrar formulario
            $datos = [
                'fecha_inicio' => date('Y-m-01'), // Primer día del mes actual
                'fecha_fin' => date('Y-m-t')      // Último día del mes actual
            ];
            
            $this->view('informes/reservas', $datos);
        }
    }

    /**
     * Generar informe de clases
     */
    public function clases() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar el formulario
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $fecha_inicio = trim($_POST['fecha_inicio']);
            $fecha_fin = trim($_POST['fecha_fin']);
            $id_profesor = isset($_POST['id_profesor']) ? trim($_POST['id_profesor']) : '';
            $estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
            $formato = isset($_POST['formato']) ? trim($_POST['formato']) : 'pdf';
            
            // Validar fechas
            if (empty($fecha_inicio) || empty($fecha_fin)) {
                flash('informe_mensaje', 'Por favor, seleccione un rango de fechas válido', 'alert alert-danger');
                redirect('informes/clases');
            }
            
            // Obtener clases según los filtros
            $clases = $this->claseModelo->obtenerClasesPorFecha($fecha_inicio, $fecha_fin, $id_profesor, $estado);
            
            if (empty($clases)) {
                flash('informe_mensaje', 'No hay clases para el período seleccionado', 'alert alert-warning');
                redirect('informes/clases');
            }
            
            // Generar el informe
            $titulo = 'Informe de Clases: ' . date('d/m/Y', strtotime($fecha_inicio)) . ' - ' . date('d/m/Y', strtotime($fecha_fin));
            
            if ($formato == 'pdf') {
                // Cargar el helper de PDF
                require_once APPROOT . '/helpers/pdf_helper.php';
                
                // Generar el PDF
                generarInformeClases($clases, $titulo);
                exit;
            } else {
                // Exportar a CSV
                $filename = 'informe_clases_' . date('Ymd') . '.csv';
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                
                $output = fopen('php://output', 'w');
                
                // Encabezados CSV
                fputcsv($output, ['ID', 'Usuario', 'Profesor', 'Pista', 'Fecha', 'Hora Inicio', 'Hora Fin', 'Estado', 'Precio']);
                
                // Datos
                foreach ($clases as $clase) {
                    fputcsv($output, [
                        $clase->id,
                        $clase->nombre_usuario,
                        $clase->nombre_profesor,
                        isset($clase->nombre_pista) ? $clase->nombre_pista : 'N/A',
                        $clase->fecha,
                        $clase->hora_inicio,
                        $clase->hora_fin,
                        $clase->estado,
                        $clase->precio
                    ]);
                }
                
                fclose($output);
                exit;
            }
        } else {
            // Obtener lista de profesores para el filtro
            $profesores = $this->model('Profesor')->obtenerProfesores();
            
            // Mostrar formulario
            $datos = [
                'fecha_inicio' => date('Y-m-01'), // Primer día del mes actual
                'fecha_fin' => date('Y-m-t'),     // Último día del mes actual
                'profesores' => $profesores
            ];
            
            $this->view('informes/clases', $datos);
        }
    }

    /**
     * Generar informe de torneos
     */
    public function torneos() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar el formulario
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $fecha_inicio = trim($_POST['fecha_inicio']);
            $fecha_fin = trim($_POST['fecha_fin']);
            $estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
            $formato = isset($_POST['formato']) ? trim($_POST['formato']) : 'pdf';
            
            // Validar fechas
            if (empty($fecha_inicio) || empty($fecha_fin)) {
                flash('informe_mensaje', 'Por favor, seleccione un rango de fechas válido', 'alert alert-danger');
                redirect('informes/torneos');
            }
            
            // Obtener torneos según los filtros
            $torneos = $this->torneoModelo->obtenerTorneosPorFecha($fecha_inicio, $fecha_fin, $estado);
            
            if (empty($torneos)) {
                flash('informe_mensaje', 'No hay torneos para el período seleccionado', 'alert alert-warning');
                redirect('informes/torneos');
            }
            
            // Obtener inscripciones para cada torneo
            $inscripciones = [];
            foreach ($torneos as $torneo) {
                $inscripciones[$torneo->id] = $this->inscripcionModelo->obtenerInscripcionesPorTorneo($torneo->id);
            }
            
            // Generar el informe
            $titulo = 'Informe de Torneos: ' . date('d/m/Y', strtotime($fecha_inicio)) . ' - ' . date('d/m/Y', strtotime($fecha_fin));
            
            if ($formato == 'pdf') {
                // Cargar el helper de PDF
                require_once APPROOT . '/helpers/pdf_helper.php';
                
                // Generar el PDF
                generarInformeTorneos($torneos, $inscripciones, $titulo);
                exit;
            } else {
                // Exportar a CSV
                $filename = 'informe_torneos_' . date('Ymd') . '.csv';
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                
                $output = fopen('php://output', 'w');
                
                // Encabezados CSV
                fputcsv($output, ['ID', 'Nombre', 'Fecha Inicio', 'Fecha Fin', 'Capacidad', 'Inscritos', 'Estado', 'Tipo', 'Nivel', 'Precio Inscripción']);
                
                // Datos
                foreach ($torneos as $torneo) {
                    $inscritos = isset($inscripciones[$torneo->id]) ? count($inscripciones[$torneo->id]) : 0;
                    
                    fputcsv($output, [
                        $torneo->id,
                        $torneo->nombre,
                        $torneo->fecha_inicio,
                        $torneo->fecha_fin,
                        $torneo->capacidad,
                        $inscritos,
                        $torneo->estado,
                        $torneo->tipo,
                        $torneo->nivel,
                        $torneo->precio_inscripcion
                    ]);
                }
                
                fclose($output);
                exit;
            }
        } else {
            // Mostrar formulario
            $datos = [
                'fecha_inicio' => date('Y-01-01'), // Primer día del año actual
                'fecha_fin' => date('Y-12-31')     // Último día del año actual
            ];
            
            $this->view('informes/torneos', $datos);
        }
    }

    /**
     * Generar informe de usuarios
     */
    public function usuarios() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar el formulario
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $rol = isset($_POST['rol']) ? trim($_POST['rol']) : '';
            $formato = isset($_POST['formato']) ? trim($_POST['formato']) : 'pdf';
            
            // Obtener usuarios según los filtros
            $usuarios = $this->usuarioModelo->obtenerUsuariosPorRol($rol);
            
            if (empty($usuarios)) {
                flash('informe_mensaje', 'No hay usuarios para los filtros seleccionados', 'alert alert-warning');
                redirect('informes/usuarios');
            }
            
            // Generar el informe
            $titulo = 'Informe de Usuarios';
            if ($rol) {
                $titulo .= ' - Rol: ' . ucfirst($rol);
            }
            
            if ($formato == 'pdf') {
                // Cargar el helper de PDF
                require_once APPROOT . '/helpers/pdf_helper.php';
                
                // Generar el PDF
                generarInformeUsuarios($usuarios, $titulo);
                exit;
            } else {
                // Exportar a CSV
                $filename = 'informe_usuarios_' . date('Ymd') . '.csv';
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                
                $output = fopen('php://output', 'w');
                
                // Encabezados CSV
                fputcsv($output, ['ID', 'Nombre', 'Email', 'Fecha Registro', 'Rol']);
                
                // Datos
                foreach ($usuarios as $usuario) {
                    $rol = isset($usuario->rol) ? $usuario->rol : (isset($usuario->is_admin) && $usuario->is_admin ? 'Administrador' : 'Usuario');
                    
                    fputcsv($output, [
                        $usuario->id,
                        $usuario->nombre,
                        $usuario->email,
                        $usuario->fecha_registro,
                        ucfirst($rol)
                    ]);
                }
                
                fclose($output);
                exit;
            }
        } else {
            // Mostrar formulario
            $this->view('informes/usuarios');
        }
    }
}