<?php

namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateProjects extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type'=>'INT', 'unsigned'=>true, 'auto_increment'=>true],
            'name'       => ['type'=>'VARCHAR', 'constraint'=>255, 'null'=>false],
            'color'      => ['type'=>'VARCHAR', 'constraint'=>20, 'null'=>true],
            'created_at' => ['type'=>'DATETIME', 'null'=>false],
            'updated_at' => ['type'=>'DATETIME', 'null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('projects');
    }

    public function down() { $this->forge->dropTable('projects'); }
}
