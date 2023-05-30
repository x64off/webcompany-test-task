<?php
class Users extends Model{
    private $table = 'users';
    public function Get_Users($order=null,$order_type=null)
    {
        if ($order!=null && $order_type!=null)
            return $this->selectAll($this->table,$order,$order_type);
        else
            return $this->selectAll($this->table);
    }
    public function Delete_User($id)
    {
        return $this->delete($this->table, $id);
    }
    public function Get_User($id)
    {
        return $this->selectById($this->table, $id);
    }
    public function Add_User($data=[])
    {
        return $this->insert($this->table, $data);
    }
    public function Update_User($id,$data = [])
    {
        return $this->update($this->table, $id, $data);
    }
}