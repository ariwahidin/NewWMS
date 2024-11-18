<?php
class Shipment_m extends CI_Model
{

    public function getShipmentNumber()
    {
        // Generate nomor surat jalan dengan format custom SPKASYYMMXXXX
        $prefix = 'OB'; // Awalan tetap
        $currentYearMonth = date('ym'); // Format tahun dan bulan, misalnya 2410 untuk Oktober 2024

        // Mencari nomor urut terakhir dari bulan ini
        $sql = "SELECT TOP 1 shipment_number FROM shipment_header 
                            WHERE shipment_number LIKE ? 
                            ORDER BY shipment_number DESC";
        $lastEntry = $this->db->query($sql, array($prefix . $currentYearMonth . '%'))->row();

        if ($lastEntry) {
            // Ambil 4 digit terakhir dari nomor surat jalan terakhir
            $lastNumber = (int)substr($lastEntry->shipment_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Tambahkan 1 dan format dengan 4 digit
        } else {
            // Jika belum ada nomor surat jalan bulan ini, mulai dari 0001
            $newNumber = '0001';
        }

        // Gabungkan prefix, tahun-bulan, dan nomor urut baru
        $nomorSuratJalan = $prefix . $currentYearMonth . $newNumber;
        return $nomorSuratJalan;
    }

    public function getShipment($shipment_number = null, $is_confirm = null)
    {
        $sql = "select a.id, a.shipment_number, a.created_at, b.customer_name, b.ship_to_city as city,
                c.name as trucker_name, a.is_complete, isnull(d.total_item, 0) as total_item, isnull(d.total_qty_req, 0) as total_qty_req, isnull(e.qty_pick, 0) as qty_pick,
                a.created_by, a.shipment_date, a.truck_no, a.driver_name, a.driver_phone, a.sj_number, a.ship_reff,
                a.start_loading, a.finish_loading, a.print_do_date, a.print_do_time, a.truck_type, a.truck_arival_time,
                a.remarks, b.ship_to, b.customer_name, b.ship_to_address1, b.ship_to_city, a.transporter_id,
                c.code as ekspedisi_code, c.name as ekspedisi_name, a.customer_id, a.ship_request_date
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
                WHERE a.is_cross_docking = 'N'";
        $where = array();

        $isConfrim = "";
        
        if ( $shipment_number == null && $is_confirm == null) {
            $isConfrim = " AND a.is_complete = 'N'";
        }

        $sql .= $isConfrim;

        if ($shipment_number != null) {
            $sql .= " AND a.shipment_number = ?";
            $where[] = $shipment_number;
        }

        $sql .= " order by a.created_at desc";

        $query = $this->db->query($sql, $where);
        return $query;
    }

    public function getShipmentDetail($ob_no = null)
    {
        $sql = "SELECT a.*, b.item_name, c.shipment_date 
        FROM shipment_detail a
        INNER JOIN master_item b on a.item_code = b.item_code
        INNER JOIN shipment_header c on a.shipment_id = c.id";
        $arr_where = array();
        if ($ob_no != null) {
            $sql .= " WHERE a.shipment_number = ?";
            $arr_where[] = $ob_no;
        }

        $sql .= " ORDER BY a.id ASC";
        $query = $this->db->query($sql, $arr_where);
        return $query;
    }

    public function getAllItemAvailable()
    {
        $sql = "select a.*, b.available
                from master_item a
                inner join (
                        select item_code, sum(available) as available 
                        from inventory 
                        where is_pick = 'Y'
                        and available > 0
                        group by item_code
                        )b on a.item_code = b.item_code";
        $query = $this->db->query($sql);
        return $query;
    }
}
