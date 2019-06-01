<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class M_login extends CI_Model
{
    //cek user dan pass
    function cek($username, $password)
    {
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        return $this->db->get('users');
    }

    //jika username cocok
    function getLoginData($user, $pass)
    {
        $u = $user;
        $p = md5($pass);
        $query_cekLogin = $this->db->get_where('users', array('username' => $user, 'password' => $pass));
        if (count($query_cekLogin->result()) >0) {
            foreach ($query_cekLogin->result() as $qck) {
                foreach ($query_cekLogin->result() as $qad) {
                    $sess_data['logged_in'] = TRUE;
                    $sess_data['username']  = $qad->username;
                    $sess_data['password']  = $qad->password;
                    $sess_data['level']     = $qad->level;
                    $this->session->set_userdata($sess_data);
                }
                redirect('admin');
            }
        } else {
            //jika username tidak cocok
            $this->session->set_flashdata('result_login','<br>Username atau Password yang anda masukan salah.');
            header('location:'.base_url().'login');
        }

    }
}
?>