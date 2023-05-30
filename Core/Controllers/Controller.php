<?php
class Controller{
    protected $Model;
    protected $data =   [];
    protected $View;
    public function __construct()
    {
        // Счетчики
        if(isset($_COOKIE['Pages_Load']))
            setcookie('Pages_Load',intval($_COOKIE['Pages_Load'])+1, time() + 3600);
        else
            setcookie('Pages_Load',2, time() + 3600);
        $this_page = 'Page_'.$this->getName();
        if(isset($_COOKIE[$this_page]))
            setcookie($this_page,intval($_COOKIE[$this_page])+1, time() + 3600);
        else
            setcookie($this_page,2, time() + 3600);
        $this->data['Pages_Load'] = isset($_COOKIE['Pages_Load'])? $_COOKIE['Pages_Load'] : 1;
        $this->data['page'] = $this_page;
        $this->data[$this_page] = isset($_COOKIE[$this_page])? $_COOKIE[$this_page] : 1;
        
        // Загрузка Модели
        $Model  = Core_Dir.'Models/'.$this->getName().'.php';
        if (file_exists($Model)){
            include_once $Model;
            $Model = $this->getName();
            $this->Model = new $Model();
        }
        // Загрузка Рендера
        $this->View = new View();
        // Функция при загрузке
        $this->onLoad();
    }
    // Функция при загрузке 
    public function onLoad()
    {
        # code...
    }
    // Стандартная Функция контроллера
    public function index(){

    }
    public function getName()
    {
        return str_replace('Controller','',static::class);
    }
}
