<?php

namespace app\controladores;

use app\librerias\Controller;

class Paginas extends Controller {

    public function __construct(){



    }
    
    public function index(){

        $datos = [

            'titulo' => 'Bienvenido a HomeTennis',
            'descripcion' => 'Bienvenido a nuestra aplicación de gestión de club de tenis.'
            
        ];
        
        $this->view('paginas/index', $datos);
    }
}

