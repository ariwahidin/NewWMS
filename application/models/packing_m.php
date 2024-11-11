<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Packing_m extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_m');
        $this->load->model('trans_m');
    }

    public function getPackingList()
    {
        $sql = "select top 1 * from master_item";
        $where = array();
        $query = $this->db->query($sql, $where);
        return $query;
    }

    public function getShipmentComplete($shipment_number)
    {
        $sql = "select a.id as shipment_id, a.shipment_number from shipment_header a
        inner join picking_header b on a.id = b.shipment_id
        WHERE a.shipment_number = ?
        AND b.is_complete = 'Y'";
        $query = $this->db->query($sql, array($shipment_number));
        return $query;
    }

    public function getPackingHeaderByShipment($shipment_number)
    {
        $sql = "SELECT * FROM packing_header WHERE shipment_number = ?";
        $query = $this->db->query($sql, array($shipment_number));
        return $query;
    }

    public function getItemShipment($shipment_number, $item_code)
    {
        $sql = "select item_code, sum(qty) as total_qty from shipment_detail
                WHERE shipment_number = ?
                AND item_code = ?
                GROUP BY item_code";
        $query = $this->db->query($sql, array($shipment_number, $item_code));
        return $query;
    }

    public function getItemPacked($shipment_number, $item_code)
    {
        $sql = "select item_code, sum(qty) as total_qty from packing_detail
                WHERE shipment_number = ?
                AND item_code = ?
                GROUP BY item_code";
        $query = $this->db->query($sql, array($shipment_number, $item_code));
        return $query;
    }

    public function getPackingDetailByShipment($shipment_number)
    {
        $sql = "SELECT * 
                FROM packing_detail
                WHERE shipment_number = ?";
        $query = $this->db->query($sql, array($shipment_number));
        return $query;
    }

    public function deleteItem($id)
    {
        $sql = "DELETE FROM packing_detail WHERE id = ? ";
        $query = $this->db->query($sql, array($id));
        return $query;
    }

    public function getQtyProgress($shipment_number)
    {
        $sql = "SELECT shipment_number, SUM(qty) as req_qty,
                (SELECT SUM(qty) as cur_qty FROM packing_detail WHERE shipment_number = a.shipment_number GROUP BY shipment_number) as cur_qty
                FROM shipment_detail a
                WHERE shipment_number = ?
                GROUP BY shipment_number";
        $query = $this->db->query($sql, array($shipment_number));
        return $query;
    }

    public function getItemShipmentByShipment($shipment_number)
    {
        $sql = "SELECT * FROM shipment_detail WHERE shipment_number = ? ";
        $query = $this->db->query($sql, array($shipment_number));
        return $query;
    }

    public function getSummaryPacking()
    {
        $sql = "SELECT id, packing_number, shipment_number, created_at,
                (SELECT SUM(qty) as total_qty FROM packing_detail WHERE shipment_number = a.shipment_number) as total_qty,
                (SELECT COUNT(*) as total_ctn FROM (SELECT DISTINCT ctn FROM packing_detail 
                WHERE shipment_number = a.shipment_number)pd) as total_ctn
                FROM packing_header a";
        $query = $this->db->query($sql);
        return $query;
    }
}
