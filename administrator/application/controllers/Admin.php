<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Users');
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }
    }
    //Menampilkan halaman admin
    public function index()
    {
        $row = $this->M_Users->get_by_id($this->session->userdata['username']);
        $data = array(
                'wa'        => 'Web Administrator',
                'univ'      => 'Stikom Poltek Cirebon',
                'username'  => $row->username,
                'email'     => $row->email,
                'level'     => $row->level
        );
        $this->load->view('beranda', $data);
    }

    //fungsi logout
    function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url('login'));
    }

}

/* End of file Controllername.php */


?>