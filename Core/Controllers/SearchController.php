<?php
class SearchController extends Controller{
    public function onLoad()
    {
        $Model  = Core_Dir.'Models/Index.php';
        if (file_exists($Model)){
            include_once $Model;
            $this->Cities_Model = new Index();
        }
        $Model  = Core_Dir.'Models/Users.php';
        if (file_exists($Model)){
            include_once $Model;
            $this->Users_Model = new Users();
        }
    }
    public function index()
    {
        // Получение Городов
        $this->data['cities']=$this->Cities_Model->Get_Cities();
        $this->data['users']=[];
        $this->rendered = FALSE;
        // Проверка на POST запросы
        $this->POST();

        if (!$this->rendered){
            $this->Get_Users_Data();
            $this->View->render('search',$this->data);
        }
    }
    public function Get_Users_Data()
    {
        // Подставляем название города Пользователю
        foreach ($this->data['users'] as $key => $value) {
            $city = $this->City_Search($value['city']);
            if ($city)
                $this->data['users'][$key]['city'] = $city;
            else
                $this->data['users'][$key]['city'] = '';
            // фильтр по городам
            if (isset($this->city_filter)){
                if ($value['city']!= $this->city_filter) unset($this->data['users'][$key]);
            }
        }
        unset($this->city_filter);
    }
    public function City_Search($id)
    {
        foreach ($this->data['cities'] as $key => $value) {
            if ($value['id'] == $id) return $value['city_name'];
        }
        return FALSE;
    }
    public function POST()
    {
        if(isset($_POST['sub_sh_name']) && isset($_POST['ins_sh_name'])){
            $query = "SELECT * FROM users WHERE first_name LIKE CONCAT('%', :ins_sh_name, '%') or last_name LIKE CONCAT('%', :ins_sh_name, '%')";
            $this->data['users'] = $this->Users_Model->fetchAll($query, ['ins_sh_name'=>$_POST['ins_sh_name']]);
        }
        
    }
}