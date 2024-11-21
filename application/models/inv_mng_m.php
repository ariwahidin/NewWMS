<?php
class Inv_mng_m extends CI_Model
{
    public function getItemToTransfer($location, $lpn, $item_code)
    {
        $sql = "select a.*, b.item_name from inventory a
                inner join master_item b on a.item_code = b.item_code
                where a.is_pick = 'Y'
                and a.available > 0
                and a.allocated = 0";

        $whs_code = $_SESSION['user_data']['warehouse'];
        $sql .= " and a.whs_code = ?";

        if ($location != '') $sql .= " and a.location = ?";
        if ($lpn != '') $sql .= " and a.lpn_number = ?";
        if ($item_code != '') $sql .= " and a.item_code = ?";
        $where = array();
        $where[] = $whs_code;

        if ($location != '') $where[] = $location;
        if ($lpn != '') $where[] = $lpn;
        if ($item_code != '') $where[] = $item_code;

        $query = $this->db->query($sql, $where);
        return $query;
    }

    public function getInventoryById($id)
    {
        $sql = "select a.*, b.receive_detail_id from inventory a 
                inner join lpn b on a.lpn_id = b.id  
                where a.id = ?";
        $where[] = $id;
        $query = $this->db->query($sql, $where);
        return $query;
    }
}
