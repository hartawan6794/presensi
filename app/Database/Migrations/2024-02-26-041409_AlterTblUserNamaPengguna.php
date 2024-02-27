<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTblUserNamaPengguna extends Migration
{
    public function up()
    {
        $fields = array(
            'nama_pengguna' => array(
                'name' => 'nama_lengkap',
                'type' => 'VARCHAR',
                'constraint' => 255,
            ),
        );

        $this->forge->modifyColumn('tbl_user', $fields);
    }

    public function down()
    {
        //
    }
}
