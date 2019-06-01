<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Prodi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_prodi');
        $this->load->model('M_users');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }

        //tampil data berdasarkan id
        $row = $this->M_users->get_by_id($this->session->userdata['username']);
        $data = array(
                    'wa'        => 'Web Administrator',
                    'univ'      => 'STIKOM POLTEK CIREBON',
                    'username'  => $row->username,
                    'email'     => $row->email,
                    'level'     => $row->level
        );
        $this->load->view('header_list', $data);
        $this->load->view('prodi/prodi_list');
        $this->load->view('footer_list');
    }

    //fungsi json
    public function json() {
        header('Content-Type: application/json');
        echo $this->M_prodi->json(); // Menampilkan data json yang terdapat pada Jurusan_model
    }

    public function create()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }

        //tampil data berdasarkan id
        $row = $this->M_users->get_by_id($this->session->userdata['username']);
        $dataAdm = array(
                    'wa'        => 'Web Administrator',
                    'univ'      => 'Stikom Poltek Cirebon',
                    'username'  => $row->username,
                    'email'     => $row->email,
                    'level'     => $row->level
        );

        //menampung data yg di input
        $data = array(
                'button'        => 'Create',
                'back'          => site_url('prodi'),
                'action'        => site_url('prodi/create_action'),
                'id_prodi'    => set_value('id_prodi'),
                'kode_prodi'  => set_value('kode_prodi'),
                'nama_prodi'  => set_value('nama_prodi'),
                'id_jurusan'  => set_value('id_jurusan'),
        );
        $this->load->view('header', $dataAdm);
        $this->load->view('prodi/prodi_form', $data);
        $this->load->view('footer');
    }

    public function create_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                    'id_prodi' => $this->input->post('id_prodi', TRUE),
                    'kode_prodi' => $this->input->post('kode_prodi', TRUE),
                    'nama_prodi' => $this->input->post('nama_prodi', TRUE),
                    'id_jurusan' => $this->input->post('id_jurusan', TRUE),
            );
            $this->M_prodi->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('prodi'));
        }
    }

    //fungsi form update
    public function update($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }

        $rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
        $dataAdm = array(
            'wa'        => 'Web Administrator',
            'univ'      => 'Stikom Poltek Cirebon',
            'username'  => $rowAdm->username,
            'email'     => $rowAdm->email,
            'level'     => $rowAdm->level
        );

        //menampilkan data berdasarkan id nya
        $row = $this->M_prodi->get_by_id($id);
        if ($row) {
            $data = array(
                'button'        => 'Update',
                'back'          => site_url('prodi'),
                'action'        => site_url('prodi/update_action'),
                'id_prodi'      => set_value('id_prodi', $row->id_prodi),
                'kode_prodi'    => set_value('kode_prodi', $row->kode_prodi),
                'nama_prodi'    => set_value('nama_prodi', $row->nama_prodi),
                'id_jurusan'    => set_value('id_jurusan', $row->id_jurusan)
            );
            $this->load->view('header', $dataAdm);
            $this->load->view('prodi/prodi_form', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('prodi'));
        }
    }

    // Fungsi untuk melakukan aksi update data
    public function update_action(){
		// Jika session data username tidak ada maka akan dialihkan kehalaman login
		if (!isset($this->session->userdata['username'])) {
			redirect(base_url("login"));
		}

        $this->_rules(); // Rules atau aturan bahwa setiap form harus diisi

		// Jika form prodi belum diisi dengan benar
		// maka sistem akan meminta user untuk menginput ulang
        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_prodi', TRUE));
        }
		// Jika form prodi telah diisi dengan benar
		// maka sistem akan melakukan update data prodi kedalam database
		else {
            $data = array(
			'id_prodi' => $this->input->post('id_prodi',TRUE),
			'kode_prodi' => $this->input->post('kode_prodi',TRUE),
			'nama_prodi' => $this->input->post('nama_prodi',TRUE),
			'id_jurusan' => $this->input->post('id_jurusan',TRUE),
			);

            $this->M_prodi->update($this->input->post('id_prodi', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('prodi'));
        }
    }

    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }

        $row = $this->M_prodi->get_by_id($id);

        if ($row) {
            $this->M_prodi->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('prodi'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('prodi'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('id_prodi', 'id_prodi', 'trim|required');
        $this->form_validation->set_rules('kode_prodi', 'kode_prodi', 'trim|required');
        $this->form_validation->set_rules('nama_prodi', 'nama_prodi', 'trim|required');
        $this->form_validation->set_rules('id_jurusan', 'id_jurusan', 'trim|required');

        $this->form_validation->set_rules('id_prodi', 'id_prodi', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}
