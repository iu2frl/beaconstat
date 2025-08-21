<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBeaconsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'callsign' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'locator' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
            ],
            'qrg' => [
                'type'       => 'DOUBLE',
                'unsigned'   => true,
            ],
            'band' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'qth' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'n.d.',
            ],
            'asl' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'antenna' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'n.d.',
            ],
            'mode' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => 'n.d.',
            ],
            'qtf' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => 'n.d.',
            ],
            'power' => [
                'type'       => 'FLOAT',
                'unsigned'   => true,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'confirmed' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('bs_beacon', true);
        
        // Set table engine to MyISAM and charset to latin1
        //$this->db->query('ALTER TABLE `bs_beacon` ENGINE = MyISAM CHARACTER SET latin1 COLLATE latin1_swedish_ci');
    }

    public function down()
    {
        $this->forge->dropTable('bs_beacon');
    }
}