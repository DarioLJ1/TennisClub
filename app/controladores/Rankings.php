<?php
namespace app\controladores;

use app\librerias\Controller;
use app\modelos\Rankings as RankingsModelo;
use app\modelos\Usuario;

class Rankings extends Controller {
    private $rankingsModelo;
    private $usuarioModelo;

    public function __construct() {
        $this->rankingsModelo = new RankingsModelo();
        $this->usuarioModelo = new Usuario();
    }

    public function index() {
        // Obtener el ranking
        $ranking = $this->rankingsModelo->getRanking();

        // Verificar si el usuario está logueado y si está inscrito
        $estaInscrito = false;
        if (isset($_SESSION['user_id'])) {
            $estaInscrito = $this->rankingsModelo->estaInscrito($_SESSION['user_id']);
        }

        $datos = [
            'titulo' => 'Ranking de Jugadores',
            'ranking' => $ranking,
            'estaInscrito' => $estaInscrito
        ];

        $this->view('rankings/index', $datos);
    }

    public function inscribir() {
        if (!isset($_SESSION['user_id'])) {
            flash('rankings_error', 'Debes iniciar sesión para inscribirte', 'alert alert-danger');
            redirect('usuarios/login');
            return;
        }

        if ($this->rankingsModelo->estaInscrito($_SESSION['user_id'])) {
            flash('rankings_error', 'Ya estás inscrito en el ranking', 'alert alert-warning');
            redirect('rankings');
            return;
        }

        $usuario = $this->usuarioModelo->obtenerUsuarioPorId($_SESSION['user_id']);
        if ($this->rankingsModelo->inscribir($usuario)) {
            flash('rankings_success', 'Te has inscrito al ranking con éxito');
        } else {
            flash('rankings_error', 'Error al inscribirte', 'alert alert-danger');
        }
        redirect('rankings');
    }
}