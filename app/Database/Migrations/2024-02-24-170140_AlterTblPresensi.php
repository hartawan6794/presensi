<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTblPresensi extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_presensi', [
            'bulan' => [
                'type'      => 'TINYINT',
                'null'      => false
            ]
        ]);
    }

    public function down()
    {
        //
    }
}
