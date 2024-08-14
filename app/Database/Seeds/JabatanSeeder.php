<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JabatanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'jabatan' => 'Junior Programmer',
        ];

        $this->db->table('tbl_jabatan')->insert($data);
    }
}
