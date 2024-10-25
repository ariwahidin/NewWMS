<?php
class Order_m extends CI_Model
{

    // Fungsi untuk mengambil data dari Asics dan memasukkannya ke list_do di database default
    public function pullOrderAsicsToListDO()
    {
        // Koneksi ke database Asics
        $asics_db = $this->load->database('asics', TRUE);  // TRUE untuk membuka koneksi database terpisah

        // SQL untuk mengambil data dari database Asics
        $sql = "SELECT
                    WAREHOUSE,
                    SHIPMENT_ID,
                    CUSTOMER, 
                    CUSTOMER_NAME,
                    SHIP_TO,
                    SHIP_TO_NAME,
                    SHIP_TO_ADDRESS1,
                    SHIP_TO_ADDRESS2,
                    SHIP_TO_ADDRESS3,
                    SHIP_TO_CITY,
                    SHIP_TO_POSTAL_CODE,
                    SHIP_TO_PHONE_NUM,
                    SHIP_TO_STATE,
                    SHIP_TO_COUNTRY,
                    SHIP_TO_EMAIL_ADDRESS,
                    REQUESTED_DELIVERY_DATE,
                    SCHEDULED_SHIP_DATE,
                    PLANNED_SHIP_DATE,
                    ACTUAL_SHIP_DATE_TIME
                FROM shipment_header
                WHERE LEADING_STS = '900' 
                AND ACTUAL_SHIP_DATE_TIME >= DATEADD(DAY, -7, GETDATE())";

        // Jalankan query di database Asics
        $query = $asics_db->query($sql);

        // Jika data ada, masukkan ke list_do jika SHIPMENT_ID belum pernah ada
        if ($query->num_rows() > 0) {
            $result = $query->result_array();

            foreach ($result as $row) {
                // Cek apakah SHIPMENT_ID sudah ada di tabel list_do di database default
                $shipment_id = $row['SHIPMENT_ID'];

                // Gunakan koneksi database default (tidak perlu load lagi, ini database aktif)
                $this->db->from('list_do');
                $this->db->where('SHIPMENT_ID', $shipment_id);
                $exists = $this->db->count_all_results();

                // Jika SHIPMENT_ID belum ada, insert ke tabel list_do
                if ($exists == 0) {
                    $data = array(
                        'warehouse' => $row['WAREHOUSE'],
                        'shipment_id' => $row['SHIPMENT_ID'],
                        'customer' => $row['CUSTOMER'],
                        'customer_name' => $row['CUSTOMER_NAME'],
                        'ship_to' => $row['SHIP_TO'],
                        'ship_to_name' => $row['SHIP_TO_NAME'],
                        'ship_to_address1' => $row['SHIP_TO_ADDRESS1'],
                        'ship_to_address2' => $row['SHIP_TO_ADDRESS2'],
                        'ship_to_address3' => $row['SHIP_TO_ADDRESS3'],
                        'ship_to_city' => $row['SHIP_TO_CITY'],
                        'ship_to_postal_code' => $row['SHIP_TO_POSTAL_CODE'],
                        'ship_to_phone_num' => $row['SHIP_TO_PHONE_NUM'],
                        'ship_to_state' => $row['SHIP_TO_STATE'],
                        'ship_to_country' => $row['SHIP_TO_COUNTRY'],
                        'ship_to_email_address' => $row['SHIP_TO_EMAIL_ADDRESS'],
                        'scheduled_ship_date' => $row['SCHEDULED_SHIP_DATE'],
                        'planned_ship_date' => $row['PLANNED_SHIP_DATE'],
                        'actual_ship_date' => $row['ACTUAL_SHIP_DATE_TIME'],
                        'sync_by' => $_SESSION['user_data']['username'],
                        'created_by' => $_SESSION['user_data']['username']
                    );

                    // Insert data ke tabel list_do di database default
                    $this->db->insert('list_do', $data);
                }


                //Cek customer pada table customer
                $this->db->from('customer');
                $this->db->where('customer', $row['CUSTOMER']);
                $customer = $this->db->count_all_results();
                if ($customer == 0) {
                    $this->createCustomer($row);
                }

            }
        }

    }


