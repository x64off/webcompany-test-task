<?php
class Index extends Model{
    private $table = 'cities';
    public function Get_Cities($order=null,$order_type=null)
    {
        if ($order!=null && $order_type!=null)
            return $this->selectAll($this->table,$order,$order_type);
        else
            return $this->selectAll($this->table);
    }
    public function Delete_City($id)
    {
        return $this->delete($this->table, $id);
    }
    public function Get_City($id)
    {
        return $this->selectById($this->table, $id);
    }
    public function Update_City($id,$data = [])
    {
        return $this->update($this->table, $id, $data);
    }
    public function Add_City($data=[])
    {
        return $this->insert($this->table, $data);
    }
}