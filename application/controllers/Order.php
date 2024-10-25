<?php defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['order_m', 'truck_m', 'ekspedisi_m']);
        is_not_logged_in();
    }

    public function render($view, array $data = null)
    {
        $this->load->view('template/header',$data);
        $this->load->view($view, $data);
        $this->load->view('template/footer');
    }

    public function planningOrder()
    {
        $data = array(
            'title' => 'Planning Order',
            'truck' => $this->truck_m->getTruckType(),
            'ekspedisi' => $this->ekspedisi_m->getEkspedisi(),
            'type' => $this->order_m->getOrderType()
        );

        if (isset($_GET['edit']) && isset($_GET['spk'])) {
            $spk = $_GET['spk'];
            $order = $this->order_m->getDO($spk);
            if ($order->num_rows() > 0) {
                $data['order'] = $order->row();
            } else {
                echo "Not Found";
                exit;
            }
        }

        $this->render('order/index', $data);
    }

    public function sync_orders()
    {

        // Panggil method model untuk sinkronisasi data dari Asics ke list_do
        $this->order_m->pullOrderAsicsToListDO();
        $this->order_m->pullOrderAsicsToListDO_detail();

        if ($this->db->affected_rows() > 0) {
            $response = array(
                'success' => true
            );
        } else {
            $response = array(
                'success' => false
            );
        }

        // Tampilkan pesan sukses atau redirect sesuai kebutuhan
        echo json_encode($response);
    }


    public function getOrder()
    {

        // var_dump($_POST);


        $shipment_current = null;

        if (isset($_POST['spk_number'])) {
            $shipment_current = $this->order_m->getOrderDetail($_POST['spk_number'])->result_array();
        }

        $listDO = $this->order_m->getListDO()->result_array();

        $data = array(
            'shipments' => $listDO,
            'shipment_current' => $shipment_current
        );

        echo json_encode($data);
    }

    public function createSpk()
    {
        // var_dump($_POST);
        // var_dump($_SESSION);
        // die;
        $this->load->database();

        // Memulai transaksi
        $this->db->trans_start();

        // Ambil data header dari input POST
        $order_ids = $this->input->post('order_ids');
        $header = $this->input->post('header');

        // Generate nomor surat jalan dengan format custom SPKASYYMMXXXX
        $prefix = 'SPKIT'; // Awalan tetap
        $currentYearMonth = date('ym'); // Format tahun dan bulan, misalnya 2410 untuk Oktober 2024

        // Mencari nomor urut terakhir dari bulan ini
        $sql = "SELECT TOP 1 spk_number FROM order_h 
                    WHERE spk_number LIKE ? 
                    ORDER BY spk_number DESC";
        $lastEntry = $this->db->query($sql, array($prefix . $currentYearMonth . '%'))->row();

        if ($lastEntry) {
            // Ambil 4 digit terakhir dari nomor surat jalan terakhir
            $lastNumber = (int)substr($lastEntry->spk_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Tambahkan 1 dan format dengan 4 digit
        } else {
            // Jika belum ada nomor surat jalan bulan ini, mulai dari 0001
            $newNumber = '0001';
        }

        // Gabungkan prefix, tahun-bulan, dan nomor urut baru
        $nomorSuratJalan = $prefix . $currentYearMonth . $newNumber;

        // Simpan data surat jalan ke database menggunakan raw query
        $insertHeaderSQL = "INSERT INTO order_h (spk_number, load_number, order_date, order_time, spk_date, ship_mode, order_type, 
                truck_arival_date, truck_arival_time, start_loading, finish_loading, dispath_proccess, load_status, 
                transporter, truck_type, truck_no, driver_name, driver_phone, total_cbm, charge_by, remarks, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $this->db->query($insertHeaderSQL, array(
            $nomorSuratJalan,
            $header['loadNumber'],
            $header['orderDate'],
            $header['orderTime'],
            $header['spkDate'],
            $header['shipMode'],
            $header['orderTypeID'],
            ($header['truckArivalDate'] == '') ? null : $header['truckArivalDate'],
            $header['truckArivalTime'],
            $header['startLoading'],
            $header['finishLoading'],
            $header['dispathProccess'],
            $header['loadStatus'],
            $header['transporterID'],
            $header['truckType'],
            $header['truckNo'],
            $header['driverName'],
            $header['driverPhone'],
            $header['totalCBM'],
            $header['chargeBy'],
            $header['remarks'],
            $_SESSION['user_data']['username']
        ));

        $last_id = $this->db->insert_id();

        // Insert order ke tabel order_d menggunakan raw query
        foreach ($order_ids as $order_id) {
            $insertDetailSQL = "INSERT INTO order_d (order_id, spk_number, shipment_id) VALUES (?, ?, ?)";
            $this->db->query($insertDetailSQL, array($last_id, $nomorSuratJalan, $order_id));
        }

        // Menyelesaikan transaksi
        $this->db->trans_complete();

        // Mengecek apakah transaksi berhasil
        if ($this->db->trans_status() === FALSE) {
            // Jika terjadi kesalahan, rollback
            echo json_encode(array('success' => false, 'message' => 'Transaksi gagal.'));
        } else {
            // Kembalikan nomor surat jalan ke frontend
            echo json_encode(array('success' => true, 'nomor_surat_jalan' => $nomorSuratJalan));
        }
    }


    /**
     * Edit data SPK yang sudah ada di database
     * 
     * @param string $spk_number Nomor SPK yang akan diedit
     * 
     * @return json object yang berisi status kesuksesan dan nomor surat jalan yang telah diedit
     */
    public function editSpk()
    {
        $order_ids = $this->input->post('order_ids');
        $header = $this->input->post('header');


        $spk_number = $header['spkNumber'];
        // var_dump($order_ids);
        // var_dump($header);
        // die;

        $this->load->database();

        // Memulai transaksi
        $this->db->trans_start();

        // Ambil data header dari input POST

        // Periksa apakah `spk_number` ada di database
        $sqlCheck = "SELECT id FROM order_h WHERE spk_number = ?";
        $existingSpk = $this->db->query($sqlCheck, array($spk_number))->row();



        if (!$existingSpk) {
            // Jika nomor surat jalan tidak ditemukan
            echo json_encode(array('success' => false, 'message' => 'Nomor SPK tidak ditemukan.'));
            return;
        }


        $spk_id = $existingSpk->id;

        // Update data header di tabel `order_h`
        $updateHeaderSQL = "UPDATE order_h 
        SET load_number = ?, 
            order_date = ?, 
            order_time = ?, 
            spk_date = ?, 
            ship_mode = ?, 
            order_type = ?, 
            truck_arival_date = ?, 
            truck_arival_time = ?, 
            start_loading = ?, 
            finish_loading = ?, 
            dispath_proccess = ?, 
            load_status = ?, 
            transporter = ?, 
            truck_type = ?, 
            truck_no = ?, 
            driver_name = ?, 
            driver_phone = ?, 
            total_cbm = ?, 
            charge_by = ?, 
            remarks = ?, 
            updated_by = ?, 
            updated_at = GETDATE() 
        WHERE id = ?";

        $this->db->query($updateHeaderSQL, array(
            $header['loadNumber'],
            $header['orderDate'],
            $header['orderTime'],
            $header['spkDate'],
            $header['shipMode'],
            $header['orderTypeID'],
            $header['truckArivalDate'],
            $header['truckArivalTime'],
            $header['startLoading'],
            $header['finishLoading'],
            $header['dispathProccess'],
            $header['loadStatus'],
            $header['transporterID'],
            $header['truckType'],
            $header['truckNo'],
            $header['driverName'],
            $header['driverPhone'],
            $header['totalCBM'],
            $header['chargeBy'],
            $header['remarks'],
            $_SESSION['user_data']['username'],
            $spk_id
        ));

        // Hapus semua detail order lama dari tabel `order_d` yang terkait dengan `spk_number`
        $deleteDetailSQL = "DELETE FROM order_d WHERE order_id = ?";
        $this->db->query($deleteDetailSQL, array($spk_id));

        // Insert ulang detail order yang baru
        foreach ($order_ids as $order_id) {
            $insertDetailSQL = "INSERT INTO order_d (order_id, spk_number, shipment_id) VALUES (?, ?, ?)";
            $this->db->query($insertDetailSQL, array($spk_id, $spk_number, $order_id));
        }

        // Menyelesaikan transaksi
        $this->db->trans_complete();

        // Mengecek apakah transaksi berhasil
        if ($this->db->trans_status() === FALSE) {
            // Jika terjadi kesalahan, rollback
            echo json_encode(array('success' => false, 'message' => 'Transaksi gagal.'));
        } else {
            // Kembalikan nomor surat jalan yang telah diedit ke frontend
            echo json_encode(array('success' => true, 'nomor_surat_jalan' => $spk_number));
        }
    }


    public function spkShow()
    {
        $header = $this->order_m->getListOrder($_GET['spk']);
        $detail = $this->order_m->getListDOItem($_GET['spk']);

        // var_dump($detail->result());



        // var_dump($header->row());

        if($header->num_rows() == 0){
            echo "Not Found";
            exit;
        }

        $data = array(
            'header' => $header->row(),
            'detail' => $detail->result()
        );
        $this->load->view('order/spk_design', $data);
    }

    public function listOrder()
    {
        $data = array(
            'order' => $this->order_m->getListOrder()
        );
        $this->render('order/list_order/index', $data);
    }

    public function getListOrder()
    {
        $order = $this->order_m->getListOrder();
        $data = array(
            'order' => $order->result()
        );

        echo json_encode($data);
    }
}
