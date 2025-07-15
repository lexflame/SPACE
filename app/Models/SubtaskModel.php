<?php
namespace App\Models;

use CodeIgniter\Model;

class SubtaskModel extends Model
{
    protected $table = 'subtasks';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'task_id',
        'title',
        'is_done',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'title' => 'required|min_length[1]',
    ];
}

