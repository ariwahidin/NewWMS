<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse_m extends CI_Model
{
    private $table = 'master_warehouse';

    public function get_items($limit, $start, $search = '')
    {
        $this->db->like('code', $search);
        $this->db->or_like('desc', $search);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    public function count_items($search = '')
    {
        $this->db->like('code', $search);
        $this->db->or_like('desc', $search);
        return $this->db->count_all_results($this->table);
    }

    public function get_item($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert_item($data)
    {

        $this->db->insert($this->table, $data);

        //if error
        if ($this->db->error()['code'] != 0) {
            // return $this->db->error()['message'];
        } else {
            return $this->db->insert_id();
        }
    }

    public function update_item($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_item($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function getAllItem()
    {
        return $this->db->get($this->table);
    }
}
