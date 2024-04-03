<?php

namespace App\Controllers;

use App\Models\AgendaModel;
use App\Models\PresensiModel;

class Home extends BaseController
{
    protected $presensi;
    protected $agenda;

    public function __construct()
    {
        helper('settings');
        $this->presensi         = new PresensiModel();
        $this->agenda           = new AgendaModel();
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

    public function getAgenda()
    {
        $response = array();

        $data['data'] = array();

        $bulan = date('m');
        $result = $this->agenda->select('*')->where('agenda_bulan', $bulan)->findAll();

        foreach ($result as $key => $value) {

            if (session()->get('role') == 'admin') {
                $ops = '<div class="btn-group">';
                $ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="edit(' . $value->id_agenda . ')"><i class="fa fa-edit"></i></button>';
                $ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="remove(' . $value->id_agenda . ')"><i class="fa fa-trash"></i></button>';
                $ops .= '</div>';
            }else{
                $ops = '';
            }

            $data['data'][$key] = array(
                tgl_indo($value->created_at),
                $value->ket_agenda,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function addAgenda()
    {

        $response = array();

        $fields['id_agenda'] = $this->request->getPost('idAgenda');
        $fields['ket_agenda'] = $this->request->getPost('ket_agenda');
        $fields['agenda_bulan'] = date('m');
        $fields['created_at'] = $this->request->getPost('tanggal');
        $fields['created_by'] = session()->get('id_user');

        // var_dump($fields);die;
        if ($this->agenda->insert($fields)) {

            $response['success'] = true;
            $response['messages'] = 'Data has been inserted successfully';
        } else {

            $response['success'] = false;
            $response['messages'] = 'Insertion error!';
        }

        return $this->response->setJSON($response);
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
