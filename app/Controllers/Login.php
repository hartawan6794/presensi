<?php

namespace App\Controllers;

use App\Models\JabatanModel;
use App\Models\UserModel;

class Login extends BaseController
{
    protected $user;
    protected $session;
    protected $jab;

    function __construct()
    {
        $this->user = new UserModel();
        $this->jab = new JabatanModel();
        $this->session = \Config\Services::session();
    }

    public function index(): string
    {
        $data = [
            'title' => 'Form Login',
            'controller' => 'login'
        ];

        return view('login', $data);
    }

    public function login()
    {

        $response = array();

        $username = $this->request->getPost('username');
        $pass = $this->request->getPost('password');

        $data = $this->user->where('username',$username)->first();
        
        // Read new token and assign in $data['token']
        // Membaca token baru dan menetapkannya ke dalam properti 'token' pada objek
        // $data->token = csrf_hash();
        // var_dump($data);die;
        if ($data) {
            if ((password_verify($pass, $data->password))) {
                $session = [
                    'isLogin' => true,
                    'id_user' => $data->id_user,
                    'username' => $data->username,
                    'nama_lengkap' => $data->nama_lengkap,
                ];
                
                $this->session->set($session);
                $response['success'] = true;
                $response['message'] = "Berhasil login";
            } else {
                $response['success'] = false;
                $response['message'] = "Kata sandi salah";
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Email tidak terdaftar";
        }

        return $this->response->setJSON($response);
    }

    function logout(){
        $this->session->destroy();
        return redirect()->to('login');
    }
}
