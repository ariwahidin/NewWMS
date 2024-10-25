<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['inbound_m', 'outbound_m', 'user_m', 'ekspedisi_m', 'factory_m', 'dashboard_m']);
        is_not_logged_in();
    }

    public function render($view, array $data = null)
    {
        $this->load->view('template/header');
        $this->load->view($view, $data);
        $this->load->view('template/footer');
    }

    public function index()
    {
        $mein_db = $this->load->database('mein', TRUE);
        $mein_query = $mein_db->query("SELECT 'MEIN' AS WMS_CODE, SHIPMENT_ID, TRAILING_STS, LEADING_STS, CUSTOMER, SHIP_TO, SHIP_TO_ADDRESS1, SHIP_TO_NAME, ACTUAL_SHIP_DATE_TIME, SHIP_TO_CITY
        FROM SHIPMENT_HEADER 
        WHERE LEADING_STS = '900' AND ACTUAL_SHIP_DATE_TIME >= DATEADD(DAY, -7, GETDATE())");

        $asics_db = $this->load->database('asics', TRUE);
        $asics_query = $asics_db->query("SELECT 'ASICS' AS WMS_CODE, SHIPMENT_ID, TRAILING_STS, LEADING_STS, CUSTOMER, SHIP_TO, SHIP_TO_ADDRESS1, SHIP_TO_NAME, ACTUAL_SHIP_DATE_TIME, SHIP_TO_CITY
        FROM SHIPMENT_HEADER 
        WHERE LEADING_STS = '900' AND ACTUAL_SHIP_DATE_TIME >= DATEADD(DAY, -7, GETDATE())");

            // Convert result objects to array
        $mein_results = $mein_query->result_array();
        $asics_results = $asics_query->result_array();

        // Gabungkan semua data shipment
        $all_shipments = array_merge($mein_results, $asics_results);

        // Dump all shipment data
        // var_dump($all_shipments);

        $data = array(
            'shipments' => $all_shipments
        );

        $this->render('dashboard/index', $data);
    }

    // public function getUserProses()
    // {
    //     $data = array(
    //         'inbound' => $this->dashboard_m->getResumeUserInbound(),
    //         'outbound' => $this->dashboard_m->getResumeUserOutbound(),
    //         'user_inbound' => $this->dashboard_m->getUserInboundActivity(),
    //         'user_outbound' => $this->dashboard_m->getUserOutbound()
    //     );

    //     $response = array(
    //         'success' => true,
    //         'table_user_proses' => $this->load->view('dashboard/table_user_proses', $data, true)
    //     );
    //     echo json_encode($response);
    // }

    // public function getAllProccessInbound()
    // {
    //     $inbound = $this->inbound_m->getAllInboundProccess();
    //     $data = array(
    //         'inbound' => $inbound
    //     );
    //     $this->load->view('dashboard/table_inbound', $data);
    // }

    // public function getAllProccessOutbound()
    // {
    //     $outbound = $this->outbound_m->getAllOutboundProccess();
    //     $data = array(
    //         'outbound' => $outbound
    //     );
    //     $this->load->view('dashboard/table_outbound', $data);
    // }

    // public function getPresentaseInbound()
    // {
    //     $inbound = $this->inbound_m->getPresentaseInbound()->row();
    //     $response = array(
    //         'data' => $inbound,
    //         'man_power' => $this->getSummaryManPowerInbound()
    //     );
    //     echo json_encode($response);
    // }

    // public function getPresentaseOutbound()
    // {
    //     $outbound = $this->outbound_m->getPresentaseOutbound()->row();
    //     $response = array(
    //         'data' => $outbound,
    //         'man_power' => $this->getSummaryManPowerOutbound()
    //     );
    //     echo json_encode($response);
    // }

    // public function getSummaryManPowerOutbound()
    // {
    //     $result = $this->dashboard_m->getUserOutboundRangeDate();
    //     $user_active = 0;
    //     foreach ($result->result() as $data) {
    //         $arr_pl = array();
    //         $pl = getStatusProsesUserOutbound($data->user_id);
    //         foreach ($pl->result() as $p) {

    //             if ($p->proses_status == 'active') {
    //                 array_push($arr_pl, $p->pl_no);
    //             }
    //         }


    //         $pl_no = implode(", ", $arr_pl);

    //         if (count($arr_pl) > 0) {
    //             $user_active += 1;
    //         }
    //     }

    //     $data = array(
    //         'total_plan' => $result->num_rows(),
    //         'user_active' => $user_active
    //     );
    //     return $data;
    // }


    // public function getSummaryManPowerInbound()
    // {
    //     $result = $this->dashboard_m->getUserInboundRangeDate();
    //     $user_active = 0;
    //     foreach ($result->result() as $data) {
    //         $arr_sj = array();
    //         $sj = getStatusProsesUserInbound($data->user_id);
    //         foreach ($sj->result() as $p) {

    //             if ($p->proses_status == 'active') {
    //                 array_push($arr_sj, $p->no_sj);
    //             }
    //         }


    //         $pl_no = implode(", ", $arr_sj);

    //         if (count($arr_sj) > 0) {
    //             $user_active += 1;
    //         }
    //     }

    //     $data = array(
    //         'total_plan' => $result->num_rows(),
    //         'user_active' => $user_active
    //     );
    //     return $data;
    // }

    // public function getMonthlyInbound()
    // {
    //     $monthYear = $this->input->post('month');
    //     $dates = generateDates($monthYear);

    //     $dateParts = explode('-', $monthYear);
    //     $year = $dateParts[0];
    //     $month = $dateParts[1];

    //     // Query untuk mendapatkan total qty berdasarkan bulan dan tahun yang dipilih
    //     $sql = "SELECT 
    //         SUM(qty) AS total_qty,
    //         FORMAT(activity_date, 'd-MMM') AS formatted_date,
    //         CONVERT(date, activity_date) AS activity_date
    //     FROM tb_trans
    //     WHERE 
    //     YEAR(activity_date) = ? AND
    //     MONTH(activity_date) = ?
    //     GROUP BY activity_date
    //     ORDER BY activity_date ASC";
    //     $query = $this->db->query($sql, array($year, $month));
    //     $result = $query->result_array();

    //     foreach ($dates as $key => $val) {
    //         $found = false;
    //         foreach ($result as $k => $v) {
    //             if ($v['activity_date'] == $val) {
    //                 $dates[$key] = $v;
    //                 $found = true;
    //                 break; // Menghentikan loop jika ditemukan kecocokan untuk menghemat waktu
    //             }
    //         }

    //         if (!$found) {
    //             $dates[$key] = array(
    //                 'total_qty' => 0,
    //                 'formatted_date' => date('d-M', strtotime($val)),
    //                 'activity_date' => $val
    //             );
    //         }
    //     }

    //     $response = array(
    //         'success' => true,
    //         'inbound' => $dates
    //     );

    //     echo json_encode($response);
    // }

    // public function getMonthlyOutbound()
    // {
    //     $monthYear = $this->input->post('month');
    //     $dates = generateDates($monthYear);

    //     $dateParts = explode('-', $monthYear);
    //     $year = $dateParts[0];
    //     $month = $dateParts[1];

    //     // Query untuk mendapatkan total qty berdasarkan bulan dan tahun yang dipilih
    //     $sql = "SELECT 
    //         SUM(CONVERT(INT,tot_qty)) AS total_qty,
    //         FORMAT(activity_date, 'd-MMM') AS formatted_date,
    //         CONVERT(date, activity_date) AS activity_date
    //     FROM pl_h
    //     WHERE 
    //     YEAR(activity_date) = ? AND
    //     MONTH(activity_date) = ?
    //     GROUP BY activity_date
    //     ORDER BY activity_date ASC";
    //     $query = $this->db->query($sql, array($year, $month));
    //     $result = $query->result_array();

    //     foreach ($dates as $key => $val) {
    //         $found = false;
    //         foreach ($result as $k => $v) {
    //             if ($v['activity_date'] == $val) {
    //                 $dates[$key] = $v;
    //                 $found = true;
    //                 break; // Menghentikan loop jika ditemukan kecocokan untuk menghemat waktu
    //             }
    //         }

    //         if (!$found) {
    //             $dates[$key] = array(
    //                 'total_qty' => 0,
    //                 'formatted_date' => date('d-M', strtotime($val)),
    //                 'activity_date' => $val
    //             );
    //         }
    //     }

    //     $response = array(
    //         'success' => true,
    //         'outbound' => $dates
    //     );

    //     echo json_encode($response);
    // }
}
