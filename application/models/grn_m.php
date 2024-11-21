<?php
class Grn_m extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function generate_grn($receive_detail_id = null)
    {
        $prefix = 'GRN';
        $currentYearMonth = date('ymd');

        $sql = "SELECT TOP 1 grn_number FROM grn 
                WHERE grn_number LIKE ? 
                ORDER BY grn_number DESC";
        $lastEntry = $this->db->query($sql, array($prefix . $currentYearMonth . '%'))->row();

        if ($lastEntry) {
            $lastNumber = (int)substr($lastEntry->grn_number, -4); // Mengambil 4 digit terakhir dari grn_number
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        $new_grn = $prefix . $currentYearMonth . $newNumber;

        $insert_data = array(
            'grn_number' => $new_grn,
            'receive_detail_id' => $receive_detail_id
        );

        $this->db->insert('grn', $insert_data); // Pastikan tabel yang benar digunakan

        $last_grn_id = $this->db->insert_id();

        $data_return = array(
            'grn_number' => $new_grn,
            'grn_id' => $last_grn_id
        );

        return $data_return;
    }

}