    public function createCustomer($row)
    {
        $data = array(
            'customer' => $row['CUSTOMER'],
            'customer_name' => $row['CUSTOMER_NAME'],
            'ship_to' => $row['SHIP_TO'],
            'ship_to_name' => $row['SHIP_TO_NAME'],
            'ship_to_address1' => $row['SHIP_TO_ADDRESS1'],
            'ship_to_address2' => $row['SHIP_TO_ADDRESS2'],
            'ship_to_address3' => $row['SHIP_TO_ADDRESS3'],
            'ship_to_city' => $row['SHIP_TO_CITY'],
            'ship_to_postal_code' => $row['SHIP_TO_POSTAL_CODE'],
            'ship_to_phone_num' => $row['SHIP_TO_PHONE_NUM'],
            'ship_to_state' => $row['SHIP_TO_STATE'],
            'ship_to_country' => $row['SHIP_TO_COUNTRY'],
            'ship_to_email_address' => $row['SHIP_TO_EMAIL_ADDRESS'],
            'created_by' => $_SESSION['user_data']['username']
        );
        $this->db->insert('customer', $data);
    }

    public function pullOrderAsicsToListDO_detail()
    {
        // Koneksi ke database Asics
        $asics_db = $this->load->database('asics', TRUE);  // TRUE untuk membuka koneksi database terpisah

        // SQL untuk mengambil data dari database Asics
        $sql = "SELECT
                SHIPMENT_ID,
                INTERNAL_SHIPMENT_LINE_NUM,
                ERP_ORDER_LINE_NUM,
                WAREHOUSE,
                ITEM,
                ITEM_DESC,
                REQUESTED_QTY,
                TOTAL_QTY,
                QUANTITY_UM,
                CARRIER_TYPE,
                PLANNED_SHIP_DATE,
                ITEM_LENGTH,
                ITEM_WIDTH,
                ITEM_COLOR,
                ITEM_SIZE,
                ORIGINAL_ITEM_ORDERED,
                ITEM_VOLUME,
                TOTAL_WEIGHT
                FROM shipment_detail
                WHERE SHIPMENT_ID IN (SELECT DISTINCT SHIPMENT_ID FROM shipment_header
                WHERE LEADING_STS = '900' AND ACTUAL_SHIP_DATE_TIME >= DATEADD(DAY, -7, GETDATE()))";

        // Jalankan query di database Asics
        $query = $asics_db->query($sql);

        // Jika data ada, masukkan ke list_do jika SHIPMENT_ID belum pernah ada
        if ($query->num_rows() > 0) {
            $result = $query->result_array();

            foreach ($result as $row) {
                // Cek apakah SHIPMENT_ID sudah ada di tabel list_do di database default
                $shipment_id = $row['SHIPMENT_ID'];
                $internal_shipment_line_num = $row['INTERNAL_SHIPMENT_LINE_NUM'];

                // Gunakan koneksi database default (tidak perlu load lagi, ini database aktif)
                $this->db->from('list_do_item');
                $this->db->where('shipment_id', $shipment_id);
                $this->db->where('internal_shipment_line_num', $internal_shipment_line_num);
                $exists = $this->db->count_all_results();

                // Jika SHIPMENT_ID belum ada, insert ke tabel list_do
                if ($exists == 0) {
                    $data = array(
                        'shipment_id' => $row['SHIPMENT_ID'],
                        'internal_shipment_line_num' => $row['INTERNAL_SHIPMENT_LINE_NUM'],
                        'erp_order_line_num' => $row['ERP_ORDER_LINE_NUM'],
                        'warehouse' => $row['WAREHOUSE'],
                        'item' => $row['ITEM'],
                        'item_desc' => $row['ITEM_DESC'],
                        'requested_qty' => $row['REQUESTED_QTY'],
                        'total_qty' => $row['TOTAL_QTY'],
                        'quantity_um' => $row['QUANTITY_UM'],
                        'carrier_type' => $row['CARRIER_TYPE'],
                        'planned_ship_date' => $row['PLANNED_SHIP_DATE'],
                        'item_length' => $row['ITEM_LENGTH'],
                        'item_width' => $row['ITEM_WIDTH'],
                        'item_color' => $row['ITEM_COLOR'],
                        'item_size' => $row['ITEM_SIZE'],
                        'original_item_ordered' => $row['ORIGINAL_ITEM_ORDERED'],
                        'item_volume' => $row['ITEM_VOLUME'],
                        'total_weight' => $row['TOTAL_WEIGHT']
                    );

                    // Insert data ke tabel list_do di database default
                    $this->db->insert('list_do_item', $data);
                }
            }
        }
    }

