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
        $sql = "SELECT * FROM picking_list_view ";
        $where = array();
        if ($picking_number != null) {
            $sql .= " WHERE picking_number = ?";
            $where[] = $picking_number;
        }

        $sql .= " order by created_at desc";

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
