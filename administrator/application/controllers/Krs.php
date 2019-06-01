<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Krs extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_krs');
        $this->load->model('M_mahasiswa');
        $this->load->model('M_prodi');
        $this->load->model('M_thn_akad');
        $this->load->model('M_users');
        $this->load->library('form_validation');

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
                    'email'  	=> $rowAdm->email,
                    'level'  	=> $rowAdm->level
        );

        //menampung data yg di input
        $data = array(
			'button'        	=> 'Proses',
			'back'          	=> site_url('krs'),
			'action'        	=> site_url('krs/krs_action'),
			'nim'    			=> set_value('nim'),
			'id_thn_akad'  	=> set_value('id_thn_akad')
        );
        $this->load->view('header', $dataAdm);
        $this->load->view('krs/mhs_form', $data);
        $this->load->view('footer');
    }

    public function krs_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }
        $this->_rulesKrs();

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $nim = $this->input->post('nim', TRUE);
            $thn_akad = $this->input->post('id_thn_akad', TRUE);

            if ($this->M_mahasiswa->get_by_id($nim) == NULL) {
                exit('Nomor mahasiswa belum terdaftar');
            }

            //tampil data berdasarkan id
            $rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
            $dataAdm = array(
                        'wa'        => 'Web Administrator',
                        'univ'      => 'STIKOM POLTEK CIREBON',
                        'username'  => $rowAdm->username,
                        'email'  	=> $rowAdm->email,
                        'level'  	=> $rowAdm->level
            );

            $data = array(
                    'action'        => site_url('krs/daftar_krs_action'),
                    'nim'           =>$nim,
                    'id_thn_akad'   =>$thn_akad,
                    'nama_lengkap'  =>$this->M_mahasiswa->get_by_id($nim)->nama_lengkap
            );

            $dataKrs = array(
                'button'        	=> 'Create',
                'back'          	=> site_url('krs'),
                'krs_data'        	=> $this->baca_krs($nim,$thn_akad),
                'nim'    			=> $nim,
                'id_thn_akad'    	=> $thn_akad,
                'thn_akad'  	    => $this->M_thn_akad->get_by_id($thn_akad)->thn_akad,
                'semester'  	    => $this->M_thn_akad->get_by_id($thn_akad)->semester==1?'Ganjil':'Genap',
                'nama_lengkap'  	    => $this->M_prodi->get_by_id($this->M_mahasiswa->get_by_id($nim)->id_prodi)->nama_prodi,
                'prodi'=>$this->M_prodi->get_by_id($this->M_mahasiswa->get_by_id($nim)->id_prodi)->nama_prodi,
            );
            $this->load->view('header', $dataAdm);
            $this->load->view('krs/krs_list', $dataKrs);
            $this->load->view('footer');
        }
    }

    function baca_krs($nim, $thn_akad)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }

        $this->db->select('k.id_krs,k.kode_matakuliah,m.nama_matakuliah,m.sks');
        $this->db->from('krs as k');
        $this->db->where('k.nim', $nim);
        $this->db->where('k.id_thn_akad', $thn_akad);
        $this->db->join('matakuliah as m', 'm.kode_matakuliah = k.kode_matakuliah');
        $krs = $this->db->get()->result();
        return $krs;
    }

    public function _rulesKrs()
    {
        $this->form_validation->set_rules('nim', 'nim', 'trim|required|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('id_thn_akad', 'id_thn_akad', 'trim|required');
    }

    public function create($nim,$th)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }

        $rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
        $dataAdm = array(
                    'wa'        => 'Web Administrator',
                    'univ'      => 'STIKOM POLTEK CIREBON',
                    'username'  => $rowAdm->username,
                    'email'  	=> $rowAdm->email,
                    'level'  	=> $rowAdm->level
        );

        //menampung data yg di input
        $data = array(
			'button'        	=> 'Create',
			'judul'          	=> 'Tambah',
			'back'        	    => site_url('krs'),
			'action'    		=> site_url('krs/create_action'),
            'id_krs'  	        => set_value('id_krs'),
            'id_thn_akad'       =>$th,
            'thn_akad_smt'      =>$this->M_thn_akad->get_by_id($th)->thn_akad,
            'semester'  	    => $this->M_thn_akad->get_by_id($th)->semester==1?'Ganjil':'Genap',
            'nim'               =>$nim,
            'kode_matakuliah'   => set_value('kode_matakuliah'),
        );
        $this->load->view('header', $dataAdm);
        $this->load->view('krs/krs_form', $data);
        $this->load->view('footer');
    }

    public function create_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->create($this->input->post('nim', TRUE),
            $this->input->post('id_thn_akad', TRUE));
        } else {
            $nim = $this->input->post('nim', TRUE);
            $id_thn_akad = $this->input->post('id_thn_akad',TRUE);
            $kode_matakuliah = $this->input->post('kode_matakuliah', TRUE);

            $rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
            $dataAdm = array(
                        'wa'        => 'Web Administrator',
                        'univ'      => 'STIKOM POLTEK CIREBON',
                        'username'  => $rowAdm->username,
                        'email'  	=> $rowAdm->email,
                        'level'  	=> $rowAdm->level
            );

            $data = array(
                'id_thn_akad'       =>$id_thn_akad,
                'nim'               =>$nim,
                'kode_matakuliah'  	=> $kode_matakuliah
            );

            $this->M_krs->insert($data);

            $dataKrs = array(
                'button'        	=> 'Create',
                'Judul'             => 'Tambah',
                'back'          	=> site_url('krs'),
                'krs_data'        	=> $this->baca_krs($nim,$id_thn_akad),
                'nim'    			=> $nim,
                'id_thn_akad'    	=> $id_thn_akad,
                'thn_akad'  	    => $this->M_thn_akad->get_by_id($id_thn_akad)->thn_akad,
                'semester'  	    => $this->M_thn_akad->get_by_id($id_thn_akad)->semester==1?'Ganjil':'Genap',
                'nama_lengkap'  	    => $this->M_prodi->get_by_id($this->M_mahasiswa->get_by_id($nim)->id_prodi)->nama_prodi,
                'prodi' => $this->M_prodi->get_by_id($this->M_mahasiswa->get_by_id($nim)->id_prodi)->nama_prodi
            );
            $this->session->set_flashdata('message', 'Create Record Success');
            $this->load->view('header', $dataAdm);
            $this->load->view('krs/krs_list', $dataKrs);
            $this->load->view('footer');
        }

    }

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
                    'email'  	=> $rowAdm->email,
                    'level'  	=> $rowAdm->level
        );

        $row = $this->M_krs->get_by_id($id);
        $th = $row->id_thn_akad;

        if ($row) {
            $data = array(
                    'judul'  => 'Ubah',
                    'back'   => site_url('krs'),
                    'button' => 'Update',
                    'action' => site_url('krs/update_action'),
                    'id_krs' => set_value('id_thn_akad', $row->id_krs),
                    'id_thn_akad' => set_value('id_thn_akad', $row->id_thn_akad),
                    'nim' => set_value('nim', $row->nim),
                    'kode_matakuliah' => set_value('kode_matakuliah', $row->kode_matakuliah),
                    'thn_akad_smt' => $this->M_thn_akad->get_by_id($th)->thn_akad,
                    'semester' => $this->M_thn_akad->get_by_id($th)->semester==1?'Ganjil':'Genap',
            );

            $this->load->view('header', $dataAdm);
            $this->load->view('krs/krs_form', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
        }
    }

    public function update_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }

        $this->_rules();


        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_krs',TRUE));
        } else {
            $rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
            $dataAdm = array(
                        'wa'        => 'Web Administrator',
                        'univ'      => 'STIKOM POLTEK CIREBON',
                        'username'  => $rowAdm->username,
                        'email'  	=> $rowAdm->email,
                        'level'  	=> $rowAdm->level
            );

            $id_krs = $this->input->post('id_krs', TRUE);
            $nim    = $this->input->post('nim', TRUE);
            $id_thn_akad = $this->input->post('id_thn_akah', TRUE);
            $kode_mk = $this->input->post('kode_matakuliah', TRUE);

            $data = array(
                    'id_krs' => $id_krs,
                    'id_thn_akad' => $id_thn_akad,
                    'nim' => $nim,
                    'kode_matakuliah' => $this->input->post('kode_matakuliah', TRUE)
            );

            //update krs
            $this->M_krs->update($id_krs, $data);
            $this->session->set_flashdata('message', 'Update Record Success');

            //tampil data krs
            $dataKrs = array(
                        'krs_data' => $this->baca_krs($nim,$id_thn_akad),
                        'nim' =>$nim,
                        'id_thn_akad' => $id_thn_akad,
                        'thn_akad' => $this->M_thn_akad->get_by_id($id_thn_akad),
                        'semester' => $this->M_thn_akad->get_By_id($id_thn_akad)->semester==1?'Ganjil':'Genap',
                        'nama_lengkap' => $this->M_mahasiswa->get_by_id($nim)->nama_lengkap,
                        'prodi' => $this->M_prodi->get_by_id($this->M_mahasiswa->get_by_id($nim)->id_prodi)->nama_prodi
            );
            $this->load->view('header', $dataAdm);
            $this->load->view('krs/krs_list', $dataKrs);
            $this->load->view('footer');
        }
    }

    // Fungsi untuk melakukan aksi delete data berdasarkan id yang dipilih
    public function delete($id){
		// Jika session data username tidak ada maka akan dialihkan kehalaman login
		if (!isset($this->session->userdata['username'])) {
			redirect(base_url("login"));
		}

        $row = $this->M_krs->get_by_id($id);
		$nim = $this->M_krs->get_by_id($id)->nim;
		$id_thn_akad=$this->M_krs->get_by_id($id)->id_thn_akad;

		//jika id krs (nim dan id_thn_akad) yang dipilih tersedia maka akan dihapus
        if ($row) {
            $this->M_krs->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            //redirect(site_url('krs'));
        }
		//jika id  krs (nim dan id_thn_akad) yang dipilih tidak tersedia maka akan muncul pesan 'Record Not Found'
		else {
           $this->session->set_flashdata('message', 'Record Not Found');
		}

		// Menampilkan data berdasarkan id-nya yaitu username
		$rowAdm = $this->Users_model->get_by_id($this->session->userdata['username']);
		$dataAdm = array(
			'wa'       => 'Web administrator',
			'univ'     => 'Universitas Langit Inspirasi',
			'username' => $rowAdm->username,
			'email'    => $rowAdm->email,
			'level'    => $rowAdm->level,
		);

		  // Menampilkan data KRS
		  $dataKrs=array(
				   'button' =>'Tambah',
				   'back' => site_url('krs'),
				   'krs_data' => $this->baca_krs($nim,$id_thn_akad),
	               'nim' => $nim,
				   'id_thn_akad' => $id_thn_akad,
				   'thn_akad' => $this->Thn_akad_semester_model->get_by_id($id_thn_akad)->thn_akad,
				   'semester' => $this->Thn_akad_semester_model->get_by_id($id_thn_akad)->semester==1?'Ganjil':'Genap',
				   'nama_lengkap' => $this->Mahasiswa_model->get_by_id($nim)->nama_lengkap,
				   'prodi' => $this->Prodi_model->get_by_id(
				    $this->Mahasiswa_model->get_by_id($nim)->id_prodi)->nama_prodi,
				   );

            $this->load->view('header',$dataAdm); // Menampilkan bagian header dan object data users
		    $this->load->view('krs/krs_list',$dataKrs); // Menampilkan data KRS
			$this->load->view('footer'); // Menampilkan bagian footer
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nim', 'nim', 'trim|required');
        $this->form_validation->set_rules('kode_matakuliah', 'kode_matakuliah', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
    }
}

