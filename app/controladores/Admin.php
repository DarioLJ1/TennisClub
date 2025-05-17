<?php
namespace app\controladores;

use app\librerias\Controller;
use app\modelos\Pista;
use app\modelos\Mantenimiento;
use app\modelos\Profesor;
use app\modelos\Torneo;
use app\modelos\InscripcionTorneo;
use app\modelos\Usuario;

class Admin extends Controller {

    private $pistaModelo;
    private $mantenimientoModelo;
    private $profesorModelo;
    private $torneoModelo;
    private $inscripcionModelo;
    private $usuarioModelo;

    public function __construct() {

        if (!isLoggedIn() || !isAdmin()) {

            redirect('usuarios/login');

        }

        $this->pistaModelo = $this->model('Pista');
        $this->mantenimientoModelo = $this->model('Mantenimiento');
        $this->profesorModelo = $this->model('Profesor');
        $this->torneoModelo = $this->model('Torneo');
        $this->inscripcionModelo = $this->model('InscripcionTorneo');
        $this->usuarioModelo = $this->model('Usuario');

    }

    public function index() {

        $pistas = $this->pistaModelo->obtenerPistas();
        $datos = [
            'pistas' => $pistas
        ];
        $this->view('admin/index', $datos);

    }

    public function agregarPista() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $datos = [
                'nombre' => trim($_POST['nombre']),
                'tipo' => trim($_POST['tipo']),
                'estado' => trim($_POST['estado']),
                'nombre_err' => '',
                'tipo_err' => '',
                'estado_err' => ''
            ];

            if (empty($datos['nombre'])) {

                $datos['nombre_err'] = 'Por favor ingrese el nombre de la pista';

            }

            if (empty($datos['tipo'])) {

                $datos['tipo_err'] = 'Por favor seleccione el tipo de pista';

            }

            if (empty($datos['estado'])) {

                $datos['estado_err'] = 'Por favor seleccione el estado de la pista';

            }

