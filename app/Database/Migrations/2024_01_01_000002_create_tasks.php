<?php 

namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateTasks extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type'=>'INT', 'unsigned'=>true, 'auto_increment'=>true],
            'project_id'   => ['type'=>'INT', 'unsigned'=>true, 'null'=>false],
            'title'        => ['type'=>'VARCHAR', 'constraint'=>255],
            'description'  => ['type'=>'TEXT','null'=>true],
            'due_date'     => ['type'=>'DATE','null'=>true],
            'repeat_rule'  => ['type'=>'VARCHAR', 'constraint'=>30, 'null'=>true],
            'cost'         => ['type'=>'DECIMAL','constraint'=>'10,2','null'=>true],
            'is_done'      => ['type'=>'TINYINT','constraint'=>1,'default'=>0],
            'tags'         => ['type'=>'VARCHAR', 'constraint'=>255,'null'=>true],
            'map'          => ['type'=>'VARCHAR', 'constraint'=>255,'null'=>true],         // Example: map id/string
            'map_marker'   => ['type'=>'VARCHAR', 'constraint'=>255,'null'=>true],         // Example: coordinates string
            'created_at'   => ['type'=>'DATETIME', 'null'=>false],
            'updated_at'   => ['type'=>'DATETIME', 'null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tasks');
    }

    public function down() { $this->forge->dropTable('tasks'); }
}
