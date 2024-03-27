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
			'bulan'				=> $this->month
		];


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
		$response = array();


		// var_dump($this->request->getPost('bulan'));die;
		$data['bulan'] = $this->request->getPost('bulan');
		$data['id_user'] = $this->session->get('id_user');

		$this->presensiModel->where([
			'id_user' 		=> $data['id_user'],
			'bulan' 		=> $data['bulan'],
		])->delete();

		$this->validation->setRules([
			'id_user' => ['label' => 'Pengguna', 'rules' => 'permit_empty|min_length[0]|max_length[4]'],
			'bulan' => ['label' => 'Bulam', 'rules' => 'required', 'errors' => [
				'required' => 'Harus pilih bulan terlebih dahulu'
			]],
			'keterangan' => ['label' => 'Keterangan', 'rules' => 'permit_empty|min_length[0]|max_length[255]'],

		]);

		if ($this->validation->run($data) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {
			// Ambil bulan yang dipilih dari formulir
			$bulanPilihan = $data['bulan'];
			$tahun = date('Y');

			// Konstruksi objek DateTime dengan format yang benar
			$tanggalString = $tahun . '-' . $bulanPilihan . '-01';

			$fields = [];

			try {
				$dateTime = new DateTime($tanggalString);
				// Hitung tanggal awal dan akhir berdasarkan objek DateTime
				$tanggalAwal = $dateTime->format('Y-m-01');
				$tanggalAkhir = $dateTime->format('Y-m-t');

				// Buat catatan presensi dalam rentang tanggal
				for ($tanggal = $tanggalAwal; $tanggal <= $tanggalAkhir; $tanggal = date('Y-m-d', strtotime($tanggal . ' +1 day'))) {
					// Inisialisasi fields['tgl_presensi'] di setiap iterasi
					// $fields['tgl_presensi'] = $tanggal;
					// $fields['id_user'] 		= '1';

					// Cek jika hari Minggu atau Sabtu
					$hari = date('N', strtotime($tanggal));
					if ($hari == 6 || $hari == 7) {
						// Hari libur
						$keterangan = "Libur";
					} else {
						// Periksa apakah tanggal tersebut merupakan hari libur nasional dari API
						$tanggal_format = date('Y-m-d', strtotime($tanggal));
						$is_holiday = false;
						$dataKalender =  $this->kalenderApi($bulanPilihan);
						foreach ($dataKalender as $holiday) {
							// var_dump($tanggal_format, $holiday->holiday_date);
							if (strtotime($holiday->holiday_date) == strtotime($tanggal_format) && $holiday->is_national_holiday) {
								$keterangan = $holiday->holiday_name;
								$is_holiday = true;
								break;
							}
						}
						// Jika bukan hari libur nasional, maka hari kerja
						if (!$is_holiday) {
							$keterangan = "";
						}
					}

					array_push($fields, array(
						'tgl_presensi' 	=> $tanggal,
						'id_user' 		=> $data['id_user'],
						'keterangan' 	=> $keterangan,
						'bulan'			=> $bulanPilihan
					));
				}
				//  die;

				// Simpan catatan presensi ke dalam tabel tbl_presensi
				if ($this->presensiModel->insertBatch($fields)) {
					$response['success'] = true;
					$response['messages'] = lang("Berhasil menambahkan data");
				} else {
					$response['success'] = false;
					$response['messages'] = lang("Gagal menambahkan data");
				}
				// die;
			} catch (Exception $e) {
				echo 'Pesan Kesalahan: ' . $e->getMessage();
				// Handle kesalahan sesuai kebutuhan Anda
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
