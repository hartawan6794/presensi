<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;
use CodeIgniter\Model;

class JabatanModel extends Model {
    
	protected $table = 'tbl_jabatan';
	protected $primaryKey = 'id_jabatan';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['jabatan'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;
	
	
	function getJabatan(){
		return $this->findAll();
	}
	
}