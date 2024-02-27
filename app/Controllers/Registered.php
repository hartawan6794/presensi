<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JabatanModel;

class Registered extends BaseController
{

  protected $jab;
  function __construct()
  {
    $this->jab = new JabatanModel();
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
		$fields['id_jabatan'] = $this->request->getPost('jabatan');

    var_dump($fields);die;
  }

}
