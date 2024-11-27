<?php
class Shipping_load_m extends CI_Model
{

    // public function getPickingDetailByShipment($shipment_number, $item_code)
    // {
    //     $sql = "SELECT a.*, b.qty_in, b.qty_uom, b.uom
    //             FROM picking_detail a
    //             LEFT JOIN shipment_detail b ON a.shipment_number = b.shipment_number AND a.item_code = b.item_code
    //             WHERE a.shipment_number = ?
    //             AND a.item_code = ?";
    //     $query = $this->db->query($sql, array($shipment_number, $item_code));
    //     return $query;
    // }

    // public function getPackingDetailByShipment($shipment_number)
    // {
    //     $sql = "SELECT * FROM packing_detail WHERE shipment_number =?";
    //     $query = $this->db->query($sql, array($shipment_number));
    //     return $query;
    // }

    // public function checkPackingIsFull($shipment_number, $item_code)
    // {
    //     // $sql = "select a.item_code, sum(a.qty) as qty_pick, 
    //     // isnull(SUM(b.qty), 0) as qty_pack 
    //     // from picking_detail a
    //     // left join packing_detail b on a.shipment_number = b.shipment_number and a.item_code = b.item_code
    //     // where a.shipment_number = ?
    //     // AND a.item_code = ?
    //     // group by a.item_code";
    //     // $query = $this->db->query($sql, array($shipment_number, $item_code));
    //     // return $query;
    //     return $this->getPickItemsByShipment($shipment_number, $item_code);
    // }

    // public function getPickItemsByShipment($shipment_number, $item_code = null)
    // {
    //     $sql = "with shipment as (
    //                 select 
    //                     item_code, 
    //                     sum(qty_in) as qty_in,
    //                     qty_uom,
    //                     uom 
    //                 from 
    //                     shipment_detail 
    //                 where 
    //                     shipment_number = ?
    //                 group by 
    //                     item_code, 
    //                     uom,
    //                     qty_uom
    //             )

    //             select a.item_code,  a.qty_pick, 
    //                     isnull(SUM(b.qty), 0) as qty_pack,
    //                     (select qty_in from shipment where item_code = a.item_code) as qty_in,
    //                     (select qty_uom from shipment where item_code = a.item_code) as qty_uom,
    //                     (select uom from shipment where item_code = a.item_code) as uom
    //                     from (
    //                         select shipment_number, item_code, sum(qty) as qty_pick from shipment_detail a
    //                         where a.shipment_number = ?
    //                         group by a.item_code, a.qty, a.shipment_number
    //                         ) a
    //                     left join packing_detail b on a.shipment_number = b.shipment_number and a.item_code = b.item_code
    //                     where a.shipment_number = ?";

    //     if ($item_code != null) {
    //         $sql .= " AND a.item_code = ? ";
    //     }

    //     $sql .= " group by a.item_code, a.qty_pick, a.shipment_number";

    //     $where = array($shipment_number, $shipment_number, $shipment_number);

    //     if ($item_code != null) {
    //         array_push($where, $item_code);
    //     }

    //     $query = $this->db->query($sql, $where);
    //     return $query;
    // }
}
