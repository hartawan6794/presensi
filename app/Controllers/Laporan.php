<?php

namespace App\Controllers;

use App\Models\JabatanModel;
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
    protected $jab;

    function __construct()
    {
        $this->users    = new UserModel();
        $this->session  = session();
        $this->month    = config('VarMonth');
        helper('settings');
        $this->presensi = new PresensiModel();
        $this->jab = new JabatanModel();
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

        $response = array();

        $fields['id_user']  = $this->request->getPost('id_user') ? $this->request->getPost('id_user') : session()->get('id_user');
        $fields['bulan']    = $this->request->getPost('bulan');


        $data = $this->presensi->where($fields)->findAll();

        // var_dump($fields['bulan']);die;


        $pdf = new FPDF();
        $pdf->AddPage('P', [210, 290]);
        $pdf->SetFont('Arial', 'B', 12);

        $this->header($fields['id_user'], $fields['bulan'], $pdf);
        $no = 1;

        foreach ($data as $d) {

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetWidths([15, 25, 50, 100]);
            $pdf->SetAligns(["C", "L", "L", "L"]);
            $pdf->Row([
                $no,
                hariIndonesia(date('l', strtotime($d->tgl_presensi))),
                tgl_indo($d->tgl_presensi),
                $d->keterangan,
            ]);
            $no++;
        }

        
        $this->footer($fields['id_user'], $pdf);

        // Simpan output PDF ke dalam file
        $pdfContent = $pdf->Output('', 'S');
        // Tentukan lokasi dan nama file
        $filePath = WRITEPATH . '../public/dokumen/laporan-' . $fields['id_user'] . '-' . $fields['bulan'] . '.pdf';

        $bytesWritten = file_put_contents($filePath, $pdfContent);
        if ($bytesWritten !== false) {
            $response['success'] = true;
            $response['message'] = "berhasil cetak laporan";
            $response['filePath'] = base_url('dokumen/laporan-' . $fields['id_user'] . '-' . $fields['bulan'] . '.pdf');
        } else {

            $response['success'] = false;
            $response['message'] = "Gagal cetak laporan hubungi developer";
        }

        return $this->response->setJSON($response);
    }

    function header($id_user, $bulan, $pdf)
    {
        $nama = $this->users->where('id_user', $id_user)->first()->nama_lengkap;
        $jab = $this->users->join('tbl_jabatan tj', 'tj.id_jabatan = tbl_user.id_jabatan')->where('id_user', $id_user)->first()->jabatan;

        $pdf->SetFillColor(255, 255, 255);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x, $y);
        $pdf->MultiCell(190, 6, "PRESENSI KEGIATAN TIM PENDAMPING PUSDATIN BPKAD PROVINSI LAMPUNG\r\nT.A " . date('Y'), 0, 'C', 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY($x, $y + 20);
        $pdf->MultiCell(200, 6, "Nama", 0, 'L', 1);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY($x + 20, $y + 20);
        $pdf->MultiCell(200, 6, ": " . strtoupper($nama), 0, 'L', 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY($x, $y + 26);
        $pdf->MultiCell(200, 6, "Jabatan", 0, 'L', 1);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY($x + 20, $y + 26);
        $pdf->MultiCell(200, 6, ": " . strtoupper($jab), 0, 'L', 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY($x, $y + 35);
        $pdf->MultiCell(190, 6, "Periode : " . tgl_indo($bulan), 0, 'R', 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(231, 230, 230);
        $pdf->SetXY($x, $y + 42);
        $pdf->MultiCell(15, 8, "NO", 1, 'C', 1);
        $pdf->SetXY($x + 15, $y + 42);
        $pdf->MultiCell(25, 8, "HARI", 1, 'C', 1);
        $pdf->SetXY($x + 40, $y + 42);
        $pdf->MultiCell(50, 8, "TANGGAL", 1, 'C', 1);
        $pdf->SetXY($x + 90, $y + 42);
        $pdf->MultiCell(100, 8, "KETERANGAN", 1, 'C', 1);
    }

    function footer($id_user, $pdf)
    {
        $nama = $this->users->where('id_user', $id_user)->first()->nama_lengkap;
        $jab = $this->users->join('tbl_jabatan tj', 'tj.id_jabatan = tbl_user.id_jabatan')->where('id_user', $id_user)->first()->jabatan;

        $pdf->SetFillColor(255, 255, 255);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY($x+10, $y+10);
        $pdf->MultiCell(100, 6, "KASUBID PUSDATIN BPKAD", 0, 'L', 1);
        $pdf->SetXY($x+100, $y+10);
        $pdf->MultiCell(80, 6, "Bandar Lampung, ".tgl_indo(date('Y-m-d')), 0, 'R', 1);
        $pdf->SetXY($x+10, $y+16);
        $pdf->MultiCell(100, 6, "      PROVINSI LAMPUNG", 0, 'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY($x+10, $y+40);
        $pdf->MultiCell(100, 6, strtoupper("M. Nurudin Adhitama Putra, S.H, M.H"), 0, 'L', 1);
        $pdf->SetXY($x+90, $y+40);
        $pdf->MultiCell(80, 6, strtoupper($nama), 0, 'R', 1);
        $pdf->SetXY($x+10, $y+46);
        $pdf->MultiCell(100, 6, strtoupper("NIP. 19840118 201001 1 007"), 0, 'L', 1);
        $pdf->SetXY($x+115, $y+46);
        $pdf->MultiCell(80, 6, strtoupper($jab), 0, 'C', 1);

    }
}
