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

        $data = $this->user->join('tbl_jabatan tb','tb.id_jabatan = tbl_user.id_jabatan')->where('username',$username)->first();
        // var_dump($data->password);
        // var_dump($pass);
        // var_dump(password_verify($pass,$data->password));
        // die;
        
        if ($data) {
            if (password_verify($pass, $data->password)) {
                $session = [
                    'isLogin'       => true,
                    'id_user'       => $data->id_user,
                    'username'      => $data->username,
                    'jabatan'       => $data->jabatan,
                    'role'          => $data->role,
                    'nama_lengkap'  => $data->nama_lengkap,
                    'img_user'      => $data->img_user
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
            $response['message'] = "Username tidak terdaftar";
        }

        return $this->response->setJSON($response);
    }

    function logout(){
        $this->session->destroy();
        return redirect()->to('login');
    }
}