            if (empty($datos['nombre_err']) && empty($datos['tipo_err']) && empty($datos['estado_err'])) {

                if ($this->pistaModelo->agregarPista($datos)) {

                    flash('pista_mensaje', 'Pista agregada con éxito');
                    redirect('admin');

                } else {

                    die('Algo salió mal');

                }
            } else {

                $this->view('admin/agregar_pista', $datos);

            }

        } else {

            $datos = [
                'nombre' => '',
                'tipo' => '',
                'estado' => '',
                'nombre_err' => '',
                'tipo_err' => '',
                'estado_err' => ''
            ];

            $this->view('admin/agregar_pista', $datos);

        }
    }

    public function editarPista($id) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $datos = [
                'id' => $id,
                'nombre' => trim($_POST['nombre']),
                'tipo' => trim($_POST['tipo']),
                'estado' => trim($_POST['estado']),
                'nombre_err' => '',
                'tipo_err' => '',
                'estado_err' => ''
            ];

            if (empty($datos['nombre'])) {

                $datos['nombre_err'] = 'Por favor ingrese el nombre de la pista';

            }

            if (empty($datos['tipo'])) {

                $datos['tipo_err'] = 'Por favor seleccione el tipo de pista';

            }

            if (empty($datos['estado'])) {

                $datos['estado_err'] = 'Por favor seleccione el estado de la pista';

            }

            if (empty($datos['nombre_err']) && empty($datos['tipo_err']) && empty($datos['estado_err'])) {

                if ($this->pistaModelo->actualizarPista($datos)) {

                    flash('pista_mensaje', 'Pista actualizada con éxito');
                    redirect('admin');

                } else {

                    die('Algo salió mal');

                }
            } else {

                $this->view('admin/editar_pista', $datos);

            }

        } else {

            $pista = $this->pistaModelo->obtenerPistaPorId($id);

            $datos = [
                'id' => $id,
                'nombre' => $pista->nombre,
                'tipo' => $pista->tipo,
                'estado' => $pista->estado,
                'nombre_err' => '',
                'tipo_err' => '',
                'estado_err' => ''
            ];

            $this->view('admin/editar_pista', $datos);

        }
    }

    public function eliminarPista($id) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ($this->pistaModelo->eliminarPista($id)) {

                flash('pista_mensaje', 'Pista eliminada con éxito');

            } else {

                flash('pista_mensaje', 'No se pudo eliminar la pista', 'alert alert-danger');

            }

            redirect('admin');

        } else {

            redirect('admin');

        }
    }

    public function verPista($id) {

        $pista = $this->pistaModelo->obtenerPistaPorId($id);
        $mantenimientos = $this->pistaModelo->obtenerMantenimientos($id);

        $datos = [
            'pista' => $pista,
            'mantenimientos' => $mantenimientos
        ];

        $this->view('admin/ver_pista', $datos);

    }

    public function agregarMantenimiento($id_pista) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $datos = [
                'id_pista' => $id_pista,
                'fecha_inicio' => trim($_POST['fecha_inicio']),
                'fecha_fin' => trim($_POST['fecha_fin']),
                'descripcion' => trim($_POST['descripcion']),
                'estado' => trim($_POST['estado']),
                'fecha_inicio_err' => '',
                'fecha_fin_err' => '',
                'descripcion_err' => ''
            ];

            if (empty($datos['fecha_inicio'])) {

                $datos['fecha_inicio_err'] = 'Por favor ingrese la fecha de inicio';

            }

            if (empty($datos['descripcion'])) {

                $datos['descripcion_err'] = 'Por favor ingrese una descripción';

            }

            if (empty($datos['fecha_inicio_err']) && empty($datos['fecha_fin_err']) && empty($datos['descripcion_err'])) {

                if ($this->mantenimientoModelo->agregarMantenimiento($datos)) {

                    flash('mantenimiento_mensaje', 'Mantenimiento agregado con éxito');
                    redirect('admin/verPista/' . $id_pista);

                } else {

                    die('Algo salió mal');

                }
            } else {
                
                $this->view('admin/agregar_mantenimiento', $datos);

            }

        } else {

            $datos = [
                'id_pista' => $id_pista,
                'fecha_inicio' => '',
                'fecha_fin' => '',
                'descripcion' => '',
                'estado' => 'Programado',
                'fecha_inicio_err' => '',
                'fecha_fin_err' => '',
                'descripcion_err' => ''
            ];

            $this->view('admin/agregar_mantenimiento', $datos);

        }
    }

    public function editarMantenimiento($id) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $datos = [
                'id' => $id,
                'fecha_inicio' => trim($_POST['fecha_inicio']),
                'fecha_fin' => trim($_POST['fecha_fin']),
                'descripcion' => trim($_POST['descripcion']),
                'estado' => trim($_POST['estado']),
                'fecha_inicio_err' => '',
                'fecha_fin_err' => '',
                'descripcion_err' => ''
            ];

            if (empty($datos['fecha_inicio'])) {

                $datos['fecha_inicio_err'] = 'Por favor ingrese la fecha de inicio';

            }
            if (empty($datos['descripcion'])) {

                $datos['descripcion_err'] = 'Por favor ingrese una descripción';

            }

            if (empty($datos['fecha_inicio_err']) && empty($datos['fecha_fin_err']) && empty($datos['descripcion_err'])) {

                if ($this->mantenimientoModelo->actualizarMantenimiento($datos)) {

                    flash('mantenimiento_mensaje', 'Mantenimiento actualizado con éxito');
                    $mantenimiento = $this->mantenimientoModelo->obtenerMantenimientoPorId($id);
                    redirect('admin/verPista/' . $mantenimiento->id_pista);

                } else {

                    die('Algo salió mal');

                }
            } else {

                $this->view('admin/editar_mantenimiento', $datos);
            }
        } else {
            
            $mantenimiento = $this->mantenimientoModelo->obtenerMantenimientoPorId($id);

            $datos = [
                'id' => $id,
                'fecha_inicio' => $mantenimiento->fecha_inicio,
                'fecha_fin' => $mantenimiento->fecha_fin,
                'descripcion' => $mantenimiento->descripcion,
                'estado' => $mantenimiento->estado,
                'fecha_inicio_err' => '',
                'fecha_fin_err' => '',
                'descripcion_err' => ''
            ];

            $this->view('admin/editar_mantenimiento', $datos);

        }
    }

    public function eliminarMantenimiento($id) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $mantenimiento = $this->mantenimientoModelo->obtenerMantenimientoPorId($id);

            if ($this->mantenimientoModelo->eliminarMantenimiento($id)) {

                flash('mantenimiento_mensaje', 'Mantenimiento eliminado con éxito');

            } else {

                flash('mantenimiento_mensaje', 'No se pudo eliminar el mantenimiento', 'alert alert-danger');

            }

            redirect('admin/verPista/' . $mantenimiento->id_pista);

        } else {

            redirect('admin');

        }
    }

    public function profesores() {

        $profesores = $this->profesorModelo->obtenerProfesores();

        $datos = [
            'profesores' => $profesores
        ];

        $this->view('admin/profesores/index', $datos);

    }

    public function agregarProfesor() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'apellido' => trim($_POST['apellido']),
                'email' => trim($_POST['email']),
                'telefono' => trim($_POST['telefono']),
                'especialidad' => trim($_POST['especialidad']),
                'nivel' => trim($_POST['nivel']),
                'precio_hora' => trim($_POST['precio_hora']),
                'disponible' => isset($_POST['disponible']) ? 1 : 0,
                'nombre_err' => '',
                'apellido_err' => '',
                'email_err' => '',
                'telefono_err' => ''
            ];

            if (empty($datos['nombre'])) {

                $datos['nombre_err'] = 'Por favor ingrese el nombre';

            }

            if (empty($datos['apellido'])) {

                $datos['apellido_err'] = 'Por favor ingrese el apellido';

            }

            if (empty($datos['email'])) {

                $datos['email_err'] = 'Por favor ingrese el email';

            }

            if (empty($datos['telefono'])) {

                $datos['telefono_err'] = 'Por favor ingrese el teléfono';

            }

            if (empty($datos['nombre_err']) && empty($datos['apellido_err']) && empty($datos['email_err']) && empty($datos['telefono_err'])) {
                
                if ($this->profesorModelo->agregarProfesor($datos)) {

                    flash('profesor_mensaje', 'Profesor agregado con éxito');
                    redirect('admin/profesores');

                } else {

                    die('Algo salió mal');

                }

            } else {

                $this->view('admin/profesores/agregar', $datos);

            }
        } else {

            $datos = [
                'nombre' => '',
                'apellido' => '',
                'email' => '',
                'telefono' => '',
                'especialidad' => '',
                'nivel' => 'Intermedio',
                'precio_hora' => '30.00',
                'disponible' => 1,
                'nombre_err' => '',
                'apellido_err' => '',
                'email_err' => '',
                'telefono_err' => ''
            ];

            $this->view('admin/profesores/agregar', $datos);

        }
    }

    public function editarProfesor($id) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $datos = [
                'id' => $id,
                'nombre' => trim($_POST['nombre']),
                'apellido' => trim($_POST['apellido']),
                'email' => trim($_POST['email']),
                'telefono' => trim($_POST['telefono']),
                'especialidad' => trim($_POST['especialidad']),
                'nivel' => trim($_POST['nivel']),
                'precio_hora' => trim($_POST['precio_hora']),
                'disponible' => isset($_POST['disponible']) ? 1 : 0,
                'nombre_err' => '',
                'apellido_err' => '',
                'email_err' => '',
                'telefono_err' => ''
            ];

            if (empty($datos['nombre'])) {

                $datos['nombre_err'] = 'Por favor ingrese el nombre';

            }
            if (empty($datos['apellido'])) {

                $datos['apellido_err'] = 'Por favor ingrese el apellido';

            }
            if (empty($datos['email'])) {

                $datos['email_err'] = 'Por favor ingrese el email';

            }
            if (empty($datos['telefono'])) {

                $datos['telefono_err'] = 'Por favor ingrese el teléfono';

            }

            if (empty($datos['nombre_err']) && empty($datos['apellido_err']) && empty($datos['email_err']) && empty($datos['telefono_err'])) {
                
                if ($this->profesorModelo->actualizarProfesor($datos)) {

                    flash('profesor_mensaje', 'Profesor actualizado con éxito');
                    redirect('admin/profesores');

                } else {

                    die('Algo salió mal');

                }
            } else {

                $this->view('admin/profesores/editar', $datos);

            }

        } else {

            $profesor = $this->profesorModelo->obtenerProfesorPorId($id);
            
            $datos = [
                'id' => $id,
                'nombre' => $profesor->nombre,
                'apellido' => $profesor->apellido,
                'email' => $profesor->email,
                'telefono' => $profesor->telefono,
                'especialidad' => $profesor->especialidad,
                'nivel' => $profesor->nivel,
                'precio_hora' => $profesor->precio_hora,
                'disponible' => $profesor->disponible,
                'nombre_err' => '',
                'apellido_err' => '',
                'email_err' => '',
                'telefono_err' => ''
            ];

            $this->view('admin/profesores/editar', $datos);

        }
    }

    public function eliminarProfesor($id) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ($this->profesorModelo->eliminarProfesor($id)) {
                flash('profesor_mensaje', 'Profesor eliminado con éxito');
            } else {
                flash('profesor_mensaje', 'No se pudo eliminar el profesor', 'alert alert-danger');
            }

            redirect('admin/profesores');

        } else {

            redirect('admin/profesores');

        }
    }

    public function horarioProfesor($id) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $datos = [
                'id_profesor' => $id,
                'dia_semana' => trim($_POST['dia_semana']),
                'hora_inicio' => trim($_POST['hora_inicio']),
                'hora_fin' => trim($_POST['hora_fin'])
            ];

            if ($this->profesorModelo->agregarHorario($datos)) {

                flash('horario_mensaje', 'Horario agregado con éxito');

            } else {

                flash('horario_mensaje', 'Error al agregar horario', 'alert alert-danger');

            }

            redirect('admin/horarioProfesor/' . $id);

        }

        $profesor = $this->profesorModelo->obtenerProfesorPorId($id);
        $horarios = $this->profesorModelo->obtenerHorarioProfesor($id);

        $datos = [
            'profesor' => $profesor,
            'horarios' => $horarios
        ];

        $this->view('admin/profesores/horario', $datos);

    }

    public function eliminarHorario($id_horario) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ($this->profesorModelo->eliminarHorario($id_horario)) {

                flash('horario_mensaje', 'Horario eliminado con éxito');

            } else {

                flash('horario_mensaje', 'Error al eliminar horario', 'alert alert-danger');

            }

            redirect('admin/profesores');

        } else {

            redirect('admin/profesores');
            
        }
    }

    public function clases() {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('usuarios/login');
        }

        $claseModelo = $this->model('ClaseParticular');
        $clases = $claseModelo->obtenerClases();

        $datos = [
            'clases' => $clases
        ];

        $this->view('admin/clases/index', $datos);
    }

    public function actualizarEstadoClase($id) {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('usuarios/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $claseModelo = $this->model('ClaseParticular');
            
            if ($claseModelo->actualizarEstado($id, $_POST['estado'])) {
                flash('clase_mensaje', 'Estado de la clase actualizado correctamente');
            } else {
                flash('clase_mensaje', 'Error al actualizar el estado de la clase', 'alert alert-danger');
            }
            
            redirect('admin/clases');
        } else {
            redirect('admin/clases');
        }
    }

    public function cancelarClase($id) {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('usuarios/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $claseModelo = $this->model('ClaseParticular');
            
            if ($claseModelo->cancelarClase($id)) {
                flash('clase_mensaje', 'Clase cancelada correctamente');
            } else {
                flash('clase_mensaje', 'Error al cancelar la clase', 'alert alert-danger');
            }
            
            redirect('admin/clases');
        } else {
            redirect('admin/clases');
        }
    }

    // Métodos para la gestión de torneos
    public function torneos() {
        $torneos = $this->torneoModelo->obtenerTorneos();
        
        $datos = [
            'torneos' => $torneos
        ];
        
        $this->view('admin/torneos/index', $datos);
    }
    
    public function agregarTorneo() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'fecha_inicio' => trim($_POST['fecha_inicio']),
                'fecha_fin' => trim($_POST['fecha_fin']),
                'descripcion' => trim($_POST['descripcion']),
                'capacidad' => trim($_POST['capacidad']),
                'estado' => trim($_POST['estado']),
                'tipo' => trim($_POST['tipo']),
                'nivel' => trim($_POST['nivel']),
                'precio_inscripcion' => trim($_POST['precio_inscripcion']),
                'nombre_err' => '',
                'fecha_inicio_err' => '',
                'fecha_fin_err' => '',
                'capacidad_err' => ''
            ];
            
            // Validar datos
            if (empty($datos['nombre'])) {
                $datos['nombre_err'] = 'Por favor ingrese el nombre del torneo';
            }
            
            if (empty($datos['fecha_inicio'])) {
                $datos['fecha_inicio_err'] = 'Por favor ingrese la fecha de inicio';
            }
            
            if (empty($datos['fecha_fin'])) {
                $datos['fecha_fin_err'] = 'Por favor ingrese la fecha de fin';
            } elseif ($datos['fecha_fin'] < $datos['fecha_inicio']) {
                $datos['fecha_fin_err'] = 'La fecha de fin debe ser posterior a la fecha de inicio';
            }
            
            if (empty($datos['capacidad'])) {
                $datos['capacidad_err'] = 'Por favor ingrese la capacidad del torneo';
            } elseif (!is_numeric($datos['capacidad']) || $datos['capacidad'] <= 0) {
                $datos['capacidad_err'] = 'La capacidad debe ser un número mayor a 0';
            }
            
            // Si no hay errores, crear el torneo
            if (empty($datos['nombre_err']) && empty($datos['fecha_inicio_err']) && 
                empty($datos['fecha_fin_err']) && empty($datos['capacidad_err'])) {
                
                if ($this->torneoModelo->crearTorneo($datos)) {
                    flash('torneo_mensaje', 'Torneo creado con éxito');
                    redirect('admin/torneos');
                } else {
                    die('Algo salió mal');
                }
            } else {
                // Cargar la vista con errores
                $this->view('admin/torneos/agregar', $datos);
            }
        } else {
            $datos = [
                'nombre' => '',
                'fecha_inicio' => '',
                'fecha_fin' => '',
                'descripcion' => '',
                'capacidad' => '',
                'estado' => 'programado',
                'tipo' => 'Individual',
                'nivel' => 'Todos',
                'precio_inscripcion' => '0.00',
                'nombre_err' => '',
                'fecha_inicio_err' => '',
                'fecha_fin_err' => '',
                'capacidad_err' => ''
            ];
            
            $this->view('admin/torneos/agregar', $datos);
        }
    }
    
    public function editarTorneo($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $datos = [
                'id' => $id,
                'nombre' => trim($_POST['nombre']),
                'fecha_inicio' => trim($_POST['fecha_inicio']),
                'fecha_fin' => trim($_POST['fecha_fin']),
                'descripcion' => trim($_POST['descripcion']),
                'capacidad' => trim($_POST['capacidad']),
                'estado' => trim($_POST['estado']),
                'tipo' => trim($_POST['tipo']),
                'nivel' => trim($_POST['nivel']),
                'precio_inscripcion' => trim($_POST['precio_inscripcion']),
                'nombre_err' => '',
                'fecha_inicio_err' => '',
                'fecha_fin_err' => '',
                'capacidad_err' => ''
            ];
            
            // Validar datos
            if (empty($datos['nombre'])) {
                $datos['nombre_err'] = 'Por favor ingrese el nombre del torneo';
            }
            
            if (empty($datos['fecha_inicio'])) {
                $datos['fecha_inicio_err'] = 'Por favor ingrese la fecha de inicio';
            }
            
            if (empty($datos['fecha_fin'])) {
                $datos['fecha_fin_err'] = 'Por favor ingrese la fecha de fin';
            } elseif ($datos['fecha_fin'] < $datos['fecha_inicio']) {
                $datos['fecha_fin_err'] = 'La fecha de fin debe ser posterior a la fecha de inicio';
            }
            
            if (empty($datos['capacidad'])) {
                $datos['capacidad_err'] = 'Por favor ingrese la capacidad del torneo';
            } elseif (!is_numeric($datos['capacidad']) || $datos['capacidad'] <= 0) {
                $datos['capacidad_err'] = 'La capacidad debe ser un número mayor a 0';
            }
            
            // Si no hay errores, actualizar el torneo
            if (empty($datos['nombre_err']) && empty($datos['fecha_inicio_err']) && 
                empty($datos['fecha_fin_err']) && empty($datos['capacidad_err'])) {
                
                if ($this->torneoModelo->actualizarTorneo($datos)) {
                    flash('torneo_mensaje', 'Torneo actualizado con éxito');
                    redirect('admin/torneos');
                } else {
                    die('Algo salió mal');
                }
            } else {
                // Cargar la vista con errores
                $this->view('admin/torneos/editar', $datos);
            }
        } else {
            // Obtener datos del torneo
            $torneo = $this->torneoModelo->obtenerTorneoPorId($id);
            
            $datos = [
                'id' => $id,
                'nombre' => $torneo->nombre,
                'fecha_inicio' => $torneo->fecha_inicio,
                'fecha_fin' => $torneo->fecha_fin,
                'descripcion' => $torneo->descripcion,
                'capacidad' => $torneo->capacidad,
                'estado' => $torneo->estado,
                'tipo' => isset($torneo->tipo) ? $torneo->tipo : 'Individual',
                'nivel' => isset($torneo->nivel) ? $torneo->nivel : 'Todos',
                'precio_inscripcion' => isset($torneo->precio_inscripcion) ? $torneo->precio_inscripcion : '0.00',
                'nombre_err' => '',
                'fecha_inicio_err' => '',
                'fecha_fin_err' => '',
                'capacidad_err' => ''
            ];
            
            $this->view('admin/torneos/editar', $datos);
        }
    }
    
    public function eliminarTorneo($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verificar si hay inscripciones
            $inscripciones = $this->inscripcionModelo->obtenerInscripcionesPorTorneo($id);
            
            if (!empty($inscripciones)) {
                flash('torneo_mensaje', 'No se puede eliminar un torneo con inscripciones', 'alert alert-danger');
                redirect('admin/torneos');
                return;
            }
            
            if ($this->torneoModelo->eliminarTorneo($id)) {
                flash('torneo_mensaje', 'Torneo eliminado con éxito');
            } else {
                flash('torneo_mensaje', 'Error al eliminar el torneo', 'alert alert-danger');
            }
            
            redirect('admin/torneos');
        } else {
            redirect('admin/torneos');
        }
    }
    
    public function verInscripciones($id_torneo) {
        $torneo = $this->torneoModelo->obtenerTorneoPorId($id_torneo);
        $inscripciones = $this->inscripcionModelo->obtenerInscripcionesPorTorneo($id_torneo);
        
        $datos = [
            'torneo' => $torneo,
            'inscripciones' => $inscripciones
        ];
        
        $this->view('admin/torneos/inscripciones', $datos);
    }
    
    public function actualizarEstadoInscripcion($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $estado = $_POST['estado'];
            $id_torneo = $_POST['id_torneo'];
            
            if ($this->inscripcionModelo->actualizarEstadoInscripcion($id, $estado)) {
                flash('inscripcion_mensaje', 'Estado de inscripción actualizado con éxito');
            } else {
                flash('inscripcion_mensaje', 'Error al actualizar el estado de la inscripción', 'alert alert-danger');
            }
            
            redirect('admin/verInscripciones/' . $id_torneo);
        } else {
            redirect('admin/torneos');
        }
    }

    // Método para mostrar la lista de usuarios
    public function usuarios() {
        // Obtener todos los usuarios sin paginación
        $usuarios = $this->usuarioModelo->obtenerTodosUsuarios();

        $datos = [
            'usuarios' => $usuarios
        ];

        $this->view('admin/usuarios/index', $datos);
    }

    // Método para cambiar el rol de un usuario
    public function cambiarRolUsuario($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verificar que el usuario a modificar no sea el mismo que está logueado
            if ($id == $_SESSION['user_id']) {
                flash('usuario_mensaje', 'No puedes cambiar tu propio rol', 'alert alert-danger');
                redirect('admin/usuarios');
                return;
            }

            $rol = $_POST['rol'];
            
            if ($this->usuarioModelo->cambiarRolUsuario($id, $rol)) {
                flash('usuario_mensaje', 'Rol de usuario actualizado con éxito');
            } else {
                flash('usuario_mensaje', 'Error al actualizar el rol del usuario', 'alert alert-danger');
            }
            
            redirect('admin/usuarios');
        } else {
            redirect('admin/usuarios');
        }
    }
}