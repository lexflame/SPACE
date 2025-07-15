<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubtasksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'task_id'    => ['type' => 'INT', 'unsigned' => true],
            'title'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_done'    => ['type' => 'BOOLEAN', 'default' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('task_id', 'tasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('subtasks');
    }

    public function down()
    {
        $this->forge->dropTable('subtasks');
    }
}
