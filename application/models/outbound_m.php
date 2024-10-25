<?php
class Outbound_m extends CI_Model
{

    function generateTransactionNumber()
    {
        // Set zona waktu ke Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');
        // Ambil tanggal saat ini
        $date = date('ymd');

        // Ambil nomor urut perharinya dari database
        $sql = "SELECT
        ISNULL(MAX(RIGHT(transaction_number, 5)), 0) + 1 AS max_id 
        FROM pl_h 
        WHERE SUBSTRING(transaction_number, 2, 6) = '$date'";

        $query = $this->db->query($sql);
        $row = $query->row();

        $max_id = sprintf("%05s", $row->max_id);

        // Gabungkan informasi dan nomor urut perharinya
        $transaction_number = "O$date$max_id";

        return $transaction_number;
    }

    function createTask($params)
    {
        return $this->db->insert('tb_out_temp', $params);
    }

    function editTask($id, $params)
    {
        $this->db->where(['id' => $id]);
        $this->db->update('tb_out_temp', $params);
    }

    public function getTaskByUser($post = null)
    {
        // var_dump($post);
        // die;
        // if (isset($post['search'])) {
        //     if ($post['search'] != '') {
        //         $search = $post['search'];
        //         $this->db->like('no_pl', $search, 'both');
        //     }
        // }

        // if (isset($post['id'])) {
        //     if ($post['id'] != '') {
        //         $id = $post['id'];
        //         $this->db->where('a.id', $id);
        //     }
        // }

        // $this->db->select('a.*, b.fullname as checker_name, c.name as ekspedisi_name, e.fullname as created_by_name');
        // $this->db->from('tb_out_temp a');
        // $this->db->join('master_user b', 'a.checker_id = b.id', 'left');
        // $this->db->join('master_ekspedisi c', 'a.ekspedisi = c.id', 'left');
        // $this->db->join('master_user e', 'a.created_by = e.id', 'left');


        // if ($_SESSION['user_data']['role'] == 4) {
        //     $this->db->where('checker_id', userId());
        // }

        // $this->db->get();

        // var_dump($this->db->last_query());


        $sql = "SELECT b.id, a.id as pl_id, a.pl_no as no_pl, a.adm_pl_date, a.adm_pl_time, a.no_truck, 
        a.tot_qty as qty, a.sj_no, a.expedisi, a.pintu_loading,
        a.sj_time, a.dest, a.dealer_code, a.dealer_det, a.remarks,
        b.start_picking, b.stop_picking,
        b.start_checking, b.stop_checking,
        b.start_scanning, b.stop_scanning,
        CONVERT(DATE, b.created_date) AS activity_date
        FROM pl_h a
        INNER JOIN tb_out_temp b ON a.id = b.no_pl";

        if (isset($post['pl_id'])) {
            if ($post['pl_id'] != '') {
                $pl_id = $post['pl_id'];
                $sql .= " WHERE a.id = '$pl_id'";
            }
        }

        if (isset($post['search']) && isset($post['searchDest'])) {
            // if ($post['search'] != '') {
            $search = $post['search'];
            $searchDest = $post['searchDest'];
            $sql .= " WHERE a.pl_no LIKE '%$search%' AND dest LIKE '%$searchDest%'";
            // }
        }

        $sql .= " ORDER BY b.id DESC";

        $query = $this->db->query($sql);

        return $query;
        // var_dump($query->result());
        // return $this->db->get();
    }

