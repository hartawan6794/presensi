<?php

namespace App\Controllers;

use App\Models\PresensiModel;

class Home extends BaseController
{
    protected $presensi;

    public function __construct()
    {
        helper('settings');
        $this->presensi         = new PresensiModel();
    }

    public function index(): string
    {


        $data = [
            'controller'        => 'home',
            'title'             => 'Dashboard',
            'bulan'             => tgl_indo(date(('m')))
        ];

        return view('dashboard', $data);
    }

    function cekKataKunci($keterangan)
    {
        // Kata kunci yang ingin Anda cek
        $kata_kunci = array('libur', 'raya', 'hari', 'cuti', 'izin');
        $ada = 0;
        foreach ($kata_kunci as $kata) {
            // Mengecek jika kata kunci ada dalam string menggunakan stripos() (case-insensitive)
            if (stripos($keterangan, $kata) !== false)
                $ada += 1;
        }
        return $ada > 0 ? true :  false;
    }

    public function data()
    {

        $presensi = array();
        $id     = session()->get('id_user');
        $bulan  = date('m');

        $data   = $this->presensi->where([
            'id_user'   => $id,
            'bulan'     => $bulan
        ])->findAll();

        $belum  = 0;
        $cuti   = 0;
        $terisi = 0;
        foreach ($data as $p) {

            if ($p->keterangan == '') {
                $belum += 1;
            } else if (strlen($p->keterangan) > 0) {
                // Loop melalui setiap kata kunci
                if ($this->cekKataKunci($p->keterangan))
                    $cuti += 1;
                else
                    $terisi += 1;
            }
        }

        $presensi['belum'] = $belum;
        $presensi['cuti'] = $cuti;
        $presensi['terisi'] = $terisi;

        return $this->response->setJSON($presensi);


    }
}
