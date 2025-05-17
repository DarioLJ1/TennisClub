<?php
namespace app\controladores;

use app\librerias\Controller;
use app\modelos\Pista;
use app\modelos\Reserva;

class Pistas extends Controller {
    private $pistaModelo;
    private $reservaModelo;

    public function __construct(){
        if(!isLoggedIn()){
            redirect('usuarios/login');
        }

        $this->pistaModelo = $this->model('Pista');
        $this->reservaModelo = $this->model('Reserva');
    }

    public function index(){
        $pistas = $this->pistaModelo->obtenerPistas();

        $datos = [
            'pistas' => $pistas
        ];

        $this->view('pistas/index', $datos);
    }

    public function reservar($id = null){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $datos = [
                'id_pista' => $id,
                'fecha' => trim($_POST['fecha']),
                'hora_inicio' => trim($_POST['hora_inicio']),
                'hora_fin' => trim($_POST['hora_fin']),
                'id_usuario' => $_SESSION['user_id']
            ];

            if($this->reservaModelo->crearReserva($datos)){
                flash('reserva_mensaje', 'Reserva realizada con éxito');
                redirect('pistas');
            } else {
                die('Algo salió mal');
            }
        } else {
            $pista = $this->pistaModelo->obtenerPistaPorId($id);

            $datos = [
                'pista' => $pista
            ];

            $this->view('pistas/reservar', $datos);
        }
    }
}





