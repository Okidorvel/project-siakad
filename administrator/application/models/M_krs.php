<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class M_krs extends CI_Model
{

    public $table = 'krs';
    public $id    = 'id_krs';
    public $order = 'DESC';

    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }

    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    function update($id, $data)
    {
        $this->db->where($this->table, $id);
        $this->db->update($this->table, $data);
    }

    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }


}

/* End of file ModelName.php */
