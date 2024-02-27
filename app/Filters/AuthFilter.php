<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Periksa apakah pengguna telah login, misalnya dengan menggunakan session atau autentikasi yang Anda gunakan.
        if (!session()->get('isLogin')) { // Ganti dengan kondisi yang sesuai untuk memeriksa login pengguna
            return redirect()->to(base_url('/login')); // Ganti '/login' dengan URL halaman login Anda.
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan tindakan setelah request.
    }
    
}
