<?php
class Shipping_load_m extends CI_Model
{

    public function getShipment($shipment)
    {
        $sql = "select a.*, b.name as transporter_name 
                from shipment_header a
                inner join ekspedisi b on a.transporter_id = b.id
                where a.shipment_number = ?";
        $query = $this->db->query($sql, array($shipment));
        return $query;
    }

    public function getCartonList($shipment_number){
        $sql = "select a.shipment_number, a.carton, 
                count(a.carton) as qty_carton, isnull(b.qty_carton_in, 0) as qty_carton_in
                from packing_detail a
                left join (
                        select shipment_number, carton_no, sum(qty_carton) as qty_carton_in 
                        from shipping_load_d
                        group by shipment_number, carton_no
                )b on a.shipment_number = b.shipment_number and a.carton = b.carton_no
                where a.shipment_number = ?
                and a.is_sealed = 'Y'
                group by a.shipment_number, a.carton, qty_carton_in
                order by carton ASC";
        $query = $this->db->query($sql, array($shipment_number));
        return $query;
    }

    public function getContainerDetail($shipment_number)
    {
        $sql = "select * 
                from shipping_load_d
                where shipment_number = ?";
        $query = $this->db->query($sql, array($shipment_number));
        return $query;
    }

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
