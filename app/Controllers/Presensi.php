<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\PresensiModel;
use DateTime;
use Exception;

class Presensi extends BaseController
{

	protected $presensiModel;
	protected $validation;
	protected $session;
	protected $month;

	public function __construct()
	{
		$this->presensiModel = new PresensiModel();
		$this->validation =  \Config\Services::validation();
		$this->session = \Config\Services::session();
		helper('settings');
		$this->month    = config('VarMonth');
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'presensi',
			'title'     		=> 'Daftar Presensi',
			'bulan'				=> $this->month,
			'bulan_ini'			=> date('m') . ' , ' . tgl_indo(date('m')),
		];

		// var_dump($data);die;
		return view('presensi', $data);
	}

	public function getSelectedMonth()
	{
		$response = $data['data'] = array();
		$bulan = $this->request->getPost('bulan');

		$id_user = $this->session->get('id_user');

		$result = $this->presensiModel->select()->where('bulan', $bulan)->where('tbl_presensi.id_user', $id_user)->findAll();

		$no = 1;
		foreach ($result as $key => $value) {

			$ops = $value->id_presensi;

			$data['data'][$key] = array(
				$no,
				hariIndonesia(date('l', strtotime($value->tgl_presensi))),
				tgl_indo($value->tgl_presensi),
				$value->keterangan,
				$ops
			);

			$no++;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_presensi');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->presensiModel->where('id_presensi', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = [];

		$data['bulan'] = $this->request->getPost('bulan');
		$data['id_user'] = $this->session->get('id_user');

		// Hapus data sebelumnya berdasarkan id_user dan bulan
		$this->presensiModel->where([
			'id_user' => $data['id_user'],
			'bulan' => $data['bulan'],
		])->delete();

		// Validasi input
		$this->validation->setRules([
			'id_user' => ['label' => 'Pengguna', 'rules' => 'permit_empty|min_length[0]|max_length[4]'],
			'bulan' => ['label' => 'Bulan', 'rules' => 'required', 'errors' => [
				'required' => 'Harus pilih bulan terlebih dahulu',
			]],
			'keterangan' => ['label' => 'Keterangan', 'rules' => 'permit_empty|min_length[0]|max_length[255]'],
		]);

		if ($this->validation->run($data) == FALSE) {
			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors();
		} else {
			$bulanPilihan = $data['bulan'];
			$tahun = date('Y');
			$tanggalString = $tahun . '-' . $bulanPilihan . '-01';

			try {
				$dateTime = new DateTime($tanggalString);
				$tanggalAwal = $dateTime->format('Y-m-01');
				$tanggalAkhir = $dateTime->format('Y-m-t');

				// Ambil data kalender dengan cache
				$cacheKey = 'kalender_' . $bulanPilihan;
				$dataKalender = cache()->get($cacheKey);
				if (!$dataKalender) {
					$dataKalender = $this->kalenderApi($bulanPilihan);
					cache()->save($cacheKey, $dataKalender, 3600); // Simpan 1 jam
				}

				// Optimalkan data hari libur
				$liburNasional = [];
				foreach ($dataKalender as $holiday) {
					if ($holiday->is_national_holiday) {
						$liburNasional[$holiday->holiday_date] = $holiday->holiday_name;
					}
				}

				$fields = [];

				// Iterasi tanggal
				for ($tanggal = $tanggalAwal; $tanggal <= $tanggalAkhir; $tanggal = date('Y-m-d', strtotime($tanggal . ' +1 day'))) {
					$hari = date('N', strtotime($tanggal));
					if ($hari == 6 || $hari == 7) {
						$keterangan = "Libur";
					} elseif (isset($liburNasional[$tanggal])) {
						$keterangan = $liburNasional[$tanggal];
					} else {
						$keterangan = "";
					}

					$fields[] = [
						'tgl_presensi' => $tanggal,
						'id_user' => $data['id_user'],
						'keterangan' => $keterangan,
						'bulan' => $bulanPilihan,
					];
				}

				// Simpan data dengan insertBatch
				$batchSize = 100;
				$chunks = array_chunk($fields, $batchSize);
				foreach ($chunks as $chunk) {
					if (!$this->presensiModel->insertBatch($chunk)) {
						$response['success'] = false;
						$response['messages'] = lang("Gagal menambahkan data");
						return $this->response->setJSON($response);
					}
				}

				$response['success'] = true;
				$response['messages'] = lang("Berhasil menambahkan data");
			} catch (Exception $e) {
				log_message('error', 'Error: ' . $e->getMessage());
				$response['success'] = false;
				$response['messages'] = 'Terjadi kesalahan saat memproses data.';
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{
		$response = array();

		$fields['id_presensi'] = $this->request->getPost('id_presensi');
		$fields['keterangan'] = $this->request->getPost('keterangan');
		// $fields['id_user'] = '1';

		// var_dump($fields);die

		$this->validation->setRules([
			'id_user' => ['label' => 'Pengguna', 'rules' => 'permit_empty|min_length[0]|max_length[4]'],
			'keterangan' => ['label' => 'Keterangan', 'rules' => 'permit_empty|min_length[0]|max_length[255]'],
		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->presensiModel->update($fields['id_presensi'], $fields)) {

				$response['success'] = true;
				$response['messages'] = lang("Berhasil perbarui data");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("Gagal Perbarui data");
			}
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('id_presensi');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->presensiModel->where('id_presensi', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = lang("Berhasil menghapus data");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("Gagal menghapus data");
			}
		}

		return $this->response->setJSON($response);
	}

	public function kalenderApi($month = null)
	{
		// URL API
		$url = 'https://api-harilibur.vercel.app/api?month=' . $month . '&year=2024';

		// Inisialisasi Curl
		$ch = curl_init();

		// Set URL dan opsi lainnya
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Eksekusi Curl
		$response = curl_exec($ch);

		// Periksa apakah ada kesalahan
		if (curl_errno($ch)) {
			echo 'Error: ' . curl_error($ch);
		}

		// Tutup Curl
		curl_close($ch);

		// Ubah respons menjadi objek
		$responseObject = json_decode($response);

		// Tampilkan objek
		return $responseObject;
	}
}
