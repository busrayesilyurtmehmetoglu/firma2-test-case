<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIntegrationLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'source'     => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'endpoint'   => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'params'     => [
                'type' => 'LONGTEXT',
            ],
            'status'     => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'message'    => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('integration_logs');
    }

    public function down()
    {
        $this->forge->dropTable('integration_logs');
    }
} 