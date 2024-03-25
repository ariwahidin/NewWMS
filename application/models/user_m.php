<?php
class User_m extends CI_Model
{

    var $table = 'master_user';
    var $column_order = array(null, 'username', 'password', 'cardcode', 'role'); //field yang ada di table cuatomer
    var $column_search = array('usrname', 'role'); //field yang diizin untuk pencarian 
    var $order = array('username' => 'asc'); // default order 

    private function _get_datatables_query()
    {

        $this->db->from($this->table);
        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function getUserActive($username = null, $password = null)
    {
        $this->db->select('a.id, a.fullname, a.username, a.role, b.role as role_name');
        $this->db->from('master_user a');
        $this->db->join('master_role b', 'b.id = a.role');
        $this->db->where('a.is_deleted <> ', 'Y');
        if ($username != null && $password != null) {
            $this->db->where('a.username', $username);
            $this->db->where('a.password', $password);
        }
        $query = $this->db->get();
        // print_r($this->db->last_query());
        return $query;
    }

    public function createUser($post)
    {
        date_default_timezone_set('Asia/Jakarta');
        $created_at = date('Y-m-d H:i:s');
        $params = array(
            'fullname' => $post['fullname'],
            'username' => $post['username'],
            'password' => $post['password'],
            'role' => $post['role'],
            'is_deleted' => 'N',
            'created_by' => $_SESSION['user_data']['user_id'],
            'created_at' => $created_at
        );
        $this->db->insert('master_user', $params);
    }

    public function editUser($post)
    {
        date_default_timezone_set('Asia/Jakarta');
        $created_at = date('Y-m-d H:i:s');
        $data = array(
            'fullname' => $post['fullname'],
            'username' => $post['username'],
            'role' => $post['role'],
            'updated_by' => $_SESSION['user_data']['user_id'],
            'updated_at' => $created_at
        );
        if ($post['password'] != '') {
            $params['password'] = $post['password'];
        }


        $this->db->where('id', $post['user_id']);
        $this->db->update('master_user', $data);
    }

    public function deleteUser($post)
    {
        date_default_timezone_set('Asia/Jakarta');
        $created_at = date('Y-m-d H:i:s');
        $data = array(
            'is_deleted' => 'Y',
            'is_active' => 'N',
            'deleted_by' => $_SESSION['user_data']['user_id'],
            'deleted_at' => $created_at
        );

        $this->db->where('id', $post['id']);
        $this->db->update('master_user', $data);
    }

    public function getUserByUsername($username)
    {
        $where = array(
            'username' => $username
        );
        return $this->db->get_where('master_user', $where, 1);
    }

    public function getOperator()
    {
        $this->db->select('a.id, a.fullname, b.role');
        $this->db->from('master_user a');
        $this->db->join('master_role b', 'a.role = b.id');
        $where = array(
            'b.role' => 'operator',
            'a.is_active' => 'Y'
        );
        $this->db->where($where);
        return $this->db->get();
    }


    // public function deleteChecker()
    // {
    //     $post = $this->input->post();
    //     $id = $post['id'];
    //     date_default_timezone_set('Asia/Jakarta');
    //     $datetime = date('Y-m-d H:i:s');
    //     $params = array(
    //         'delete_by' => $_SESSION['user_data']['username'],
    //         'delete_at' =>  $datetime,
    //         'is_delete' => 'Y'
    //     );
    //     $this->employee->deleteChecker($id, $params);
    //     if ($this->db->affected_rows() > 0) {
    //         $response = array(
    //             'success' => true,
    //             'message' => 'Success deleting data'
    //         );
    //     } else {
    //         $response = array(
    //             'success' => false,
    //             'message' => 'Failed deleting data'
    //         );
    //     }
    //     echo json_encode($response);
    // }
}
