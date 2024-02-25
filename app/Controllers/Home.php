<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'controller'        => 'home',
            'title'             => 'Dashboard',
        ];
        return view('dashboard', $data);
    }
}
