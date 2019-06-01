<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Users extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_users');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        //jika session username tidak ada
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }
        //tampil data berdasarkan id
        $rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
        $dataAdm = array(
                    'wa'        => 'Web Administrator',
                    'univ'      => 'STIKOM POLTEK CIREBON',
                    'username'  => $rowAdm->username,
                    'email'  => $rowAdm->email,
                    'level'  => $rowAdm->level
        );
        $this->load->view('header_list', $dataAdm);
        $this->load->view('users/users_list');
        $this->load->view('footer_list');
    }

    //fungsi json
    public function json() {
        header('Content-Type: application/json');
        echo $this->M_users->json();
    }

    //fungsi tampil form create users
    public function create()
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
                    'email'  => $rowAdm->email,
                    'level'  => $rowAdm->level
        );

        //menampung input data
        $data = array(
                'button' => 'Create',
                'back'  => site_url('users'),
                'action' => site_url('users/create_action'),
                'username' => set_value('username'),
                'nama_user' => set_value('nama_user'),
                'password' => set_value('password'),
                'email' => set_value('email'),
                'level' => set_value('level'),
                'blokir' => set_value('blokir'),
        );
        $this->load->view('header',$dataAdm);
        $this->load->view('users/users_form',$data);
        $this->load->view('footer');
    }

    //fungsi simpan data
    public function create_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        }else {
            $data = array(
                    'username' => $this->input->post('username', TRUE),
                    'password' => md5($this->input->post('password', TRUE)),
                    'nama_user' => $this->input->post('nama_user',TRUE),
                    'email' => $this->input->post('email', TRUE),
                    'level' => $this->input->post('level', TRUE),
                    'blokir' => $this->input->post('blokir', TRUE),
                    'id_sessions' => md5($this->input->post('password', TRUE))
            );
            $this->M_users->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('users'));
        }
    }

    //fungsi menampilkan form users
    public function update($id)
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
                    'email'  => $rowAdm->email,
                    'level'  => $rowAdm->level
        );

        $row = $this->M_users->get_by_id($id);
        if ($row) {
            $data = array(
                    'button' => 'Update',
                    'back'  => site_url('users'),
                    'action'  => site_url('users/update_action'),
                    'username' => set_value('username', $row->username),
                    'nama_user' => set_value('nama_user', $row->nama_user),
                    'email' => set_value('email', $row->email),
                    'level' => set_value('level', $row->level),
                    'blokir' => set_value('blokir', $row->blokir)
            );
            $this->load->view('header',$dataAdm);
            $this->load->view('users/users_form',$data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record tidak ada');
            redirect(site_url('users'));
        }
    }

    //fungsi update data
    public function update_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('username', TRUE));
        } else {
            $data = array(
                'username' => $this->input->post('username', TRUE),
                'password' => $this->input->post('password', TRUE),
                'nama_user' => $this->input->post('nama_user', TRUE),
                'email' => $this->input->post('email', TRUE),
                'level' => $this->input->post('level', TRUE),
                'blokir' => $this->input->post('blokir', TRUE),
                'id_sessions' => md5($this->input->post('password', TRUE))
            );
            $this->M_users->update($this->input->post('username', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('users'));
        }
    }

    //fungsi delete
    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }
        $row = $this->M_users->get_by_id($id);
        if ($row) {
            $this->M_users->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('users'));
        } else {
            $this->session->set_flashdata('message', 'Record Tidak Tersedia');
            redirect(site_url('users'));
        }
    }

    //fungsi rules
    public function _rules()
    {
        $this->form_validation->set_rules('username', 'username', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('nama_user', 'nama_user', 'trim|required');
        $this->form_validation->set_rules('level', 'level', 'trim|required');
        $this->form_validation->set_rules('blokir', 'blokir', 'trim|required');
        $this->form_validation->set_rules('username', 'username', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
    }
}

?>