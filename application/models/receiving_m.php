<?php
class Receiving_m extends CI_Model
{
    public function receiveList()
    {
        $sql = "select a.id, a.receive_number, a.receive_date, b.total_qty
                from receive_header a
                inner join (select receive_id, SUM(qty) as total_qty from receive_detail group by receive_id) b ON a.id = b.receive_id";
        $query = $this->db->query($sql);
        return $query;
    }
}
