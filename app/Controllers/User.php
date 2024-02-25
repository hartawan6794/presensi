<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\UserModel;
use App\Models\JabatanModel;

class User extends BaseController
{

	protected $userModel;
	protected $validation;
	protected $jabatanModel;

	public function __construct()
	{
		$this->jabatanModel = new JabatanModel();
		$this->userModel = new UserModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'user',
			'title'     		=> 'Pengguna',
			'jabatan'			=> $this->jabatanModel->getJabatan(),
			'role'				=> $this->userModel->getRole()
		];

		return view('user', $data);
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		$result = $this->userModel->select()->join('tbl_jabatan tj', 'tbl_user.id_jabatan = tj.id_jabatan')->findAll();

		$no = 1;
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			$value->status == 'inactive' ? $ops .= '<a class="dropdown-item text-success" onClick="aktivasi(' . $value->id_user . ')"><i class="fa-solid fa-check"></i>   ' .  lang("Aktivasi")  . '</a>' : '';
			$ops .= '<a class="dropdown-item text-info" onClick="save(' . $value->id_user . ')"><i class="fa-solid fa-pen-to-square"></i>   ' .  lang("Ubah")  . '</a>';
			$ops .= '<div class="dropdown-divider"></div>';
			$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id_user . ')"><i class="fa-solid fa-trash"></i>   ' .  lang("Hapus")  . '</a>';
			$ops .= '</div></div>';

			$data['data'][$key] = array(
				$no,
				$value->username,
				$value->nama_pengguna,
				$value->role,
				$value->status,
				$value->jabatan,
				$ops
			);
			$no++;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_user');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->userModel->where('id_user', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();
		
		$fields['id_user'] = $this->request->getPost('id_user');
		$fields['username'] = $this->request->getPost('username');
		$fields['password'] = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
		$fields['nama_pengguna'] = $this->request->getPost('nama_pengguna');
		$fields['role'] = $this->request->getPost('role');
		$fields['status'] = 'inactive';
		$fields['id_jabatan'] = $this->request->getPost('jabatan');

		$this->validation->setRules([
			'username' => ['label' => 'Username', 'rules' => 'required|min_length[4]|userExist[username]|cekSpasi[username]', 'errors' => [
				'required'		=> 'Username tidak boleh kosong',
				'cekSpasi'		=> 'Harap mengisi username tanpa menggunakan spasi',
				'min_length'	=> 'Minimal panjang username berjumalh 4 karakter',
				'userExist'		=> 'Username sudah digunakan'
			]],
			'password' => ['label' => 'Password', 'rules' => 'required|min_length[4]', 'errors' => [
				'required'		=> 'Password tidak boleh kosong',
				'min_length'	=> 'Minimal panjang password berjumalh 4 karakter',
			]],
			'nama_pengguna' => ['label' => 'Nama pengguna', 'rules' => 'required', 'errors' => [
				'required'		=> 'Harap masukan nama lengkap anda, mau dipanggil siapa anda jika tanpa nama'
			]],
			'id_jabatan' => ['label' => 'Jabatan', 'rules' => 'required', 'errors' => [
				'required'		=> 'Harap masukan jabatan anda jika masih ingin kerja'
			]],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {
			// var_dump($fields);die;

			if ($this->userModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = lang("Berhasil menambahkan data");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("Gagal menambahkan data");
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{
		$response = array();

		$fields['id_user'] = $this->request->getPost('id_user');
		$fields['username'] = $this->request->getPost('username');
		$fields['nama_pengguna'] = $this->request->getPost('nama_pengguna');
		$fields['role'] = $this->request->getPost('role');
		$fields['id_jabatan'] = $this->request->getPost('jabatan');


		$this->validation->setRules([
			'username' => ['label' => 'Username', 'rules' => 'required|min_length[4]|userExist[username]|cekSpasi[username]', 'errors' => [
				'required'		=> 'Username tidak boleh kosong',
				'cekSpasi'		=> 'Harap mengisi username tanpa menggunakan spasi',
				'min_length'	=> 'Minimal panjang username berjumalh 4 karakter',
				'userExist'		=> 'Username sudah digunakan'
			]],
			'password' => ['label' => 'Password', 'rules' => 'required|min_length[4]', 'errors' => [
				'required'		=> 'Password tidak boleh kosong',
				'min_length'	=> 'Minimal panjang password berjumalh 4 karakter',
			]],
			'nama_pengguna' => ['label' => 'Nama pengguna', 'rules' => 'required', 'errors' => [
				'required'		=> 'Harap masukan nama lengkap anda, mau dipanggil siapa anda jika tanpa nama'
			]],
			'jabatan' => ['label' => 'Jabatan', 'rules' => 'required', 'errors' => [
				'required'		=> 'Harap masukan jabatan anda jika masih ingin kerja'
			]],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->userModel->update($fields['id_user'], $fields)) {

				$response['success'] = true;
				$response['messages'] = lang("Berhasil perbarui data");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("Gagal Perbarui data");
			}
		}

		return $this->response->setJSON($response);
	}
	

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('id_user');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->userModel->where('id_user', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = lang("Berhasil menghapus data");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("Gagal menghapus data");
			}
		}

		return $this->response->setJSON($response);
	}

	public function aktivasi()
	{
		$response = array();

		$id = $this->request->getPost('id_user');
		$field['status'] = 'active';

		if ($this->userModel->update($id, $field)) {

			$response['success'] = true;
			$response['messages'] = lang("Berhasil mengaktifkan user");
		} else {

			$response['success'] = false;
			$response['messages'] = lang("Gagal mengaktifkan user");
		}


		return $this->response->setJSON($response);
	}
}
