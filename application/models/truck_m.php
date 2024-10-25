<?php
class Truck_m extends CI_Model
{
    public function getTruckType()
    {
        $sql = "SELECT id, truck_name from truck_type";
        $query = $this->db->query($sql);
        return $query;
    }
}
