<?php
namespace app\controladores;

use app\librerias\Controller;
use app\modelos\Reserva;
use app\modelos\Pista;

class Estadisticas extends Controller {

    private $reservaModelo;
    private $pistaModelo;

    public function __construct(){

        if(!isLoggedIn() || !isAdmin()){

            redirect ('usuarios/login');

        }

        $this->reservaModelo = $this->model('Reserva');
        $this->pistaModelo = $this->model('Pista');

    }

    public function index(){

        $pistas = $this->pistaModelo->obtenerPistas();

        $datos = [

            'pistas' => $pistas

        ];

        $this->view ('estadisticas/index',$datos);

    }

    public function verEstadisticas(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $id_pista = $_POST['id_pista'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];

            if ($id_pista = 'todas'){

                $estadisticas = $this->reservaModelo->obtenerEstadisticasTodasPistas($fecha_inicio, $fecha_fin);

            }else{

                $estadisticas = $this->reservaModelo->obtenerEstadisticasTodasPistas($id_pista, $fecha_inicio, $fecha_fin);
                $pista = $this->pistaModelo->obtenerPistaPorId($id_pista);
                $estadisticas->nombre = $pista->nombre;

            }

            $datos = [

                'estadisticas' => $estadisticas,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'id_pista' => $id_pista

            ];

            $this->view('estadisticas/ver', $datos);

        }else{

            redirect('estadisticas');

        }

    }

}