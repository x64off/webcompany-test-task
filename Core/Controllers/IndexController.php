<?php 
class IndexController extends Controller{
    
    public function index()
    {
        // Для рендера основной страницы
        $this->rendered = FALSE;
        // Проверка на POST запросы
        $this->POST();
        
        if (!$this->rendered){
            $this->Get_Cities_Data();
            $this->View->render('index',$this->data);
        }
        
    }
    // Получение всех городов
    private function Get_Cities_Data()
    {
        // Проверка на сортировку
        if (isset($_SESSION['city_order']) && isset($_SESSION['city_type_order']))
            $this->data['cities'] = $this->Model->Get_Cities($_SESSION['city_order'],$_SESSION['city_type_order']);
        else
            $this->data['cities'] = $this->Model->Get_Cities();
    }

    // POST запросы
    private function POST()
    {
        if (isset($_POST)){
            // ДЕЙСТВИЯ //

            // Сортировка
            if (isset($_POST['submit_sort_city'])){
                if (isset($_POST['sort_sity']) && isset($_POST['sort_order_by'])){
                    $order = match ($_POST['sort_sity']) {
                        'sort_id' => 'id',
                        'sort_sity' => 'city_name',
                        'sort_rangir' => 'sort_index',
                    };
                    $type_order = match ($_POST['sort_order_by']) {
                        'sort_asc' => 'ASC',
                        'sort_desc' => 'DESC',
                    };
                    $_SESSION['city_order'] = $order;
                    $_SESSION['city_type_order'] = $type_order;
                }
            }
            // Добавляем город
            if (isset($_POST['subminscity'])){
                if(isset($_POST['instextcity']) && isset($_POST['instextrangir'])){
                    $City_Name = htmlentities($_POST['instextcity']);
                    $Sort_index = intval($_POST['instextrangir']);
                    $data = [
                        'city_name' => $City_Name,
                        'sort_index' => $Sort_index,
                    ];
                    $this->Model->Add_City($data);
                }
            }
           
            // Сохранение изменений города
            if (isset($_POST['submit_edit_city']) && isset($_POST['id'])){
                if(isset($_POST['edit_text_city']) && isset($_POST['edit_text_rangir'])){
                    $City_Name = htmlentities($_POST['edit_text_city']);
                    $Sort_index = intval($_POST['edit_text_rangir']);
                    $data = [
                        'city_name' => $City_Name,
                        'sort_index' => $Sort_index,
                    ];
                    $this->Model->Update_City(intval($_POST['id']),$data);
                }
            }


            // Кнопки //
            
            // Кнопка Сортировать
            if(isset($_POST['sort'])){
                $this->rendered = TRUE;
                $this->Get_Cities_Data();
                $this->View->render('city_sort',$this->data);
            }

            // Кнопка Добавить город
            if(isset($_POST['ins'])){
                $this->rendered = TRUE;
                $this->View->render('city_add',$this->data);
            }
            // Кнопка Редактировать
            if (isset($_POST['edit_fors_city']) && isset($_POST['id'])){
                $this->rendered = TRUE;
                $this->data['city'] = $this->Model->Get_City(intval($_POST['id']));
                $this->View->render('city_edit',$this->data);
            }
            // Кнопка Удаления города
            if (isset($_POST['del_fors_city']) && isset($_POST['id'])){
                $this->Model->Delete_City(intval($_POST['id']));
            }
        }
    }
}