    function prosesActivity($id, $params)
    {
        // var_dump($id);
        // Memulai transaksi
        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->update('tb_out_temp', $params);
        if ($this->db->affected_rows() > 0) {
            $sql = "select 
            b.id as pl_id, b.pl_no as no_pl, b.no_truck, b.tot_qty as qty,
            b.adm_pl_date as pl_date, b.adm_pl_time as pl_time, b.expedisi as ekspedisi,
            a.start_picking, a.stop_picking, a.start_checking, a.stop_checking, a.start_scanning, a.stop_scanning,
            a.created_date, a.created_by, a.remarks
            from tb_out_temp a
            INNER JOIN pl_h b on a.no_pl = b.id
            WHERE a.id = '$id'";
            $act = $this->db->query($sql)->row();
            if ($act->stop_picking != null && $act->stop_checking != null && $act->stop_scanning != null) {
                $data = array(
                    'pl_id' => $act->pl_id,
                    'no_pl' => $act->no_pl,
                    // 'no_truck' => $act->no_truck,
                    // 'qty' => $act->qty,
                    // 'checker_id' => $act->checker_id,
                    // 'pl_date' => $act->pl_date,
                    // 'pl_time' => $act->pl_time,
                    // 'time_arival' => $act->time_arival,
                    // 'ekspedisi' => $act->ekspedisi,
                    // 'driver' => $act->driver,
                    'start_picking' => $act->start_picking,
                    'stop_picking' => $act->stop_picking,
                    'duration_picking' => countDuration($act->start_picking, $act->stop_picking),
                    'start_checking' => $act->start_checking,
                    'stop_checking' => $act->stop_checking,
                    'duration_checking' => countDuration($act->start_checking, $act->stop_checking),
                    'start_scanning' => $act->start_scanning,
                    'stop_scanning' => $act->stop_scanning,
                    'duration_scanning' => countDuration($act->start_scanning, $act->stop_scanning),
                    // 'remarks' => $act->remarks,
                    'activity_created_date' => $act->created_date,
                    'activity_created_by' => $act->created_by,
                    'created_date' => currentDateTime(),
                    'created_by' => userId(),
                    'is_deleted' => 'N'
                );
                $this->db->insert('tb_out', $data);
                if ($this->db->affected_rows() > 0) {
                    $this->db->where(['id' => $id]);
                    $this->db->delete('tb_out_temp');
                }
            }
        }

        // Menyelesaikan transaksi
        $this->db->trans_complete();

        // Memeriksa apakah transaksi berhasil atau tidak
        if ($this->db->trans_status() === FALSE) {
            // Jika transaksi gagal, bisa dilakukan rollback
            $this->db->trans_rollback();
            // Atau penanganan kesalahan lainnya
        } else {
            // Jika transaksi berhasil, bisa dilakukan commit
            $this->db->trans_commit();
        }
    }

    function deleteOutTemp($post)
    {
        $this->db->where(['id' => $post['id']]);
        $this->db->delete('tb_out_temp');
    }

    public function getCompletedActivity()
    {
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];

        $sql = "select * from
        (select c.activity_date as [ACTIVITY DATE], CONVERT(DATE, a.created_date) as TANGGAL, c.pl_no AS [NO PL], c.sj_no AS [NO SJ], c.dest as TUJUAN, c.no_truck as [NO TRUCK], 
        c.dealer_code as [KODE DEALER], d.name AS EXPEDISI, c.dock as [MD/DDS], c.tot_qty as QTY, c.pintu_loading as [PINTU LOADING],
        c.pl_print_time as [JAM CETAK PL],
        c.adm_pl_time as [JAM AMANO], 
        a.start_picking as [MULAI DORONG], 
        a.stop_picking as [SELESAI DORONG], a.start_checking as [MULAI CHECK], 
        a.stop_checking as [SELESAI CHECK], a.start_scanning as [MULAI SCAN], 
        a.stop_scanning as [SELESAI SCAN],
        c.sj_time as [JAM TERIMA SJ],
        c.remarks as [REMARKS] 
        from tb_out a 
        left join master_user b on a.checker_id = b.id 
        left join pl_h c on c.id = a.pl_id 
        left join master_ekspedisi d on c.expedisi = d.id  
        where a.is_deleted <> 'Y'
        union all
        select b.activity_date as [ACTIVITY DATE], CONVERT(DATE, a.created_date) as [TANGGAL], b.pl_no as [NO PL], b.sj_no as [NO SJ], b.dest as [TUJUAN], b.no_truck as [NO TRUCK],b.dealer_code as [KODE DEALER],
        c.name as [EXPEDISI], b.dock as [MD/DDS], b.tot_qty as [QTY],
        b.pintu_loading as [PINTU LOADING], b.pl_print_time as [JAM CETAK PL], b.adm_pl_time as [JAM AMANO], a.start_picking as [MULAI DORONG], a.stop_picking as [SELESAI DORONG],
        a.start_checking as [MULAI CHECK], a.stop_checking as [SELESAI CHECK], a.start_scanning as [MULAI SCAN], a.stop_scanning as [SELESAI SCAN], b.sj_time as [JAM TERIMA SJ], b.remarks as [REMARKS]
        from tb_out_temp a
        RIGHT JOIN pl_h b on a.no_pl = b.id
        LEFT JOIN master_ekspedisi c on b.expedisi = c.id 
        WHERE b.pl_no NOT IN (SELECT no_pl from tb_out WHERE activity_date between CONVERT(DATE, '$startDate') and CONVERT(DATE, '$endDate')))a";

