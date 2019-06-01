<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class M_matakuliah extends CI_Model {

    public $table = 'matakuliah';
    public $id    = 'kode_matakuliah';
    public $order = 'DESC';
    public $hasil = '';

    public function __construct()
    {
        parent::__construct();
    }

    //tabel data dengan nama matakuliah dan prodi
    function json()
    {
        $this->datatables->select("m.kode_matakuliah, m.nama_matakuliah, p.nama_prodi, m.jenis, (CASE WHEN m.jenis = 'U' THEN 'Umum' WHEN m.jenis = 'W' THEN 'Wajib' ELSE 'Pilihan' END) as namaJenis");
        $this->datatables->from('matakuliah as m');
        $this->datatables->join('prodi as p','m.id_prodi= p.id_prodi');
        $this->datatables->add_column('action', anchor(site_url('matakuliah/read/$1'),'<button type="button" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></button>')."  ".anchor(site_url('matakuliah/update/$1'),'<button type="button" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></button>')."  ".anchor(site_url('matakuliah/delete/$1'),'<button type="button" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>','onclick="javasciprt: return confirm(\'Hapus Data ?\')"'), 'kode_matakuliah');
        return $this->datatables->generate();
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

    //menampilkan jumlah data
    function total_rows($q= NULL)
    {
        $this->db->like('kode_matakuliah', $q);
        $this->db->or_like('kode_matakuliah', $q);
        $this->db->or_like('nama_matakuliah', $q);
        $this->db->or_like('sks', $q);
        $this->db->or_like('semester', $q);
        $this->db->or_like('jenis', $q);
        $this->db->or_like('id_prodi', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function get_limit_data($limit, $start = 0, $q=NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->or_like('kode_matakuliah', $q);
        $this->db->or_like('nama_matakuliah', $q);
        $this->db->or_like('sks', $q);
        $this->db->or_like('semester', $q);
        $this->db->or_like('jenis', $q);
        $this->db->or_like('id_prodi', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
}
