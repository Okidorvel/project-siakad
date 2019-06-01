<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Jurusan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_jurusan');
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
        $this->load->view('jurusan/jurusan_list');
        $this->load->view('footer_list');
    }

    //fungsi json
    public function json() {
        header('Content-Type: application/json');
        echo $this->M_jurusan->json(); // Menampilkan data json yang terdapat pada Jurusan_model
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
                'back'          => site_url('jurusan'),
                'action'        => site_url('jurusan/create_action'),
                'id_jurusan'    => set_value('id_jurusan'),
                'kode_jurusan'  => set_value('kode_jurusan'),
                'nama_jurusan'  => set_value('nama_jurusan')
        );
        $this->load->view('header', $dataAdm);
        $this->load->view('jurusan/jurusan_form', $data);
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
                    'kode_jurusan' => $this->input->post('kode_jurusan', TRUE),
                    'nama_jurusan' => $this->input->post('nama_jurusan', TRUE)
            );
            $this->M_jurusan->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurusan'));
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
        $row = $this->M_jurusan->get_by_id($id);
        if ($row) {
            $data = array(
                'button'        => 'Update',
                'back'          => site_url('jurusan'),
                'action'        => site_url('jurusan/update_action'),
                'id_jurusan'    => set_value('id_jurusan', $row->id_jurusan),
                'kode_jurusan'  => set_value('kode_jurusan', $row->kode_jurusan),
                'nama_jurusan'  => set_value('nama_jurusan', $row->nama_jurusan)
        );
        $this->load->view('header', $dataAdm);
        $this->load->view('jurusan/jurusan_form', $data);
        $this->load->view('footer');
        } else {
        $this->session->set_flashdata('message', 'Record Not Found');
        redirect(site_url('jurusan'));
        }

    }

    public function update_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_jurusan', TRUE));
        } else {
            $data = array(
                    'kode_jurusan' => $this->input->post('kode_jurusan', TRUE),
                    'nama_jurusan' => $this->input->post('nama_jurusan', TRUE),
            );
            $this->M_jurusan->update($this->input->post('id_jurusan', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurusan'));
        }
    }

    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }

        $row = $this->M_jurusan->get_by_id($id);

        if ($row) {
            $this->M_jurusan->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurusan'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurusan'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('kode_jurusan', 'kode_jurusan', 'trim|required');
        $this->form_validation->set_rules('nama_jurusan', 'nama_jurusan', 'trim|required');
        $this->form_validation->set_rules('id_jurusan', 'id_jurusan', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}