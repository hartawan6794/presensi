<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblJabatan extends Migration
{
    public function up()
    {
        $fileds = [
            'id_jabatan' => [
                'type'          => 'TINYINT',
                'auto_increment'=> true
            ],
            'jabatan' => [
                'type'          => 'VARCHAR',
                'constraint'     => '255',
            ],
        ];


        $this->forge->addField($fileds);
        $this->forge->addPrimaryKey('id_jabatan');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('tbl_jabatan', true, $attributes);

    }

    public function down()
    {
        $this->forge->dropTable('tbl_jabatan');
    }
}
