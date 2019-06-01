<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Matakuliah extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_matakuliah');
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
        $this->load->view('matakuliah/matakuliah_list');
        $this->load->view('footer_list');
    }

    //fungsi json
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->M_matakuliah->json(); // Menampilkan data json yang terdapat pada Jurusan_model
    }

    public function read($id)
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

        //query tampil matakuliah dan prodi
        $sql = "SELECT * FROM prodi, matakuliah WHERE prodi.id_prodi = matakuliah.id_prodi AND matakuliah.kode_matakuliah = '$id'";
        $row = $this->db->query($sql)->row();

        //jika data ada maka tampilkan
        if ($row) {
            $data = array(
                'button'            => 'Read',
                'back'              => site_url('matakuliah'),
                'kode_matakuliah'   => $row->kode_matakuliah,
                'nama_matakuliah'   => $row->nama_matakuliah,
                'sks'               => $row->sks,
                'semester'          => $row->semester,
                'jenis'             => $row->jenis,
                'nama_prodi'        => $row->nama_prodi
            );
            $this->load->view('header', $dataAdm);
            $this->load->view('matakuliah/matakuliah_read', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('matakuliah'));
        }
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
                'button'            => 'Create',
                'back'              => site_url('matakuliah'),
                'action'            => site_url('matakuliah/create_action'),
                'kode_matakuliah'   => set_value('kode_matakuliah'),
                'nama_matakuliah'   => set_value('nama_matakuliah'),
                'sks'               => set_value('sks'),
                'semester'          => set_value('semester'),
                'jenis'             => set_value('jenis'),
                'id_prodi'          => set_value('id_prodi')
        );
        $this->load->view('header', $dataAdm);
        $this->load->view('matakuliah/matakuliah_form', $data);
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
                    'kode_matakuliah' => $this->input->post('kode_matakuliah', TRUE),
                    'nama_matakuliah' => $this->input->post('nama_matakuliah', TRUE),
                    'sks' => $this->input->post('sks', TRUE),
                    'semester' => $this->input->post('semester', TRUE),
                    'jenis' => $this->input->post('jenis', TRUE),
                    'id_prodi' => $this->input->post('id_prodi', TRUE),
            );
            $this->M_matakuliah->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('matakuliah'));
        }
    }

    // Fungsi menampilkan form Update Matakuliah
    public function update($id){
		// Jika session data username tidak ada maka akan dialihkan kehalaman login
		if (!isset($this->session->userdata['username'])) {
			redirect(base_url("login"));
		}

		// Menampilkan data berdasarkan id-nya yaitu username
		$rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
		$dataAdm = array(
			'wa'       => 'Web administrator',
			'univ'     => 'STIKOM POLTEK CIREBON',
			'username' => $rowAdm->username,
			'email'    => $rowAdm->email,
			'level'    => $rowAdm->level,
		);

		// Menampilkan data berdasarkan id-nya yaitu kode_matakuliah
        $row = $this->M_matakuliah->get_by_id($id);

		// Jika id-nya dipilih maka data matakuliah ditampilkan ke form edit matakuliah
        if ($row) {
            $data = array(
                'button' => 'Update',
				'back'   => site_url('matakuliah'),
                'action' => site_url('matakuliah/update_action'),
				'kode_matakuliah' => set_value('kode_matakuliah', $row->kode_matakuliah),
				'nama_matakuliah' => set_value('nama_matakuliah', $row->nama_matakuliah),
				'sks' => set_value('sks', $row->sks),
				'semester' => set_value('semester', $row->semester),
				'jenis' => set_value('jenis', $row->jenis),
				'id_prodi' => set_value('id_prodi', $row->id_prodi),
				);
			$this->load->view('header',$dataAdm); // Menampilkan bagian header dan object data users
            $this->load->view('matakuliah/matakuliah_form', $data); // Menampilkan form matakuliah
			$this->load->view('footer'); // Menampilkan bagian footer
        }
		// Jika id-nya yang dipilih tidak ada maka akan menampilkan pesan 'Record Not Found'
		else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('matakuliah'));
        }
    }

	// Fungsi untuk melakukan aksi update data
    public function update_action(){
		// Jika session data username tidak ada maka akan dialihkan kehalaman login
		if (!isset($this->session->userdata['username'])) {
			redirect(base_url("login"));
		}

        $this->_rules(); // Rules atau aturan bahwa setiap form harus diisi

		// Jika form matakuliah belum diisi dengan benar
		// maka sistem akan meminta user untuk menginput ulang
        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('kode_matakuliah', TRUE));
        }
		// Jika form matakuliah telah diisi dengan benar
		// maka sistem akan melakukan update data matakuliah kedalam database
		else {
            $data = array(
			'kode_matakuliah' => $this->input->post('kode_matakuliah',TRUE),
			'nama_matakuliah' => $this->input->post('nama_matakuliah',TRUE),
			'sks' => $this->input->post('sks',TRUE),
			'semester' => $this->input->post('semester',TRUE),
			'jenis' => $this->input->post('jenis',TRUE),
			'id_prodi' => $this->input->post('id_prodi',TRUE),
			);

            $this->M_matakuliah->update($this->input->post('kode_matakuliah', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('matakuliah'));
        }
    }

    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url('login'));
        }

        $row = $this->M_matakuliah->get_by_id($id);

        if ($row) {
            $this->M_matakuliah->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('matakuliah'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('matakuliah'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('kode_matakuliah', 'kode_matakuliah', 'trim|required');
        $this->form_validation->set_rules('nama_matakuliah', 'nama_matakuliah', 'trim|required');
        $this->form_validation->set_rules('sks', 'sks', 'trim|required');
        $this->form_validation->set_rules('semester', 'semester', 'trim|required');
        $this->form_validation->set_rules('jenis', 'jenis', 'trim|required');
        $this->form_validation->set_rules('id_prodi', 'id_prodi', 'trim|required');

        $this->form_validation->set_rules('kode_matakuliah', 'kode_matakuliah', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}