        $sql .= " WHERE a.[ACTIVITY DATE] between CONVERT(DATE, '$startDate')and CONVERT(DATE, '$endDate')";

        // if (isset($_POST['startDate']) != '' && isset($_POST['endDate']) != '') {
        //     $startDate = $_POST['startDate'];
        //     $endDate = $_POST['endDate'];
        // } else {
        //     $sql .= " AND a.[ACTIVITY DATE] = CONVERT(DATE, GETDATE())";
        // }

        $sql .= " ORDER BY a.[ACTIVITY DATE] DESC";
        $query = $this->db->query($sql);
        return $query;
    }

    public function getPresentaseOutbound()
    {
        $post = $this->input->post();
        // var_dump($post);
        $start_date = $post['start_date'];
        $end_date = $post['end_date'];

        // $sql = "WITH OutboundProses AS (
        //     SELECT COUNT(*) AS count_proses,
        // 	CASE WHEN SUM(CONVERT(INT,tot_qty)) IS NULL THEN 0 ELSE SUM(CONVERT(INT,tot_qty)) END as qty_proses
        //     FROM tb_out_temp a 
        // 	INNER JOIN pl_h b ON a.no_pl = b.id 
        //     WHERE CONVERT(date, b.activity_date) BETWEEN CONVERT(date, '$start_date') AND CONVERT(date, '$end_date')
        // ),
        // OutboundComplete AS (
        //     SELECT COUNT(*) AS count_complete,
        // 	CASE WHEN SUM(CONVERT(INT,tot_qty)) IS NULL THEN 0 ELSE SUM(CONVERT(INT,tot_qty)) END as qty_complete
        //     FROM tb_out a
        // 	INNER JOIN pl_h b ON a.pl_id = b.id
        //     WHERE CONVERT(date, b.activity_date) BETWEEN CONVERT(date, '$start_date') AND CONVERT(date, '$end_date')
        // ),
        // TotalOutbound AS (
        //     SELECT 
        //         (SELECT count_proses FROM OutboundProses) + 
        //         (SELECT count_complete FROM OutboundComplete) AS total_count
        // ),
        // TotalQty AS (
        //     SELECT 
        //         (SELECT qty_proses FROM OutboundProses) + 
        //         (SELECT qty_complete FROM OutboundComplete) AS total_qty
        // )
        // SELECT 
        //     (SELECT count_proses FROM OutboundProses) AS outbound_proses,
        // 	(SELECT qty_proses FROM OutboundProses) AS qty_proses,
        //     (SELECT count_complete FROM OutboundComplete) AS outbound_complete,
        // 	(SELECT qty_complete FROM OutboundComplete) AS qty_complete,
        //     (SELECT total_count FROM TotalOutbound) AS total_outbound,
        // 	(SELECT total_qty FROM TotalQty) AS total_qty,
        //     CASE 
        //         WHEN (SELECT total_count FROM TotalOutbound) <> 0 THEN
        //             (CAST((SELECT count_complete FROM OutboundComplete) AS FLOAT) / 
        //              (SELECT total_count FROM TotalOutbound)) * 100
        //         ELSE 0 
        //     END AS presentase";

        // Optimized Query
        $sql = "WITH
        OutboundUnProses AS (
            SELECT COUNT(*) AS count_unproses,
                COALESCE(SUM(CONVERT(INT, tot_qty)), 0) AS qty_unproses
            FROM pl_h a
            WHERE
            a.pl_no NOT IN (SELECT b.pl_no FROM tb_out_temp a 
            LEFT JOIN pl_h b ON a.no_pl = b.id WHERE b.activity_date BETWEEN '$start_date' AND '$end_date')
            AND a.pl_no NOT IN (SELECT b.pl_no FROM tb_out a 
            LEFT JOIN pl_h b ON a.no_pl = b.pl_no WHERE b.activity_date BETWEEN '$start_date' AND '$end_date')
            AND a.activity_date BETWEEN '$start_date' AND '$end_date'
        ),
        OutboundProses AS (
            SELECT COUNT(*) AS count_proses,
                COALESCE(SUM(CONVERT(INT, tot_qty)), 0) AS qty_proses
            FROM tb_out_temp a 
            LEFT JOIN pl_h b ON a.no_pl = b.id 
            WHERE b.activity_date BETWEEN '$start_date' AND '$end_date'
        ),
        OutboundComplete AS (
            SELECT COUNT(*) AS count_complete,
                COALESCE(SUM(CONVERT(INT, tot_qty)), 0) AS qty_complete
            FROM tb_out a
            INNER JOIN pl_h b ON a.pl_id = b.id
            WHERE b.activity_date BETWEEN '$start_date' AND '$end_date'
        ),
        InfoSuratJalan AS (
            SELECT COUNT(sj_no) AS total_sj FROM pl_h
            WHERE activity_date BETWEEN '$start_date' AND '$end_date'
            AND sj_no <> ''
        )
        SELECT 
            ou.count_unproses AS outbound_unproses,
            ou.qty_unproses AS qty_unproses,
            op.count_proses AS outbound_proses,
            op.qty_proses AS qty_proses,
            oc.count_complete AS outbound_complete,
            oc.qty_complete AS qty_complete,
            isj.total_sj AS total_sj,
            (op.count_proses + oc.count_complete + ou.count_unproses) AS total_pl,
            (op.qty_proses + oc.qty_complete + ou.qty_unproses) AS total_qty
        FROM OutboundUnProses ou, OutboundProses op, OutboundComplete oc, InfoSuratJalan isj;";
        $query = $this->db->query($sql);
        return $query;
    }


    public function getAllOutboundProccess()
    {

        // var_dump($_POST);
        // exit;

        $post = $this->input->post();
        $start_date = $post['start_date'];
        $end_date = $post['end_date'];

        $sql = "SELECT *,
        CASE 
            WHEN start_picking IS NULL AND stop_picking IS NULL AND start_scanning IS NULL AND stop_scanning IS NULL AND start_checking IS NULL AND stop_checking IS NULL THEN ''
            WHEN start_picking IS NOT NULL AND stop_picking IS NOT NULL AND start_scanning IS NOT NULL AND stop_scanning IS NOT NULL AND start_checking IS NOT NULL AND stop_checking IS NOT NULL THEN 'Done'
            ELSE 'Processing'
        END AS [status]
        FROM
        (
            SELECT a.id AS pl_id, a.pl_no, b.start_picking, b.stop_picking,
                    b.start_scanning, b.stop_scanning, b.start_checking, b.stop_checking, b.created_date, a.activity_date
            FROM pl_h a
            INNER JOIN tb_out_temp b ON a.id = b.no_pl
            UNION
            SELECT a.id AS pl_id, b.no_pl AS pl_no, b.start_picking, b.stop_picking,
                    b.start_scanning, b.stop_scanning, b.start_checking, b.stop_checking, b.activity_created_date AS created_date, a.activity_date
            FROM pl_h a
            INNER JOIN tb_out b ON a.id = b.pl_id
            UNION
            SELECT a.id AS pl_id, a.pl_no, b.start_picking, b.stop_picking,
            b.start_scanning, b.stop_scanning, b.start_checking, b.stop_checking, a.created_at AS created_date, a.activity_date 
            FROM pl_h a
            LEFT JOIN tb_out b ON a.id = b.pl_id
            WHERE
            a.pl_no NOT IN (SELECT b.pl_no FROM tb_out_temp a 
            LEFT JOIN pl_h b ON a.no_pl = b.id WHERE b.activity_date BETWEEN '$start_date' AND '$end_date')
            AND a.pl_no NOT IN (SELECT b.pl_no FROM tb_out a 
            LEFT JOIN pl_h b ON a.no_pl = b.pl_no WHERE b.activity_date BETWEEN '$start_date' AND '$end_date')
            AND a.activity_date BETWEEN '$start_date' AND '$end_date'
        ) a 
        WHERE CONVERT(DATE, activity_date) between CONVERT(DATE, '$start_date') and CONVERT(DATE, '$end_date')
        ORDER BY created_date DESC;";

        $query = $this->db->query($sql);
        return $query;
    }

    public function getTaskCompleteById($post = null)
    {
        // if (isset($post['search'])) {
        //     if ($post['search'] != '') {
        //         $search = $post['search'];
        //         $this->db->like('no_sj', $search, 'both');
        //     }
        // }

        if (isset($post['id'])) {
            if ($post['id'] != '') {
                $id = $post['id'];
                $this->db->where('a.id', $id);
            }
        }

        $this->db->select('a.*, b.fullname as checker_name');
        $this->db->from('tb_out a');
        $this->db->join('master_user b', 'a.checker_id = b.id');
        return $this->db->get();
    }

    public function editTaskCompleted($id, $params)
    {
        $this->db->where('id', $id);
        $this->db->update('tb_out', $params);
    }

    public function deleteActivityComplete($id, $params)
    {
        $this->db->where('id', $id);
        $this->db->update('tb_out', $params);
    }

    public function createPickingList($params)
    {
        $this->db->insert('pl_h', $params);
    }

    public function getAllPickingList($id = null)
    {
        $sql = "select a.*, b.name as ekspedisi_name, c.no_pl, d.no_pl,
        case 
        WHEN c.no_pl is null and d.no_pl is null then 'unprocessed' 
        WHEN c.no_pl is not null and d.no_pl is null then 'processing' 
        WHEN c.no_pl is null and d.no_pl is not null then 'done' end as [status]
        from pl_h a
        left join master_ekspedisi b on a.expedisi = b.id
        left join tb_out_temp c on a.id = c.no_pl
        left join tb_out d on a.id = d.pl_id";



        if (isset($_POST['startDate']) != '' && isset($_POST['endDate']) != '') {
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $sql .= " WHERE CONVERT(DATE, a.activity_date) between CONVERT(DATE, '$startDate')and CONVERT(DATE, '$endDate')";
        } else {
            $sql .= " WHERE CONVERT(DATE, a.activity_date) = CONVERT(DATE, GETDATE())";
        }

        if ($id != null) {
            $sql .= " AND a.id = '$id'";
        }



        $sql .= " order by a.created_at desc";

        // print_r($sql);

        $query = $this->db->query($sql);
        return $query;
    }

    public function getAllPickingListByAdm($id = null)
    {
        $sql = "select a.*, b.name as ekspedisi_name, c.no_pl, d.no_pl,
        case 
        WHEN c.no_pl is null and d.no_pl is null then 'unprocessed' 
        WHEN c.no_pl is not null and d.no_pl is null then 'processing' 
        WHEN c.no_pl is null and d.no_pl is not null then 'done' end as [status]
        from pl_h a
        left join master_ekspedisi b on a.expedisi = b.id
        left join tb_out_temp c on a.id = c.no_pl
        left join tb_out d on a.id = d.pl_id";



        // if (isset($_POST['startDate']) != '' && isset($_POST['endDate']) != '') {
        //     $startDate = $_POST['startDate'];
        //     $endDate = $_POST['endDate'];
        //     $sql .= " WHERE CONVERT(DATE, a.created_at) between CONVERT(DATE, '$startDate')and CONVERT(DATE, '$endDate')";
        // } else {
        //     $sql .= " WHERE CONVERT(DATE, a.created_at) = CONVERT(DATE, GETDATE())";
        // }

        if ($id != null) {
            $sql .= " WHERE a.id = '$id'";
        }



        $sql .= " order by a.created_at desc";

        // print_r($sql);

        $query = $this->db->query($sql);
        return $query;
    }

    public function getAllPickingListIdle()
    {
        $sql = "SELECT a.id AS pl_id, a.pl_no
        FROM pl_h a
        LEFT JOIN tb_out_temp b ON a.id = b.no_pl
        LEFT JOIN tb_out c ON a.id = c.pl_id
        WHERE b.no_pl IS NULL AND c.no_pl IS NULL";

        $query = $this->db->query($sql);
        return $query;
    }

    public function getPickerOutbound()
    {
        $sql = "select a.pl_id, c.fullname, b.sts, a.no_pl, a.start_picking, a.stop_picking, 
        d.adm_pl_date as pl_date, a.created_date
        from tb_out a 
        inner join pl_p b on a.pl_id = b.pl_id
        inner join master_user c on b.user_id = c.id
		inner join pl_h d on a.pl_id = d.id 
        where a.is_deleted <> 'Y'
        and b.sts = 'picker'";

        if (isset($_POST['startDate']) != '' && isset($_POST['endDate']) != '') {
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $sql .= " AND CONVERT(DATE, a.created_date) between CONVERT(DATE, '$startDate')and CONVERT(DATE, '$endDate')";
        } else {
            $sql .= " AND CONVERT(DATE, a.created_date) = CONVERT(DATE, GETDATE())";
        }

        // var_dump($sql);
        // exit;

        $query = $this->db->query($sql);
        return $query;
    }

    public function getPLWithNoSJ(){

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $sql = "select id, pl_no, sj_no, sj_time, dest, activity_date
        from pl_h 
        WHERE sj_no = '' AND sj_time is null 
        AND CONVERT(date, activity_date) between convert(date, '$start_date') and convert(date, '$end_date')
        ORDER BY dest ASC";

        $query = $this->db->query($sql);
        return $query;
    }
}
