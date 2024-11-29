<?php
class Picking_scan_m extends CI_Model
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

    public function getSumReqItem($shipment_number, $item_code)
    {
        $sql = "select shipment_number, item_code, sum(qty) as qty_req
                from shipment_detail 
                where shipment_number = ? 
                and item_code = ?
                group by shipment_number, item_code";
        $query = $this->db->query($sql, array($shipment_number, $item_code));
        return $query;
    }

    public function getSumPickItem($shipment_number, $item_code)
    {
        $sql = "select shipment_number, item_code, sum(qty) as qty_pick
                from picking_detail 
                where shipment_number = ? 
                and item_code = ?
                group by shipment_number, item_code";
        $query = $this->db->query($sql, array($shipment_number, $item_code));
        return $query;
    }

    public function getPickItemsToPick($shipment_number)
    {
        $sql = "select a.shipment_number, a.item_code, a.qty_in, a.qty_uom, a.qty as qty_req, a.uom, isnull(c.qty_pick, 0) as qty_pick, 
                b.is_complete 
                from shipment_detail a
                inner join shipment_header b on a.shipment_number = b.shipment_number
                left join (
                        select shipment_number, item_code, sum(qty) as qty_pick
                        from picking_detail
                        group by shipment_number, item_code
                        )c on a.shipment_number = c.shipment_number and a.item_code = c.item_code
                where a.shipment_number = ?
                and b.is_complete = 'Y'";

        $where = array($shipment_number);

        $query = $this->db->query($sql, $where);
        return $query;
    }

    public function getPickedDetail($shipment_number){
        $this->db->where('shipment_number', $shipment_number);
        $query = $this->db->get('picking_detail');
        return $query;
    }

    public function getInventoryAvailable($item_code){
        $sql = "SELECT * FROM inventory WHERE item_code = ? AND available > 0 AND is_pick = 'Y' ORDER BY available";
        $query = $this->db->query($sql, array($item_code));
        return $query;
    }
}
