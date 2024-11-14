<?php
class Putaway_m extends CI_Model
{
    public function putawayList($isConfirm = null)
    {
        $sql = "select e.putaway_number, a.receive_number, a.receive_date, a.po_number, d.name as supplier_name, a.truck_no,
                a.is_complete, e.created_by, e.is_complete as putaway_status,
                isnull(b.total_qty, 0) as total_qty, isnull(c.qty_putaway, 0) as qty_putaway, '' as status, a.receiving_status
                from receive_header a
                inner join putaway_header e on a.receive_number = e.receive_number
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
					where b.to_location != '' and b.to_location is not null
                    group by a.receive_number, a.putaway_number
                    ) c on a.receive_number = c.receive_number
                inner join supplier d on a.supplier_id = d.id";

        if ($isConfirm == null) {
            $sql .= " WHERE e.is_complete = 'N'";
        }

        $sql .= " ORDER by e.putaway_number desc";
        $query = $this->db->query($sql);
        return $query;
    }

    public function getPutaway($put_no = null)
    {
        $sql = "SELECT a.*, e.putaway_number, e.created_by as putaway_by,
                b.id as supplier_id, b.code as supplier_code, b.name as supplier_name, e.is_complete as putaway_status,
                c.id as transporter_id, 
                c.code as ekspedisi_code, c.name as ekspedisi_name
                FROM receive_header a
                INNER JOIN supplier b on a.supplier_id = b.id
                INNER JOIN ekspedisi c on a.transporter_id = c.id
                INNER JOIN putaway_header e on a.receive_number = e.receive_number";

        $arr_where = array();

        if ($put_no != null) {
            $sql .= " WHERE e.putaway_number = ?";
            $arr_where[] = $put_no;
        }


        $query = $this->db->query($sql, $arr_where);
        return $query;
    }

    public function getReceiveDetail($ib_no = null)
    {
        $sql = "SELECT a.*, b.item_name, c.receive_date, d.to_location as putaway_location
        FROM receive_detail a
        INNER JOIN master_item b on a.item_code = b.item_code
        INNER JOIN receive_header c on a.receive_id = c.id
        LEFT JOIN putaway_detail d on a.id = d.receive_detail_id";
        $arr_where = array();
        if ($ib_no != null) {
            $sql .= " WHERE a.receive_number = ?";
            $arr_where[] = $ib_no;
        }

        $sql .= " ORDER BY a.id ASC";
        // var_dump($sql);
        // die;
        $query = $this->db->query($sql, $arr_where);
        return $query;
    }

    public function getPutawayHeaderByReceive($receive_number = null)
    {
        $sql = "select a.id, a.receive_number, a.is_complete as receive_status, b.putaway_number, b.is_complete as putaway_status,
                case when a.is_complete = 'Y' and b.is_complete = 'N' then 'Y' else 'N'end is_ready_putaway, c.exp_qty
                from receive_header a 
                left join putaway_header b on a.id = b.receive_id
                left join 
                        (
                        select receive_id, sum(qty) as exp_qty from receive_detail
                        group by receive_id
                        ) c on a.id = c.receive_id
                where a.receive_number = ?";
        $query = $this->db->query($sql, $receive_number);
        return $query;
    }


    public function getReceiveDetailByLpn($receive_number, $lpn)
    {
        // query builder
        $this->db->select('a.*');
        $this->db->from('receive_detail a');
        $this->db->join('receive_header b', 'a.receive_id = b.id');
        $this->db->where('a.receive_number', $receive_number);
        $this->db->where('a.lpn_number', $lpn);
        return $this->db->get();
    }

    public function getItemToPutaway($receive_number, $putaway_number, $lpn_number)
    {
        $sql = "select top 1
                a.id as receive_detail_id, 
                a.receive_number,
                b.putaway_number,
                a.lpn_id,
                a.lpn_number,
                a.item_code,
                a.qty
                from receive_detail a
                inner join putaway_header b on a.receive_id = b.receive_id
                where 
                a.receive_number = ?
                and a.lpn_number = ?
                and b.putaway_number = ?";
        $query = $this->db->query($sql, array($receive_number, $lpn_number, $putaway_number));
        return $query;
    }

    public function getPutawayDetail($putaway_number)
    {
        $sql = "select a.*, b.item_name, d.receive_date, c.expiry_date, c.receive_location
                from putaway_detail a
                inner join master_item b on a.item_code = b.item_code
                left join receive_detail c on a.receive_detail_id = c.id
                left join receive_header d on c.receive_id = d.id
                where a.putaway_number = ? 
                order by a.receive_detail_id asc";
        $query = $this->db->query($sql, array($putaway_number));
        return $query;
    }

    public function getReceivingDetailByPutNo($putaway_number, $receive_detail_id = null)
    {
        $sql = "select a.putaway_number, a.id as putaway_id, 
        b.receive_id, 
        b.id as receive_detail_id, b.item_code, c.item_name, 
        b.qty_in, b.qty_uom, b.uom, b.qty,
        b.receive_location, b.lpn_id, b.lpn_number
        from putaway_header a
        inner join receive_detail b on a.receive_id = b.receive_id
        inner join master_item c on b.item_code = c.item_code
        where putaway_number = ?";

        $where = array();
        $where[] = $putaway_number;
        if ($receive_detail_id != null) {
            $sql .= " and b.id = ?";
            $where[] = $receive_detail_id;
        }

        $sql .= " order by b.id asc";
        $query = $this->db->query($sql, $where);
        return $query;
    }
}
