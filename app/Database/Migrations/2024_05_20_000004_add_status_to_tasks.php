<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateAttachments extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tasks', [
            'status' => [
                'type' => 'VARCHAR', 'constraint' => 32, 'default' => 'НОВОЕ',
                'after' => 'is_done'
            ]
        ]);
    }
    public function down()
    {
        $this->forge->dropColumn('tasks', 'status');
    }
}
