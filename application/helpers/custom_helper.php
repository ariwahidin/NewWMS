<?php

function is_not_logged_in()
{
    $CI = &get_instance();
    if (!$CI->session->userdata('user_data')) {
        redirect(base_url('auth/login'));
    }
}

function is_logged_in()
{
    $CI = &get_instance();
    if ($CI->session->userdata('user_data')) {
        redirect(base_url('dashboard/index'));
    }
}

function list_menu()
{
    $CI = &get_instance();
    $query = $CI->db->get('master_menu');
    return $query;
}

function parentMenu()
{
    $CI = &get_instance();
    $sql = "SELECT * FROM master_parent_menu order by no asc";
    $query = $CI->db->query($sql);

    return $query;
}

function child_menu($id)
{
    $where = array(
        'parent_id' => $id
    );
    $CI = &get_instance();
    $query = $CI->db->get_where('master_menu', $where);
    return $query;
}

function countDuration($start, $stop)
{
    // $date_a = '2024-01-01 17:10:10';
    // $date_b = '2024-01-30 17:10:10';

    if (!is_null($start) && !is_null($stop)) {
        // Konversi string tanggal ke timestamp
        $timestamp_a = strtotime($start);
        $timestamp_b = strtotime($stop);

        // Hitung perbedaan timestamp
        $difference = abs($timestamp_b - $timestamp_a);

        // Konversi perbedaan menjadi format jam, menit, detik
        $hours = floor($difference / (60 * 60));
        $minutes = floor(($difference - $hours * 3600) / 60);
        $seconds = $difference - ($hours * 3600) - ($minutes * 60);


        // Tambahkan nol di depan jika hanya satu digit
        $hours = ($hours < 10) ? "0$hours" : $hours;
        $minutes = ($minutes < 10) ? "0$minutes" : $minutes;
        $seconds = ($seconds < 10) ? "0$seconds" : $seconds;

        $timeDuration =  $hours . ":" . $minutes . ":" . $seconds;
        $terbilang = "$hours jam, $minutes menit, $seconds detik.";

        // $result = array(
        //     'time' => $timeDuration,
        //     'terbilang' => $terbilang
        // );
    } else {
        $timeDuration = '';
        // $result = array(
        //     'time' => '',
        //     'terbilang' => ''
        // );
    }
    return $timeDuration;
}

function roundMinutes($timeString)
{
    // Memisahkan string waktu menjadi bagian-bagian yang terpisah
    $timeParts = explode(':', $timeString);

    // Menghitung total menit dari waktu
    $totalMinutes = ($timeParts[0] * 60) + $timeParts[1];

    // Lakukan pembulatan ke menit terdekat
    $roundedMinutes = round($totalMinutes);

    // Mengembalikan hasil pembulatan
    return $roundedMinutes . " Menit";
}

function currentDateTime()
{
    $timezone = new DateTimeZone('Asia/Jakarta');
    $dateTime = new DateTime('now', $timezone);
    $formattedDateTime = $dateTime->format('Y-m-d H:i:s');
    return $formattedDateTime;
}

function userId()
{
    return $_SESSION['user_data']['user_id'];
}

function getPicker($pl_id)
{
    $CI = &get_instance();
    $sql = "SELECT b.fullname, a.user_id FROM pl_p a
    INNER JOIN master_user b ON a.user_id = b.id
    WHERE a.pl_id = '$pl_id' AND sts = 'picker'";
    $query = $CI->db->query($sql);
    return $query;
}

function getChecker($pl_id)
{
    $CI = &get_instance();
    $sql = "SELECT b.fullname FROM pl_p a
    INNER JOIN master_user b ON a.user_id = b.id
    WHERE a.pl_id = '$pl_id' AND sts = 'checker'";
    $query = $CI->db->query($sql);
    return $query;
}

function getScanner($pl_id)
{
    $CI = &get_instance();
    $sql = "SELECT b.fullname FROM pl_p a
    INNER JOIN master_user b ON a.user_id = b.id
    WHERE a.pl_id = '$pl_id' AND sts = 'scanner'";
    $query = $CI->db->query($sql);
    return $query;
}

function getStatusProsesUserInbound($user_id)
{
    $CI = &get_instance();
    $sql = "SELECT a.no_sj, a.checker_id, a.start_unloading, a.stop_unloading, a.start_checking, a.stop_checking, a.start_putaway, a.start_putaway,
    CASE 
        WHEN a.start_unloading IS NOT NULL AND a.stop_unloading IS NULL THEN 'active' 
        WHEN a.start_checking IS NOT NULL AND a.stop_checking IS NULL THEN 'active'
        WHEN a.start_putaway IS NOT NULL AND a.stop_putaway IS NULL THEN 'active' 
        ELSE 'idle' 
    END as proses_status
    FROM tb_trans_temp a
    WHERE a.checker_id = '$user_id'";
    $query = $CI->db->query($sql);
    return $query;
}

function getStatusProsesUserOutbound($user_id)
{
    $CI = &get_instance();
    $sql = "SELECT a.pl_no, b.sts, c.start_picking, c.stop_picking, c.start_checking, c.stop_checking, c.start_scanning, c.stop_scanning,
    CASE 
        WHEN b.sts = 'picker' AND c.start_picking IS NOT NULL AND c.stop_picking IS NULL THEN 'active' 
        WHEN b.sts = 'checker' AND c.start_checking IS NOT NULL AND c.stop_checking IS NULL THEN 'active'
        WHEN b.sts = 'scanner' AND c.start_scanning IS NOT NULL AND c.stop_scanning IS NULL THEN 'active' 
        ELSE 'idle' 
    END as proses_status
    FROM pl_h a
    JOIN pl_p b ON a.id = b.pl_id
    JOIN tb_out_temp c ON a.id = c.no_pl
    WHERE b.user_id = '$user_id'";
    $query = $CI->db->query($sql);
    return $query;
}

function generateDates($input)
{
    // Memastikan format input adalah 'YYYY-MM'
    if (!preg_match('/^\d{4}-\d{2}$/', $input)) {
        return "Format input tidak valid. Harus 'YYYY-MM'.";
    }

    // Memisahkan tahun dan bulan dari input
    list($year, $month) = explode('-', $input);

    // Mendapatkan jumlah hari dalam bulan tersebut
    $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    // Membuat array tanggal
    $dates = [];
    for ($day = 1; $day <= $numDays; $day++) {
        $dates[] = sprintf('%s-%02d-%02d', $year, $month, $day);
    }

    return $dates;
}

function wsConfig()
{
    $CI = &get_instance();
    $sql = "SELECT TOP 1 *  FROM ws_config";
    $query = $CI->db->query($sql);
    return $query->row();
}
