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

	function countUser(){
		
		// di sql server
		//return $this->db->table('tbl_user')->countAllResults();
		//di mysql
		return $this->db->table('tbl_user')->where('role !=', 'admin')->countAllResults();
	}

	function getUserInput($bulan = null , $belum_input = null) {
		$sql = 'SELECT DISTINCT(id_user) AS user_input FROM tbl_presensi WHERE bulan = ?';
		if($belum_input != null) {
			$sql = 'SELECT id_user,role from tbl_user where id_user not IN (SELECT DISTINCT(id_user) AS user_input FROM tbl_presensi WHERE bulan = ?)';
		}
		return $this->db->query($sql,[$bulan]);
	}
	
}