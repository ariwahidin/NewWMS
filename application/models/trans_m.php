<?php
class Trans_m extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getTransID($trans_name)
    {
        $prefix = 'TR'; // Awalan tetap
        $currentYearMonth = date('ym'); // Format tahun dan bulan, misalnya 2410 untuk Oktober 2024

        // Mencari nomor urut terakhir dari bulan ini
        $sql = "SELECT TOP 1 trans_no FROM trans 
                    WHERE trans_no LIKE ? 
                    ORDER BY trans_no DESC";
        $lastEntry = $this->db->query($sql, array($prefix . $currentYearMonth . '%'))->row();

        if ($lastEntry) {
            // Ambil 4 digit terakhir dari nomor surat jalan terakhir
            $lastNumber = (int)substr($lastEntry->trans_no, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Tambahkan 1 dan format dengan 4 digit
        } else {
            // Jika belum ada nomor surat jalan bulan ini, mulai dari 0001
            $newNumber = '0001';
        }

        // Gabungkan prefix, tahun-bulan, dan nomor urut baru
        $trans_no = $prefix . $currentYearMonth . $newNumber;

        $sqlInsert = "INSERT INTO trans (trans_no, trans_name, created_by) VALUES (?, ?, ?)";
        $this->db->query($sqlInsert, array($trans_no, $trans_name, $_SESSION['user_data']['username']));
        $transID = $this->db->insert_id();

        return $transID;
    }
}
