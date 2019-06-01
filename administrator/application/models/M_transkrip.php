<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class M_transkrip extends CI_Model
{
    public $table = 'transkrip';
    public $id = 'id_transkrip';
    public $order = 'DESC';

    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }

    //tampil semua data
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    //tampil berdasarkan id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    //tambah data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    //update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    //hapus data
    function delete($id)
    {
        $this->db->where($this->table, $data);
        $this->db->delete($this->table);
    }


}
