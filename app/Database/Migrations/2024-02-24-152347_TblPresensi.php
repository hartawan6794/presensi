<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblPresensi extends Migration
{
    public function up()
    {
        $fileds = [
            'id_presensi' => [
                'type'              => 'INT',
                'auto_increment'    => true
            ], 
            'id_user' => [
                'type'              => 'TINYINT',
            ],
            'tgl_presensi' => [
                'type'              => 'DATE',
                'null'              => true,
            ],
            'keterangan' => [
                'type'              => 'VARchAR',
                'constraint'        => '255',
                'null'              => true,
            ]
        ];


        $this->forge->addField($fileds);
        $this->forge->addPrimaryKey('id_presensi');
        $this->forge->addForeignKey('id_user','tbl_user','id_user','CASCADE','CASCADE');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('tbl_presensi', true, $attributes);
    }

    public function down()
    {
        //
    }
}
