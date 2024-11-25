
<?php
class Inventory_m extends CI_Model
{
    // Fungsi untuk mengambil data dari Asics dan memasukkannya ke list_do di database default
    public function InventoryByItem()
    {
        $sql = "select item_code, SUM(on_hand) as on_hand, SUM(allocated) as allocated, SUM(available) as available, SUM(in_transit) as in_transit 
                from inventory
                group by item_code
                order by item_code asc
                ";
        $query = $this->db->query($sql);
        return $query;
    }

    public function InventoryByLocation()
    {
        $sql = "select [location], item_code,  SUM(on_hand) as on_hand, SUM(allocated) as allocated, SUM(available) as available, SUM(in_transit) as in_transit 
                from inventory
                group by [location], item_code
                order by [location] asc
                ";
        $query = $this->db->query($sql);
        return $query;
    }

    public function InventoryByDetail()
    {
        $sql = "select [location], item_code, receive_date, expiry_date, qa,  SUM(on_hand) as on_hand, SUM(allocated) as allocated, SUM(available) as available, SUM(in_transit) as in_transit 
                from inventory
                group by [location], item_code, receive_date, expiry_date, qa
                order by receive_date desc";
        $query = $this->db->query($sql);
        return $query;
    }

    // buat fungsi untuk menghapus inventory yang on_hand, allocated, available, in_transit = 0
    
    public function deleteZeroInventory()
    {
        $sql = "delete from inventory 
                where on_hand = 0 and allocated = 0 and available = 0 and in_transit = 0";
        $this->db->query($sql);
    }
}
