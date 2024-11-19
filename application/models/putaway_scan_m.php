<?php
class Putaway_scan_m extends CI_Model
{
    public function getItem($post)
    {
        $sql = "SELECT b.id as putaway_id, 
            b.putaway_number, a.id as receive_detail_id,  a.* 
            FROM receive_detail a 
            INNER JOIN putaway_header b ON a.receive_id = b.receive_id
            WHERE a.receive_number = ?
            AND a.item_code = ?
            AND b.is_complete = 'N'";
        $where = array();
        $where[] = $post['receiveNumber'];
        $where[] = $post['itemCode'];

        $query = $this->db->query($sql, $where);
        return $query;
    }

    public function checkItemScaned($receive_number = null, $receive_detail_id = null)
    {
        $sql = "SELECT 
            a.item_code, 
            a.qty as req_qty,
            COALESCE(SUM(b.qty), 0) as qty_scan
        FROM receive_detail a
        LEFT JOIN putaway_detail b ON a.id = b.receive_detail_id AND a.item_code = b.item_code
        WHERE a.receive_number = ? ";

        $where = array();
        $where[] = $receive_number;

        if ($receive_detail_id != null) {
            $sql .= " and a.id = ?";
            $where[] = $receive_detail_id;
        }

        $sql .= " GROUP BY a.item_code, a.qty";

        $query = $this->db->query($sql, $where);
        return $query;
    }

    public function getItemPutawayByReceiveNumber($receive_number)
    {
        $sql = "SELECT a.* FROM putaway_detail a
                INNER JOIN receive_detail b ON a.receive_detail_id = b.id
                WHERE b.receive_number = ?";

        $query = $this->db->query($sql, array($receive_number));
        return $query;
    }
}
