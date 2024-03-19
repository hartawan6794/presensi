<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTblUser extends Migration
{
    public function up()
    {
        $fields = array(
            'img_user' => array(
                // 'name' => 'nama_lengkap',
                'type'          => 'VARCHAR',
                'constraint'    => 255,
                'null'          => true
            ),
        );

        $this->forge->addColumn('tbl_user', $fields);
    }

    public function down()
    {
        //
    }
}
