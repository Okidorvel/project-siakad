<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Login extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //jika sess user dan pass sesuai
        if ($this->session->userdata('username') AND $this->session->userdata('password') AND $this->session->userdata('level')=='admin') {
            redirect(base_url('admin'));
        }
        $this->load->model(array('M_login'));
    }

    //menampilkan halaman Login
    function index()
    {
        $this->load->view('login');
    }

    //proses login
    function proses()
    {
        $this->form_validation->set_rules('username', 'username', 'required|trim|xss_clean');
        $this->form_validation->set_rules('password', 'password', 'required|trim|xss_clean');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $user = $username;
            $pass = md5($password);

            $cek = $this->M_login->cek($user, $pass);
            if ($cek->num_rows() > 0) {
                //buat session untuk ditampilkan
                foreach ($cek->result() as $qad) {
                    $sess_data['username'] = $qad->username;
                    $sess_data['email'] = $qad->email;
                    $sess_data['level'] = $qad->level;
                    $this->session->set_userdata($sess_data);
                }
                if ($sess_data['level'] == 'admin') {
                    $this->session->set_flashdata('success',  'Login Berhasil !');
                    redirect(base_url('admin'));
                } else {
                    $this->session->set_flashdata('result_login', '<br>Username atau Password yang anda masukan salah');
                    redirect(base_url('login'));
                }
            } else {
                $this->session->set_flashdata('result_login', '<br>Username atau Password yang anda masukan salah');
                redirect(base_url('login'));
            }
        }
    }
}

?>