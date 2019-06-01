<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class M_users extends CI_Model
{
    public $table   = 'users';
    public $id      = 'username';
    public $order   = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function json() {
        $this->datatables->select('username,password,nama_user,email,level,blokir,id_sessions');
        $this->datatables->from('users');
        $this->datatables->add_column('action', anchor(site_url('users/update/$1'),'<button type="button" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></button>')."  ".anchor(site_url('users/delete/$1'),'<button type="button" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>','onclick="javasciprt: return confirm(\'Hapus Data ?\')"'), 'username');
        return $this->datatables->generate();
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

    //tampil jumlah data
    function total_rows($q = NULL)
    {
        $this->db->like('username', $q);
        $this->db->or_like('username'. $q);
        $this->db->or_like('nama_user',$q);
        $this->db->or_like('tgl_registrasi',$q);
        $this->db->or_like('password'. $q);
        $this->db->or_like('email'. $q);
        $this->db->or_like('level'. $q);
        $this->db->or_like('blokir'. $q);
        $this->db->or_like('id_sessions'. $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    //tampil data dengan limit
    function get_limit_data($limit, $start=0, $q=NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('username', $q);
        $this->db->or_like('username'. $q);
        $this->db->or_like('password'. $q);
        $this->db->or_like('nama_user',$q);
        $this->db->or_like('tgl_registrasi',$q);
        $this->db->or_like('email'. $q);
        $this->db->or_like('level'. $q);
        $this->db->or_like('blokir'. $q);
        $this->db->or_like('id_sessions'. $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
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
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
}


?>