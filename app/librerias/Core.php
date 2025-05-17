<?php
namespace app\librerias;

class Core {
    protected $currentController = 'Paginas';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();
        
        if($url !== null && file_exists(APPROOT . '/controladores/' . ucwords($url[0]) . '.php')) {
            $this->currentController = ucwords($url[0]);
            unset($url[0]);
        }

        require_once APPROOT . '/controladores/' . $this->currentController . '.php';
        $controllerClass = '\\app\\controladores\\' . $this->currentController;
        $this->currentController = new $controllerClass();

        if(isset($url[1])) {
            if(method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];
    }

    public function run() {
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl() {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return null;
    }
}