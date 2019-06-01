<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Thn_akad_semester extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_thn_akad');
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
        $rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
        $dataAdm = array(
                    'wa'        => 'Web Administrator',
                    'univ'      => 'STIKOM POLTEK CIREBON',
                    'username'  => $rowAdm->username,
                    'email'     => $rowAdm->email,
                    'level'     => $rowAdm->level
        );
        $this->load->view('header_list', $dataAdm);
        $this->load->view('thn_akad_semester/thn_akad_semester_list');
        $this->load->view('footer_list');
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_thn_akad->json(); // Menampilkan data json yang terdapat pada Jurusan_model
    }

    public function create()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }

        //tampil data berdasarkan id
        $rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
        $dataAdm = array(
                    'wa'        => 'Web Administrator',
                    'univ'      => 'Stikom Poltek Cirebon',
                    'username'  => $rowAdm->username,
                    'email'     => $rowAdm->email,
                    'level'     => $rowAdm->level
        );

        //menampung data yg di input
        $data = array(
                'button'        => 'Create',
                'back'          => site_url('thn_akad_semester'),
                'action'        => site_url('thn_akad_semester/create_action'),
                'id_thn_akad'   => set_value('id_thn_akad'),
                'thn_akad'      => set_value('thn_akad'),
                'semester'      => set_value('semester')
        );
        $this->load->view('header', $dataAdm);
        $this->load->view('thn_akad_semester/thn_akad_semester_form', $data);
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
                    'thn_akad' => $this->input->post('thn_akad', TRUE),
                    'semester' => $this->input->post('semester', TRUE)
            );
            $this->M_thn_akad->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('thn_akad_semester'));
        }
    }

    // Fungsi untuk melakukan aksi update data
    public function update_action(){
		if (!isset($this->session->userdata['username'])) {
			redirect(base_url("login"));
		}

        $this->_rules(); // Rules atau aturan bahwa setiap form harus diisi


        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_thn_akad', TRUE));
        } else {
            $data = array(
            'thn_akad' => $this->input->post('thn_akad',TRUE),
            'semester' => $this->input->post('semester',TRUE),
            );

            $this->M_thn_akad->update($this->input->post('id_thn_akad', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('thn_akad_semester'));
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
        $row = $this->M_thn_akad->get_by_id($id);
        if ($row) {
            $data = array(
                'button'        => 'Update',
                'back'          => site_url('thn_akad_semester'),
                'action'        => site_url('thn_akad_semester/update_action'),
                'id_thn_akad'   => set_value('id_thn_akad', $row->id_thn_akad),
                'thn_akad'      => set_value('thn_akad', $row->thn_akad),
                'semester'      => set_value('semester', $row->semester)
            );
            $this->load->view('header', $dataAdm);
            $this->load->view('thn_akad_semester/thn_akad_semester_form', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('thn_akad_semester'));
        }
    }

    public function aktif_action($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }

        $rows = $this->M_thn_akad->get_by_id($id);
        if ($rows) {
            $this->M_thn_akad->update_tidakAktif($id);
            $this->M_thn_akad->update_aktif($id);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('thn_akad_semester'));
        }
    }

    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }

        $row = $this->M_thn_akad->get_by_id($id);

        if ($row) {
            $this->M_thn_akad->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('thn_akad_semester'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('thn_akad_semester'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('thn_akad', 'thn_akad', 'trim|required');
        $this->form_validation->set_rules('semester', 'semester', 'trim|required');

        $this->form_validation->set_rules('id_thn_akad', 'id_thn_akad', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

