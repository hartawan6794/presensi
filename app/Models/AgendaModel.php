<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;
use CodeIgniter\Model;

class AgendaModel extends Model {
    
	protected $table = 'tbl_agenda';
	protected $primaryKey = 'id_agenda';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['ket_agenda', 'agenda_bulan', 'created_at', 'created_by'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	
}