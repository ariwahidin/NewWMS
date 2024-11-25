<?php
class Packing_scan_m extends CI_Model
{

    public function getPickingDetailByShipment($shipment_number, $item_code)
    {
        $sql = "SELECT a.*, b.qty_in, b.qty_uom, b.uom
                FROM picking_detail a
                LEFT JOIN shipment_detail b ON a.shipment_number = b.shipment_number AND a.item_code = b.item_code
                WHERE a.shipment_number = ?
                AND a.item_code = ?";
        $query = $this->db->query($sql, array($shipment_number, $item_code));
        return $query;
    }

    public function getPackingDetailByShipment($shipment_number)
    {
        $sql = "SELECT * FROM packing_detail WHERE shipment_number =?";
        $query = $this->db->query($sql, array($shipment_number));
        return $query;
    }

    public function checkPackingIsFull($shipment_number, $item_code)
    {
        $sql = "select a.item_code, sum(a.qty) as qty_pick, 
        isnull(SUM(b.qty), 0) as qty_pack 
        from picking_detail a
        left join packing_detail b on a.shipment_number = b.shipment_number and a.item_code = b.item_code
        where a.shipment_number = ?
        AND a.item_code = ?
        group by a.item_code";
        $query = $this->db->query($sql, array($shipment_number, $item_code));
        return $query;
    }

    public function getPickItemsByShipment($shipment_number)
    {
        $sql = "select a.item_code, sum(a.qty) as qty_pick, 
        isnull(SUM(b.qty), 0) as qty_pack 
        from picking_detail a
        left join packing_detail b on a.shipment_number = b.shipment_number and a.item_code = b.item_code
        where a.shipment_number = ?
        group by a.item_code";
        $query = $this->db->query($sql, array($shipment_number));
        return $query;
    }
}