    public function getListDO()
    {
        $sql = "select * from list_do";
        $query = $this->db->query($sql);
        return $query;
    }

    public function getOrderType()
    {
        $sql = "select * from order_type";
        $query = $this->db->query($sql);
        return $query;
    }

    public function getDO($spk = null)
    {


        $sql = "SELECT a.*, b.id as order_type_id, b.code as order_type_code, b.name as order_type_name,
                c.id as transporter_id, c.code as ekspedisi_code, c.name as ekspedisi_name
                FROM order_h a
                LEFT JOIN order_type b on a.order_type = b.id
                LEFT JOIN ekspedisi c on a.transporter = c.id";

        $arr_where = array();

        if ($spk != null) {
            $sql .= " WHERE a.spk_number = ?";
            $arr_where[] = $spk;
        }


        $query = $this->db->query($sql, $arr_where);
        return $query;
    }

    public function getOrderDetail($spk = null)
    {
        $sql = "SELECT a.*, b.customer_name, b.ship_to_name, b.ship_to_city  FROM order_d a
                INNER JOIN list_do b on a.shipment_id = b.shipment_id";
        $arr_where = array();
        if ($spk != null) {
            $sql .= " WHERE a.spk_number = ?";
            $arr_where[] = $spk;
        }
        $query = $this->db->query($sql, $arr_where);
        return $query;
    }

    public function getListOrder($spk = null)
    {
        $sql = "SELECT a.id, a.spk_number, a.order_date, a.spk_date, b.truck_name, 
        '' as total_drop, '' as qty_ori, '' as qty_loading, '' as total_dn,
        c.code as transporter_code, c.name as transporter_name, d.name as order_type,
        start_loading, finish_loading, truck_arival_date, truck_arival_time, remarks, truck_no
        FROM order_h a
        LEFT JOIN truck_type b ON a.truck_type = b.truck_name
        LEFT JOIN ekspedisi c ON a.transporter = c.id
        LEFT JOIN order_type d ON a.order_type = d.id";
        $arr_where = array();
        if ($spk != null) {
            $sql .= " WHERE a.spk_number = ?";
            $arr_where[] = $spk;
        }
        $query = $this->db->query($sql, $arr_where);
        return $query;
    }

    public function getListDOItem($spk = null)
    {
        $sql = "SELECT a.id, a.spk_number, a.shipment_id as do_number, b.ship_to, b.ship_to_name, 
        d.total_cbm, c.total_qty as total_pcs, '' as total_box, '' as total_volume, b.ship_to_city as city
        FROM order_d a 
        INNER JOIN list_do b ON a.shipment_id = b.shipment_id
        INNER JOIN (SELECT shipment_id, SUM(total_qty) as total_qty
        FROM list_do_item
        -- WHERE shipment_id = 'IF-24439596'
        GROUP BY shipment_id) c ON a.shipment_id = c.shipment_id
        LEFT JOIN order_h d ON a.spk_number = d.spk_number";
        $arr_where = array();
        if ($spk != null) {
            $sql .= " WHERE a.spk_number = ?";
            $arr_where[] = $spk;
        }
        $query = $this->db->query($sql, $arr_where);
        return $query;
    }
}
