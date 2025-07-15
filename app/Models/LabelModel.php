<?php
namespace App\Models;

use CodeIgniter\Model;

class LabelModel extends Model
{
    protected $table = 'labels';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'task_id',
        'name',
    ];

    protected $useTimestamps = false;
}

