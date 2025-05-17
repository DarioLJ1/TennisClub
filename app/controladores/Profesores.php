<?php
namespace app\controladores;

use app\librerias\Controller;
use app\modelos\Profesor;

class Profesores extends Controller {
    
    private $profesorModelo;

    public function __construct(){

        $this->profesorModelo = $this->model('Profesor');

    }

    public function index(){

        $profesores = $this->profesorModelo->obtenerProfesores();

        $datos = [

            'profesores' => $profesores

        ];

        $this->view('profesores/index', $datos);
    }

    public function detalle($id){

        $profesor = $this->profesorModelo->obtenerProfesorPorId($id);

        $datos = [

            'profesor' => $profesor

        ];

        $this->view('profesores/detalle', $datos);
    }
}

