<?php
class Receiving_m extends CI_Model
{
    private $lpn_prefix = 'LPN'; // Prefix untuk nomor LPN
    public function receiveList()
    {
        $sql = "select a.id, a.receive_number, a.receive_date, c.putaway_number, a.po_number, d.name as supplier_name, a.truck_no,
                a.is_complete, a.created_by, e.name as ekspedisi_name, f.total_item,
                isnull(b.total_qty, 0) as total_qty, isnull(c.qty_putaway, 0) as qty_putaway, '' as status, a.receiving_status
                from receive_header a
                left join 
                    (
                    select receive_id, SUM(qty) as total_qty from receive_detail 
                    group by receive_id
                    ) b ON a.id = b.receive_id
                left join 
                    (
                    select distinct a.receive_number, a.putaway_number, isnull(sum(b.qty), 0) as qty_putaway
                    from putaway_header a
                    inner join putaway_detail b on a.id = b.putaway_id
                    group by a.receive_number, a.putaway_number
                    ) c on a.receive_number = c.receive_number
                inner join supplier d on a.supplier_id = d.id
                inner join ekspedisi e on a.transporter_id = e.id
                inner join 
                    (
                    SELECT receive_id, COUNT(item_code) total_item FROM
                    (SELECT DISTINCT receive_id, item_code FROM receive_detail)s
                    GROUP BY receive_id
                    )f on a.id = f.receive_id
                ORDER by a.receive_number desc";
        $query = $this->db->query($sql);
        return $query;
    }

    public function getReceive($ib_no = null)
    {
        $sql = "SELECT a.*,
                b.id as supplier_id, b.code as supplier_code, b.name as supplier_name,
                c.id as transporter_id, d.putaway_number,
                c.code as ekspedisi_code, c.name as ekspedisi_name
                FROM receive_header a
                LEFT JOIN supplier b on a.supplier_id = b.id
                LEFT JOIN ekspedisi c on a.transporter_id = c.id
                LEFT JOIN putaway_header d on a.id = d.receive_id";

        $arr_where = array();

        if ($ib_no != null) {
            $sql .= " WHERE a.receive_number = ?";
            $arr_where[] = $ib_no;
        }


        $query = $this->db->query($sql, $arr_where);
        return $query;
    }


    public function getReceiveDetail($ib_no = null)
    {
        $sql = "SELECT a.*, b.item_name, c.receive_date 
        FROM receive_detail a
        INNER JOIN master_item b on a.item_code = b.item_code
        INNER JOIN receive_header c on a.receive_id = c.id";
        $arr_where = array();
        if ($ib_no != null) {
            $sql .= " WHERE a.receive_number = ?";
            $arr_where[] = $ib_no;
        }

        $sql .= " ORDER BY a.id ASC";
        $query = $this->db->query($sql, $arr_where);
        return $query;
    }

    // public function generate_lpn()
    // {
    //     // Dapatkan nomor LPN terakhir berdasarkan urutan tanggal
    //     $last_lpn = $this->get_last_lpn();

    //     // Tentukan nomor LPN berikutnya
    //     if ($last_lpn) {
    //         $last_number = (int)substr($last_lpn->lpn_number, strlen($this->lpn_prefix));
    //         $new_number = $last_number + 1;
    //     } else {
    //         $new_number = 1; // Jika belum ada nomor, mulai dari 1
    //     }

    //     // Format LPN dengan zero padding
    //     return sprintf('%s%05d', $this->lpn_prefix, $new_number);
    // }

    // /**
    //  * Get the last generated LPN
    //  * @return object|null Last LPN row or null if no LPN found
    //  */
    // private function get_last_lpn()
    // {
    //     $this->db->select_max('lpn_number');
    //     $query = $this->db->get('receive_detail');
    //     return $query->row();
    // }
}
