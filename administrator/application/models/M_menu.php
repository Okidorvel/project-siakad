<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class M_menu extends CI_Model
{
    public $table     = 'menu';
    public $id        = 'id_menu';
    public $main_menu = 'main_menu';
    public $order     = 'DESC';

    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }

    //tabel data menu
    function json() {
        $this->datatables->select('id_menu,nama_menu,link,icon,main_menu');
        $this->datatables->from('menu');
        $this->datatables->add_column('action', anchor(site_url('menu/update/$1'),'<button type="button" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></button>')."  ".anchor(site_url('menu/delete/$1'),'<button type="button" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>','onclick="javasciprt: return confirm(\'Hapus Data ?\')"'), 'id_menu');
        return $this->datatables->generate();
    }

    //tampil semua data
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    //tampil semua data berdasarkan id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    //tampil jumlah data
    function total_rows($q = NULL)
    {
        $this->db->like('id_menu', $q);
        $this->db->or_like('nama_menu', $q);
        $this->db->or_like('link', $q);
        $this->db->or_like('icon', $q);
        $this->db->or_like('main_menu', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    //tampil data dengan jumlah limit
    function get_limit_data($limit, $start = 0, $q=NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id_menu', $q);
        $this->db->or_like('nama_menu', $q);
        $this->db->or_like('link', $q);
        $this->db->or_like('icon', $q);
        $this->db->or_like('main_menu', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    //insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    //ubah data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    //menghapus data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
}

?>