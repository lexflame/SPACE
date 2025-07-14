<?php

namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateAttachments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type'=>'INT', 'unsigned'=>true,'auto_increment'=>true],
            'task_id'   => ['type'=>'INT', 'unsigned'=>true,'null'=>false],
            'filename'  => ['type'=>'VARCHAR','constraint'=>255,'null'=>false],
            'url'       => ['type'=>'VARCHAR','constraint'=>255,'null'=>false],
            'created_at'=> ['type'=>'DATETIME','null'=>false]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('attachments');
    }

    public function down() { $this->forge->dropTable('attachments'); }
}
