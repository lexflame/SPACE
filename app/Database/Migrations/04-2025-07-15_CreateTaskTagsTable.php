<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTaskTagsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'task_id' => ['type' => 'INT', 'unsigned' => true],
            'tag_id'  => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addKey(['task_id', 'tag_id'], true);
        $this->forge->addForeignKey('task_id', 'tasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tag_id', 'tags', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('task_tags');
    }

    public function down()
    {
        $this->forge->dropTable('task_tags');
    }
}
