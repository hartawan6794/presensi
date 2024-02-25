<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblUser extends Migration
{
    public function up()
    {
        $fields = [
            'id_user' => [
                'type' => 'TINYINT',
                'auto_increment' => true
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'nama_pengguna' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => true,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['admin','pegawai'],
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active','inactive'],
                'null' => true,
            ],
            'id_jabatan' => [
                'type' => 'TINYINT',
                'constraint' => 3,
                'null' => true,
            ]
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id_user');
        $this->forge->addForeignKey('id_jabatan','tbl_jabatan','id_jabatan','CASCADE','CASCADE');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('tbl_user', true, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_user');
    }
}
