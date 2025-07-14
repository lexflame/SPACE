<?php 

namespace App\Models;
use CodeIgniter\Model;

class ProjectModel extends Model {
    protected $table = 'projects';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name','color'];
    protected $useTimestamps = true;
}
