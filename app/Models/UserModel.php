<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model {
    
	protected $table = 'tbl_user';
	protected $primaryKey = 'id_user';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['username', 'password', 'nama_lengkap', 'role', 'status', 'id_jabatan', 'img_user'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;
	
	
	function getRole() {
		return ['admin','pegawai'];
	}
	
}