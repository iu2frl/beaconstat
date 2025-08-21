<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'date' => [
                'type'       => 'DATETIME',
            ],
            'beacon_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'callsign' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'locator' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
            ],
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
            ],
            'antenna' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
            ],
            'note' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('bs_report', true);
        
        // Set table engine to MyISAM and charset to latin1
        //$this->db->query('ALTER TABLE `bs_report` ENGINE = MyISAM CHARACTER SET latin1 COLLATE latin1_swedish_ci');
    }

    public function down()
    {
        $this->forge->dropTable('bs_report');
    }
}