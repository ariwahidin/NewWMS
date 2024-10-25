<?php
class Ekspedisi_m extends CI_Model
{
    public function getEkspedisi()
    {
        $sql = "SELECT * FROM ekspedisi";
        $query = $this->db->query($sql);
        return $query;
    }
}
