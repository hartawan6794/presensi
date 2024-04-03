<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblAgenda extends Migration
{
    public function up()
    {
        $fields = [
            'id_agenda' => [
                'type'              => 'INT',
                'auto_increment'    => true
            ],
            'ket_agenda' => [
                'type'              => 'varchar',
                'constraint'        => '255',
            ],
            'agenda_bulan' => [
                'type'              => 'TINYINT',
            ],
            'created_at'   => [
                'type'              => 'DATE',
                'null'              => true
            ],
            'created_by'    => [
                'type'              => 'TINYINT',
            ]
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id_agenda');
        $this->forge->addForeignKey('created_by','tbl_user','id_user','CASCADE','CASCADE');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('tbl_agenda', true, $attributes);
    }

    public function down()
    {
        // $this->forge->dropForeignKey('tbl_agenda','created_by');
        $this->forge->dropTable('tbl_agenda');
    }
}
