<?php 

namespace App\Models;
use CodeIgniter\Model;

class AttachmentModel extends Model {
    protected $table = 'attachments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['task_id','filename','url'];
    public    $timestamps = true;
}
