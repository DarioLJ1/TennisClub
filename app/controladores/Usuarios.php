<?php
namespace app\controladores;

use app\librerias\Controller;
use app\modelos\Usuario;

class Usuarios extends Controller {
    private $usuarioModelo;

    public function __construct() {
        $this->usuarioModelo = new Usuario;
    }

    public function registrar() {
        error_log("Método registrar llamado");
        // Comprobar POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar formulario
            
            // Sanitizar datos de POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Inicializar datos
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirmar_password' => trim($_POST['confirmar_password']),
                'nombre_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirmar_password_err' => ''
            ];

            // Validar Email
            if(empty($datos['email'])) {
                $datos['email_err'] = 'Por favor ingrese el email';
            } else {
                // Comprobar email
                if($this->usuarioModelo->encontrarUsuarioPorEmail($datos['email'])) {
                    $datos['email_err'] = 'Email ya está registrado';
                }
            }

            // Validar Nombre
            if(empty($datos['nombre'])) {
                $datos['nombre_err'] = 'Por favor ingrese el nombre';
            }

            // Validar Password
            if(empty($datos['password'])) {
                $datos['password_err'] = 'Por favor ingrese la contraseña';
            } elseif(strlen($datos['password']) < 6) {
                $datos['password_err'] = 'La contraseña debe tener al menos 6 caracteres';
            }

            // Validar Confirmar Password
            if(empty($datos['confirmar_password'])) {
                $datos['confirmar_password_err'] = 'Por favor confirme la contraseña';
            } else {
                if($datos['password'] != $datos['confirmar_password']) {
                    $datos['confirmar_password_err'] = 'Las contraseñas no coinciden';
                }
            }

            // Asegurarse de que los errores estén vacíos
            if(empty($datos['email_err']) && empty($datos['nombre_err']) && empty($datos['password_err']) && empty($datos['confirmar_password_err'])) {
                // Validado
                
                // Hash Password
                $datos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);

                // Registrar Usuario
                if($this->usuarioModelo->registrar($datos)) {
                    flash('register_success', 'Ya estás registrado y puedes iniciar sesión');
                    redirect('usuarios/login');
                } else {
                    die('Algo salió mal');
                }

            } else {
                error_log("Cargando vista de registro con errores: " . print_r($datos, true));
                // Cargar vista con errores
                $this->view('usuarios/registrar', $datos);
            }

        } else {
            error_log("Cargando vista de registro");
            // Inicializar datos
            $datos = [
                'nombre' => '',
                'email' => '',
                'password' => '',
                'confirmar_password' => '',
                'nombre_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirmar_password_err' => ''
            ];

            // Cargar vista
            $this->view('usuarios/registrar', $datos);
        }
    }

    public function login() {
        // Comprobar POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar formulario
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            // Inicializar datos
            $datos = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',      
            ];

            // Validar Email
            if(empty($datos['email'])) {
                $datos['email_err'] = 'Por favor ingrese el email';
            }

            // Validar Password
            if(empty($datos['password'])) {
                $datos['password_err'] = 'Por favor ingrese la contraseña';
            }

            // Comprobar usuario/email
            if($this->usuarioModelo->encontrarUsuarioPorEmail($datos['email'])) {
                // Usuario encontrado
            } else {
                // Usuario no encontrado
                $datos['email_err'] = 'No se encontró usuario';
            }

            // Asegurarse de que los errores estén vacíos
            if(empty($datos['email_err']) && empty($datos['password_err'])) {
                // Validado
                // Comprobar e iniciar sesión
                $usuarioLogueado = $this->usuarioModelo->login($datos['email'], $datos['password']);

                if($usuarioLogueado) {
                    // Crear sesión
                    $this->crearSesionUsuario($usuarioLogueado);
                } else {
                    $datos['password_err'] = 'Contraseña incorrecta';
                    $this->view('usuarios/login', $datos);
                }
            } else {
                // Cargar vista con errores
                $this->view('usuarios/login', $datos);
            }

        } else {
            // Init data
            $datos = [    
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',        
            ];

            // Cargar vista
            $this->view('usuarios/login', $datos);
        }
    }

    public function crearSesionUsuario($usuario) {
        $_SESSION['user_id'] = $usuario->id;
        $_SESSION['user_email'] = $usuario->email;
        $_SESSION['user_name'] = $usuario->nombre;
        $_SESSION['user_role'] = $usuario->role;
        redirect('paginas/index');
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();
        redirect('usuarios/login');
    }
}