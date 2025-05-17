<?php
namespace app\controladores;

use app\librerias\Controller;
use app\modelos\Torneo;
use app\modelos\InscripcionTorneo;

class Torneos extends Controller {

    private $torneoModelo;
    private $inscripcionModelo;

    public function __construct() {

        if(!isLoggedIn()) {

            redirect('usuarios/login');

        }

        $this->torneoModelo = $this->model('Torneo');
        $this->inscripcionModelo = $this->model('InscripcionTorneo');

    }

    public function index() {

        $torneos = $this->torneoModelo->obtenerTorneos();

        $datos = [

            'torneos' => $torneos

        ];

        $this->view('torneos/index', $datos);
    }

    public function detalle($id) {

        $torneo = $this->torneoModelo->obtenerTorneoPorId($id);
        $inscripciones = $this->inscripcionModelo->obtenerInscripcionesPorTorneo($id);
        $datos = [

            'torneo' => $torneo,
            'inscripciones' => $inscripciones

        ];

        $this->view('torneos/detalle', $datos);

    }

    public function inscribir($id_torneo) {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            if($this->inscripcionModelo->inscribirUsuario($id_torneo, $_SESSION['user_id'])) {
                flash('torneo_mensaje', 'Te has inscrito al torneo correctamente');
                redirect('torneos/detalle/' . $id_torneo);
                
            } else {

                die('Algo salió mal');

            }
        } else {

            redirect('torneos');

        }
    }

    public function misInscripciones() {

        $inscripciones = $this->inscripcionModelo->obtenerInscripcionesPorUsuario($_SESSION['user_id']);

        $datos = [

            'inscripciones' => $inscripciones

        ];

        $this->view('torneos/mis_inscripciones', $datos);
    }
}

