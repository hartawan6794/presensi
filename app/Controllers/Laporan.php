<?php

namespace App\Controllers;

use App\Models\PresensiModel;
use App\Models\UserModel;
use Config\Session;
use TCPDF;
use FPDF;

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


    public function show()
    {
        $response = array();

        $id_user    = $this->request->getPost('user');
        $bulan      = $this->request->getPost('bulan');

        $presensis = $this->presensi->where([
            'tbl_presensi.id_user'   => $id_user,
            'bulan'     => $bulan
        ])->join('tbl_user tu', 'tu.id_user = tbl_presensi.id_user')->findAll();

        $no = 1;
        $data['data'] = array();
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

    public function cetak()
    {
        
        $fields['id_user']  = $this->request->getPost('id_user') ? $this->request->getPost('id_user') : session()->get('id_user');
        $fields['bulan']    = $this->request->getPost('bulan');

        $data = $this->presensi->select('tbl_presensi.tgl_presensi,tbl_presensi.keterangan,tbl_presensi.bulan,tu.nama_lengkap')->join('tbl_user tu','tu.id_user = tbl_presensi.id_user')->where([
            'tu.id_user'   => $fields['id_user'],
            'bulan'     => $fields['bulan']
        ])->findAll();


        foreach($data as $d){
            var_dump(hariIndonesia(date('l', strtotime($d->tgl_presensi))));
        }

        die;


        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, 'Hello World!');;

        //         // Simpan output PDF ke dalam file
        $pdfContent = $pdf->Output('', 'S');
        $filePath = WRITEPATH . '../public/laporan/laporan-' . $fields['id_user'] . '-' . $fields['bulan'] . '.pdf';

        //         // Tentukan lokasi dan nama file

        //         // Tulis konten PDF ke dalam file
        file_put_contents($filePath, $pdfContent);
    }
}
