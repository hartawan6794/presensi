<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JabatanModel;
use App\Models\UserModel;

class Registered extends BaseController
{

  protected $jab;
	protected $validation;
  protected $user;
  function __construct()
  {
    $this->jab = new JabatanModel();
    $this->user = new UserModel();
		$this->validation =  \Config\Services::validation();
  }

  public function index()
  {

    $dataJab = $this->jab->findAll();

    $data  = [ 
      'controller'    => 'registered',
      'title'         => 'registered',
      'dataJab'       => $dataJab
    ];
    return view('registered', $data);
  }

  public function registered(){
    $fields['username'] = $this->request->getPost('username');
		$fields['password'] = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
		$fields['nama_lengkap'] = $this->request->getPost('pengguna');
		$fields['role'] = 'pegawai';
		$fields['status'] = 'inactive';
		$fields['id_jabatan'] = $this->request->getPost('valueJab');
			// var_dump($fields);die;

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
			'nama_lengkap' => ['label' => 'Nama pengguna', 'rules' => 'required', 'errors' => [
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

			if ($this->user->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = lang("Berhasil menambahkan data");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("Gagal menambahkan data");
			}
		}

		return $this->response->setJSON($response);
  }

}
