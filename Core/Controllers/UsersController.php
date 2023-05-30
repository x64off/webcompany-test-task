<?php
class UsersController extends Controller{
    public function onLoad()
    {
        $Model  = Core_Dir.'Models/Index.php';
        if (file_exists($Model)){
            include_once $Model;
            $this->Cities_Model = new Index();
        }
        $Model  = Core_Dir.'Models/File.php';
        if (file_exists($Model)){
            include_once $Model;
            $this->File_Model = new File();
        }
        
    }
    public function index()
    {
        // Получение Городов
        $this->data['cities']=$this->Cities_Model->Get_Cities();

        $this->rendered = FALSE;
        // Проверка на POST запросы
        $this->POST();

        if (!$this->rendered){
            $this->Get_Users_Data();
            $this->View->render('users',$this->data);
        }
    }
    public function Get_Users_Data()
    {
        // Проверка на сортировку
        if (isset($_SESSION['users_order']) && isset($_SESSION['users_type_order']))
            $this->data['users'] = $this->Model->Get_Users($_SESSION['users_order'],$_SESSION['users_type_order']);
        else
            $this->data['users'] = $this->Model->Get_Users();
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
        //ДЕЙСТВИЯ//

        // Сортировка
        if (isset($_POST['submit_sort_names'])){
            if (isset($_POST['sort_name']) && isset($_POST['sort_order_by_2'])){
                $order = match ($_POST['sort_name']) {
                    'sort_id' => 'id',
                    'sort_nm' => 'first_name',
                    'sort_srnm' => 'last_name',
                    'sort_st' => 'city',
                };
                $type_order = match ($_POST['sort_order_by_2']) {
                    'sort_asc' => 'ASC',
                    'sort_desc' => 'DESC',
                };
                $_SESSION['users_order'] = $order;
                $_SESSION['users_type_order'] = $type_order;
            }
        }

        //Добавить Пользователя
        if(isset($_POST['subm_ins_names'])){
            if (isset($_POST['ins_name']) && isset($_POST['ins_surname']) && isset($_POST['selsity'])){
                $data = [
                    'first_name' => htmlentities($_POST['ins_name']),
                    'last_name' => htmlentities($_POST['ins_surname']),
                    'city'=>intval($_POST['selsity']),
                ];
                $picture = $this->File_Model->Upload('uploadfile');
                if ($picture) $data['image'] = $picture;
                $this->Model->Add_User($data);
            }
        }
        //Редактирование Пользователя
        if(isset($_POST['subm_edit_names'])){
            if (isset($_POST['edit_text_name']) && isset($_POST['edit_text_surname']) && isset($_POST['edit_selsity'])&& isset($_POST['id_red'])){
                $data = [
                    'first_name' => htmlentities($_POST['edit_text_name']),
                    'last_name' => htmlentities($_POST['edit_text_surname']),
                    'city'=>intval($_POST['edit_selsity']),
                ];
                $picture = $this->File_Model->Upload('uploadfile');
                if ($picture) $data['image'] = $picture;
                $this->Model->Update_User($_POST['id_red'],$data);
            }
        }
        // Кнопки //
        // Кнопка Добавить Пользователя
        if(isset($_POST['ins2'])){
            $this->rendered = TRUE;
            $this->View->render('user_add',$this->data);
        }
        // Фильтр по городам
        if (isset($_POST['sort_fc']) && isset($_POST['selsity_2'])){
            $this->city_filter = intval($_POST['selsity_2']);
        }
        
        // Кнопка Сортировать
        if(isset($_POST['sort2'])){
            $this->rendered = TRUE;
            $this->Get_Users_Data();
            $this->View->render('user_sort',$this->data);
        }

        // Кнопка Редактирования Пользователя
        if (isset($_POST['edit_fors_names']) && isset($_POST['id'])){
            $this->rendered = TRUE;
            $this->data['user'] = $this->Model->Get_User(intval($_POST['id']));
            $this->View->render('user_edit',$this->data);
        }
   
        // Кнопка Удаления пользователя
        if (isset($_POST['del_fors_names']) && isset($_POST['id'])){
            $this->Model->Delete_User(intval($_POST['id']));
        }
    }
}