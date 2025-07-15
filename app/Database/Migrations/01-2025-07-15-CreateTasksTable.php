<?php
// Миграция для таблицы задач
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTasksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title'       => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status'      => [
                'type'    => 'VARCHAR',
                'constraint' => '50',
                'default' => 'pending',
            ],
            'due_date'    => [
                'type' => 'DATE',
                'null' => true,
            ],
            'attachment'  => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'latitude'    => [
                'type' => 'DECIMAL',
                'constraint' => '10,8',
                'null' => true,
            ],
            'longitude'   => [
                'type' => 'DECIMAL',
                'constraint' => '11,8',
                'null' => true,
            ],
            'created_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('tasks');
    }

    public function down()
    {
        $this->forge->dropTable('tasks');
    }
}

