<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Picking_m extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_m');
        $this->load->model('trans_m');
    }

    public function getPickingList($picking_number = null)
    {
        $sql = "select a.id, f.id as picking_id, f.picking_number, a.shipment_number, f.created_at, b.customer_name, b.ship_to_city as city,
                c.name as trucker_name, a.is_complete, isnull(d.total_item, 0) as total_item, isnull(d.total_qty_req, 0) as total_qty_req, isnull(e.qty_pick, 0) as qty_pick,
                f.created_by, a.shipment_date, a.truck_no, a.driver_name, a.driver_phone, a.sj_number, a.ship_reff,
                a.start_loading, a.finish_loading, a.print_do_date, a.print_do_time, a.truck_type, a.truck_arival_time,
                a.remarks, b.ship_to, b.customer_name, b.ship_to_address1, b.ship_to_city, a.transporter_id,
                c.code as ekspedisi_code, c.name as ekspedisi_name, a.customer_id, f.is_complete as picking_status
                from shipment_header a
                inner join customer b on a.customer_id = b.id
                inner join ekspedisi c on a.transporter_id = c.id
                left join(
                            select shipment_id, count(item_code) as total_item, sum(qty) as total_qty_req
                            from shipment_detail group by shipment_id 
                        )d on a.id = d.shipment_id
                left join(
                            select shipment_id, sum(qty) as qty_pick from picking_detail
                            group by shipment_id
                        )e on a.id = e.shipment_id
                inner join picking_header f on a.id = f.shipment_id";
        $where = array();
        if ($picking_number != null) {
            $sql .= " WHERE f.picking_number = ?";
            $where[] = $picking_number;
        }

        $sql .= " order by a.created_at desc";

        $query = $this->db->query($sql, $where);
        return $query;
    }

    public function getPickingDetail($picking_number = null)
    {
        $sql = "SELECT a.*, b.item_name FROM picking_detail a INNER JOIN master_item b ON a.item_code = b.item_code";

        $where = array();

        if($picking_number != null){
            $sql .= " WHERE a.picking_number = ?";
            $where[] = $picking_number;
        }

        $query = $this->db->query($sql, $where);
        return $query;
    }

    public function getShipmentDetailByPickingNumber($picking_number){
        $sql = "select a.*, b.picking_number, c.item_name
        from shipment_detail a
        inner join picking_header b on a.shipment_id = b.shipment_id
        inner join master_item c on a.item_code = c.item_code";
        $where = array();

        if($picking_number != null){
            $sql .= " WHERE b.picking_number = ?";
            $where[] = $picking_number;
        }

        $query = $this->db->query($sql, $where);

        return $query;
    }
}
