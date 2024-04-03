<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Master_model;
use App\Models\AgendaModel;

class Agenda extends BaseController
{

	protected $agendaModel;
	protected $validation;

	public function __construct()
	{
		$this->agendaModel = new AgendaModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'agenda',
			'judul'     		=> 'Agenda'
		];
		return view('agenda', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->agendaModel->select('*')->findAll();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="edit(' . $value->id_agenda . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="remove(' . $value->id_agenda . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->id_agenda,
				$value->ket_agenda,
				$value->agenda_bulan,
				$value->created_at,
				$value->created_by,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_agenda');

		$data = $this->agendaModel->where('id_agenda', $id)->first();

		return $this->response->setJSON($data);
	}

	public function add()
	{

		$response = array();

		$fields['id_agenda'] = $this->request->getPost('idAgenda');
		$fields['ket_agenda'] = $this->request->getPost('ketAgenda');
		$fields['agenda_bulan'] = $this->request->getPost('agendaBulan');
		$fields['created_at'] = $this->request->getPost('createdAt');
		$fields['created_by'] = $this->request->getPost('createdBy');

		if ($this->agendaModel->insert($fields)) {

			$response['success'] = true;
			$response['messages'] = 'Data has been inserted successfully';
		} else {

			$response['success'] = false;
			$response['messages'] = 'Insertion error!';
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id_agenda'] = $this->request->getPost('idAgenda');
		$fields['ket_agenda'] = $this->request->getPost('ketAgenda');
		$fields['agenda_bulan'] = $this->request->getPost('agendaBulan');
		$fields['created_at'] = $this->request->getPost('createdAt');
		$fields['created_by'] = $this->request->getPost('createdBy');



		if ($this->agendaModel->update($fields['id_agenda'], $fields)) {

			$response['success'] = true;
			$response['messages'] = 'Successfully updated';
		} else {

			$response['success'] = false;
			$response['messages'] = 'Update error!';
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('id_agenda');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->agendaModel->where('id_agenda', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Deletion succeeded';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Deletion error!';
			}
		}

		return $this->response->setJSON($response);
	}
}
