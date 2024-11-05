<?php
class Lpn_m extends CI_Model
{
    private $lpn_prefix = 'LPN'; // Prefix untuk nomor LPN

    public function __construct()
    {
        parent::__construct();
    }
    public function generate_lpn($receive_detail_id = null)
    {
        // Dapatkan nomor LPN terakhir berdasarkan urutan tanggal
        // $last_lpn = $this->get_last_lpn();

        // // Tentukan nomor LPN berikutnya
        // if ($last_lpn) {
        //     $last_number = (int)substr($last_lpn->lpn_number, strlen($this->lpn_prefix));
        //     $new_number = $last_number + 1;
        // } else {
        //     $new_number = 1; // Jika belum ada nomor, mulai dari 1
        // }

        // $new_lpn = sprintf('%s%05d', $this->lpn_prefix, $new_number);

        // $insert_data = array(
        //     'lpn_number' => $new_lpn,
        //     'receive_detail_id' => $receive_detail_id
        // );

        // $this->db->insert('lpn', $insert_data);

        // $last_lpn_id = $this->db->insert_id();

        // Generate nomor surat jalan dengan format custom SPKASYYMMXXXX
        $prefix = 'LP'; // Awalan tetap
        $currentYearMonth = date('ym'); // Format tahun dan bulan, misalnya 2410 untuk Oktober 2024

        // Mencari nomor urut terakhir dari bulan ini
        $sql = "SELECT TOP 1 lpn_number FROM lpn 
                            WHERE lpn_number LIKE ? 
                            ORDER BY lpn_number DESC";
        $lastEntry = $this->db->query($sql, array($prefix . $currentYearMonth . '%'))->row();

        if ($lastEntry) {
            // Ambil 4 digit terakhir dari nomor surat jalan terakhir
            $lastNumber = (int)substr($lastEntry->lpn_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Tambahkan 1 dan format dengan 4 digit
        } else {
            // Jika belum ada nomor surat jalan bulan ini, mulai dari 0001
            $newNumber = '0001';
        }

        // Gabungkan prefix, tahun-bulan, dan nomor urut baru
        $new_lpn = $prefix . $currentYearMonth . $newNumber;

        $insert_data = array(
            'lpn_number' => $new_lpn,
            'receive_detail_id' => $receive_detail_id
        );

        $this->db->insert('lpn', $insert_data);

        $last_lpn_id = $this->db->insert_id();

        $data_return = array(
            'lpn_number' => $new_lpn,
            'lpn_id' => $last_lpn_id
        );

        return $data_return;
    }

    /**
     * Get the last generated LPN
     * @return object|null Last LPN row or null if no LPN found
     */
    private function get_last_lpn()
    {
        $this->db->select_max('lpn_number');
        $query = $this->db->get('lpn');
        return $query->row();
    }
}
