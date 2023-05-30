<?php
require_once Core_Dir.'controllers/Controller.php';
require_once Core_Dir.'models/Model.php';
require_once Core_Dir.'models/View.php';
session_start();
class System {
    public function  __construct()
    {
        $this->Routing();
    }
    public function Routing()
    {
        if (isset($_GET['page']))
            $Contoroller = match ($_GET['page']) {
                '1' => 'IndexController',
                '2' => 'UsersController',
                '3' => 'SearchController',
            };
        else 
            $Contoroller = 'IndexController';
        $classFile = Core_Dir . 'controllers/' . $Contoroller . '.php';
        if (file_exists($classFile)) {
            require $classFile;
        }
        else{
            echo 'Контроллер не найден!';
        }

        if (class_exists($Contoroller)) {
            $controller = new $Contoroller();
            $controller->index();
        }else{
            echo '404';
        }
    }
}