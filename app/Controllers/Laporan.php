<?php

namespace App\Controllers;

use App\Models\PresensiModel;
use App\Models\UserModel;
use Config\Session;

class Laporan extends BaseController
{

    protected $users;
    protected $presensi;
    protected $session;
    protected $month;

    function __construct()
    {
        $this->users    = new UserModel();
        $this->session  = session();
        $this->month    = config('VarMonth');
		helper('settings');
        $this->presensi = new PresensiModel();
    }

    public function index(): string
    {
        $users = $this->users->findAll();

        $data = [
            'controller'        => 'laporan',
            'title'             => 'Laporan',
            'users'             => $users,
            'months'             => $this->month
        ];
        return view('laporan', $data);
    }


    public function show(){
        $response = array();

        $id_user    = $this->request->getPost('user');
        $bulan      = $this->request->getPost('bulan');

        $presensis = $this->presensi->where([
            'tbl_presensi.id_user'   => $id_user,
            'bulan'     => $bulan
        ])->join('tbl_user tu', 'tu.id_user = tbl_presensi.id_user')->findAll();

        $no = 1;
		foreach ($presensis as $key => $value) {

			$data['data'][$key] = array(
				$no,
				$value->nama_lengkap,
				tgl_indo($value->tgl_presensi),
				$value->keterangan,
				// $ops
			);

			$no++;
		}

        return $this->response->setJSON($data);
    }
}
?>