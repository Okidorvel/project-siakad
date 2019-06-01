<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Menu extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_menu');
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
        $this->load->view('menu/menu_list');
        $this->load->view('footer_list');
    }

    //fungsi json
    public function json()
    {
        header('Content-type: application/json');
        echo $this->M_menu->json();
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
                    'univ'      => 'STIKOM POLTEK CIREBON',
                    'username'  => $rowAdm->username,
                    'email'  => $rowAdm->email,
                    'level'  => $rowAdm->level
        );

        //menampung input data
        $data = array(
            'button' => 'Create',
            'back'  => site_url('menu'),
            'action' => site_url('menu/create_action'),
            'id_menu' => set_value('id_menu'),
            'nama_menu' => set_value('nama_menu'),
            'link' => set_value('link'),
            'icon' => set_value('icon'),
            'main_menu' => set_value('main_menu')
        );
        $this->load->view('header',$dataAdm);
        $this->load->view('menu/menu_form',$data);
        $this->load->view('footer');
    }

    //fungsi simpan data
    function create_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        }else {
            $data = array(
                    'nama_menu' => $this->input->post('nama_menu', TRUE),
                    'link' => $this->input->post('link', TRUE),
                    'icon' => $this->input->post('icon', TRUE),
                    'main_menu' => $this->input->post('main_menu', TRUE)
            );
            $this->M_menu->insert($data);
            $this->session->set_flashdata('message', 'Berhasil Menambahkan Data');
            redirect(site_url('menu'));
        }
    }

    //menampilkan form update
    public function update($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }

        $rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
        $dataAdm = array(
                    'wa'        => 'Web Administrator',
                    'univ'      => 'STIKOM POLTEK CIREBON',
                    'username'  => $rowAdm->username,
                    'email'  => $rowAdm->email,
                    'level'  => $rowAdm->level
        );
        $row = $this->M_menu->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'back'  => site_url('menu'),
                'action' => site_url('menu/update_action'),
                'id_menu' => set_value('id_menu', $row->id_menu),
                'nama_menu' => set_value('nama_menu', $row->nama_menu),
                'link' => set_value('link'),
                'icon' => set_value('icon'),
                'main_menu' => set_value('main_menu', $row->main_menu),
            );
            $this->load->view('header', $dataAdm);
            $this->load->view('menu/menu_form', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Data Tidak Ada');
            redirect(site_url('menu'));
        }
    }

    //fungsi update
    public function update_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_menu', TRUE));
        } else {
            $data = array(
                'nama_menu'     => $this->input->post('nama_menu', TRUE),
                'link'          => $this->input->post('link', TRUE),
                'icon'          => $this->input->post('icon', TRUE),
                'main_menu'     => $this->input->post('main_menu', TRUE)
            );
            $this->M_menu->update($this->input->post('id_menu', TRUE), $data);
            $this->session->set_flashdata('message', 'Berhasil Mengupdate data');
            redirect(site_url('menu'));
        }
    }

    //fungsi delete
    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }
        $row = $this->M_menu->get_by_id($id);
        if ($row) {
            $this->M_menu->delete($id);
            $this->session->set_flashdata('message', 'Berhasil Menghapus data');
            redirect(site_url('menu'));
        } else {
            $this->session->set_flashdata('message', 'Data Tidak Ada');
            redirect(site_url('menu'));
        }
    }

    //fungsi rules
    public function _rules()
    {
        $this->form_validation->set_rules('nama_menu', 'nama_menu', 'trim|required');
        $this->form_validation->set_rules('link', 'link', 'trim|required');
        $this->form_validation->set_rules('icon', 'icon', 'trim|required');
        $this->form_validation->set_rules('main_menu', 'main_menu', 'trim|required');
        $this->form_validation->set_rules('id_menu', 'id_menu', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

?>

