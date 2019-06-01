<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class M_thn_akad extends CI_Model {

    public $table  = 'thn_akad_semester';
    public $id     = 'id_thn_akad';
    public $order  = 'DESC';

    function json()
    {
        $this->datatables->select("id_thn_akad, thn_akad, semester, IF(aktif = 'Y', 'Aktif', 'Tidak') as status, IF(semester = 1, 'Ganjil', 'Genap') as namaSemester");
        $this->datatables->from('thn_akad_semester');
        $this->datatables->add_column('action', anchor(site_url('thn_akad_semester/update/$1'),'<button type="button" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></button>')."  ".anchor(site_url('thn_akad_semester/delete/$1'),'<button type="button" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>','onclick="javasciprt: return confirm(\'Hapus Data ?\')"'), 'id_thn_akad');
        return $this->datatables->generate();
    }

    //tampil semua data
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

    //menampilkan jumlah data
    function total_rows($q= NULL)
    {
        $this->db->like('id_thn_akad', $q);
        $this->db->or_like('thn_akad', $q);
        $this->db->or_like('semester', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function get_limit_data($limit, $start = 0, $q=NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id_thn_akad', $q);
        $this->db->or_like('thn_akad', $q);
        $this->db->or_like('semester', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);

    }

    public function update_aktif($id)
    {
        $query = $this->db->where('id_thn_akad ='.$id);
        $this->db->update($this->table, array('aktif' => 'Y'), $query);
        return TRUE;
    }

    public function update_tidakAktif($id)
    {
        $query = $this->db->where('id_thn_akad !='.$id);
        $this->db->update($this->table, array('aktif' => 'N'), $query);
        return TRUE;
    }

    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
}
