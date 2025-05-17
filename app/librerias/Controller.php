<?php
namespace app\librerias;

class Controller {

  public function model($model) {

      $modelClass = '\\app\\modelos\\' . $model;

      if (class_exists($modelClass)) {

          return new $modelClass();

      } else {

          die("Model class $modelClass does not exist.");

      }
  }

  public function view($view, $datos = []) {

      $viewFile = APPROOT . '/vistas/' . $view . '.php';
      if(file_exists($viewFile)) {

          try {

              require_once $viewFile;

          } catch (\Throwable $e) {

              error_log("Error al cargar la vista: " . $e->getMessage());
              die("Error al cargar la vista. Por favor, revisa los logs para más detalles.");

          }

      } else {

          error_log("Vista no encontrada: $viewFile");
          die("Vista no encontrada: $view");
          
      }
  }
}